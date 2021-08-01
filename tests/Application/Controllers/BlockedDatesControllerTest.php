<?php

namespace Tests\Application\Controllers;

use App\V1\Application\Models\BlockedDate;
use App\V1\Domain\AvailableTimes;
use Tests\TestCase;

class BlockedDatesControllerTest extends TestCase
{
    public function testItCanListBockedDates()
    {
        BlockedDate::factory()->count(5)->create();

        $response = $this->get('api/blocked');

        $response->assertJsonCount(5, 'data');

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'date',
                    'times'
                ]
            ]
        ]);
    }

    public function testItCanGetAvailableTimes()
    {
        $date = now()->toDateString();
        $availableTimes = $this->mock(AvailableTimes::class);
        $availableTimes->shouldReceive('get')->once()->with($date)
            ->andReturn([
                9,
                13,
                16
            ]);

        $response = $this->get('api/bookings/availableTimes?date=' . $date );

        $response->assertJson([
            9,
            13,
            16
        ]);
    }

    public function testItCanStoreBockedDates()
    {
        $blockedDate = BlockedDate::factory()->make();

        $response = $this->post('api/blocked', [
            'date' => $blockedDate->blocked_date,
            'times' => $blockedDate->times
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('blocked_dates', [
            'blocked_date' => $blockedDate->blocked_date,
            'times' => $blockedDate->times
        ]);
    }

    public function testItCanDeleteBockedDates()
    {
        $blockedDate = BlockedDate::factory()->create();

        $response = $this->delete("api/blocked/$blockedDate->id");

        $response->assertOk();

        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseMissing('blocked_dates', [
            'id' => $blockedDate->id
        ]);
    }
}
