<?php

namespace Tests\Application\Controllers;

use App\V1\Application\Jobs\BookingCanceledEmail;
use App\V1\Application\Jobs\BookingCreatedEmail;
use App\V1\Application\Jobs\BookingPendingEmail;
use App\V1\Application\Models\Booking;
use App\V1\Application\Models\Product;
use App\V1\Application\Models\User;
use App\V1\Domain\BookingCreator;
use App\V1\Domain\CalendarEventInserter;
use App\V1\Domain\CalendarEventRemover;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    public function testItCanGetBookings()
    {
        $bookings = Booking::factory()->count(5)->create();

        $response = $this->get('api/bookings');

        $response->assertJsonCount('5','data');

        $response->assertJsonFragment([
            'id' => $bookings->first()->id,
            'note' => $bookings->first()->note,
            'start' => $bookings->first()->start_time->toDateTimeString(),
            'end' => $bookings->first()->end_time->toDateTimeString(),
            'products' => $bookings->first()->products,
            'user' => $bookings->first()->user,
        ]);

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'note',
                    'start',
                    'end',
                    'products',
                    'user',
                ]
            ]
        ]);
    }

    public function testItCanGetPending()
    {
        $bookings = Booking::factory()->count(5)->create();

        $bookings->first()->status = 'pending';
        $bookings->first()->save();

        $response = $this->get('api/bookings/pending');

        $response->assertJsonCount('1','data');

        $response->assertJsonFragment([
            'id' => $bookings->first()->id,
            'note' => $bookings->first()->note,
            'start' => $bookings->first()->start_time->toDateTimeString(),
            'end' => $bookings->first()->end_time->toDateTimeString(),
            'products' => $bookings->first()->products,
            'user' => $bookings->first()->user,
        ]);

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'note',
                    'start',
                    'end',
                    'products',
                    'user',
                ]
            ]
        ]);
    }

    public function testItCanFetchBookings()
    {
        $bookings = Booking::factory()->count(5)->create();

        $bookings->first()->created_at = now()->subDay();
        $bookings->first()->save();

        $date = $bookings->first()->created_at->toDateTimeString();

        $response = $this->get("api/bookings/$date");

        $response->assertJsonCount('1');

        $response->assertJsonFragment([
            'id' => $bookings->first()->id,
            'note' => $bookings->first()->note,
            'start_time' => $bookings->first()->start_time->toDateTimeString(),
            'end_time' => $bookings->first()->end_time->toDateTimeString(),
            'user_id' => (string) $bookings->first()->user_id,
        ]);

        $response->assertJsonStructure([
            [
                'id',
                'note',
                'start_time',
                'end_time',
                'user_id',
            ]
        ]);
    }

    public function testItCanStoreBookingsWithExistingUser()
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone,
            'date_time' => now()->toDateTimeString(),
            'minutes_total' => 120,
            'booking_note' => $this->faker->sentence,
            'products' => [],
        ];

        $bookingCreator = $this->mock(BookingCreator::class);
        $bookingCreator->shouldReceive('create')->once()
            ->withArgs([
                $user->id,
                $data['date_time'],
                $data['minutes_total'],
                'name: ' . $data['name'] . ' Note: ' . $data['booking_note'],
                $data['products']
            ]);
        Bus::fake();

        $response = $this->post('api/bookings', $data);

        Bus::assertDispatched(BookingPendingEmail::class);

        $response->assertJson([
            'status' => 'success'
        ]);
    }

    public function testItCanStoreBookingsWithNewUser()
    {
        $data = [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'date_time' => now()->toDateTimeString(),
            'minutes_total' => 120,
            'booking_note' => $this->faker->sentence,
            'products' => [],
        ];

        $bookingCreator = $this->mock(BookingCreator::class);
        $bookingCreator->shouldReceive('create')->once()
            ->withAnyArgs();

        Bus::fake();

        $response = $this->post('api/bookings', $data);

        Bus::assertDispatched(BookingPendingEmail::class);

        $response->assertJson([
            'status' => 'success'
        ]);
    }

    public function testItCanApproveBookingNew()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'status' => 'pending',
            'user_id' => $user->id
        ]);

        $product = Product::factory()->create();
        $eventId = $this->faker->uuid;

        $booking->products()->attach($product->id, ['quantity' => 1]);

        $event = new Event();
        $event->setId($eventId);

        $calenderEventInserter = $this->mock(CalendarEventInserter::class);
        $calenderEventInserter->shouldReceive('addEvent')->once()
            ->withAnyArgs()
            ->andReturn($event);

        Bus::fake();

        $response = $this->get("api/bookings/$booking->id/approve", );

        Bus::assertDispatched(BookingCreatedEmail::class);

        $response->assertJson([
            'status' => 'success'
        ]);

        $this->assertDatabaseHas('bookings', [
            'status' => 'active',
            'event_id' => $eventId
        ]);
    }

    public function testItCanApproveBookingExisting()
    {
        $user = User::factory()->create();
        $startTime = now()->toDateTimeString();
        Booking::factory()->create([
            'status' => 'active',
            'user_id' => $user->id,
            'start_time' => $startTime
        ]);

        $booking = Booking::factory()->create([
            'status' => 'pending',
            'user_id' => $user->id,
            'start_time' => $startTime
        ]);

        $response = $this->get("api/bookings/$booking->id/approve");

        $response->assertJson([
            'status' => 'exists'
        ]);
    }

    public function testItCanCancelBookingWithoutEventId()
    {
        $user = User::factory()->create();

        $booking = Booking::factory()->create([
            'status' => 'active',
            'user_id' => $user->id,
            'event_id' => null
        ]);

        $token = base64_encode(json_encode(['booking_id' => $booking->id]));

        Bus::fake();

        $response = $this->get("cancel?token=$token");

        $response->assertOk();

        Bus::assertDispatched(BookingCanceledEmail::class);

        $this->assertDeleted('bookings', $booking->toArray());
    }

    public function testItCanCancelBookingWithEventId()
    {
        $user = User::factory()->create();

        $booking = Booking::factory()->create([
            'status' => 'active',
            'user_id' => $user->id,
            'event_id' => $this->faker->uuid
        ]);

        $booking = Booking::find($booking->id);

        $token = base64_encode(json_encode(['booking_id' => $booking->id]));

        $calendarEventRemover = $this->mock(CalendarEventRemover::class);
        $calendarEventRemover->shouldReceive('remove')->once();

        Bus::fake();

        $response = $this->get("cancel?token=$token");

        $response->assertOk();

        Bus::assertDispatched(BookingCanceledEmail::class);

        $this->assertDeleted('bookings', $booking->toArray());
    }

    public function testItCanRemoveBooking()
    {
        $user = User::factory()->create();

        $booking = Booking::factory()->create([
            'status' => 'active',
            'user_id' => $user->id,
            'event_id' => $this->faker->uuid
        ]);

        $calendarEventRemover = $this->mock(CalendarEventRemover::class);
        $calendarEventRemover->shouldReceive('remove')->once();

        Bus::fake();

        $response = $this->get("api/bookings/$booking->id/cancel");

        $response->assertOk();

        Bus::assertDispatched(BookingCanceledEmail::class);

        $this->assertDeleted('bookings', $booking->toArray());
    }
}
