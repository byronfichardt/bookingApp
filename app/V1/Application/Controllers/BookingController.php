<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Jobs\BookingApprovalEmail;
use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use App\V1\Application\Resources\BookingResource;
use App\V1\Domain\AvailableTimes;
use App\V1\Domain\BookingCreator;
use App\V1\Domain\BookingEditor;
use App\V1\Domain\CalendarEventInserter;
use App\V1\Domain\CalendarEventRemover;
use App\Http\Controllers\Controller;
use App\V1\Application\Jobs\BookingCanceledEmail;
use App\V1\Application\Jobs\BookingCreatedEmail;
use App\V1\Application\Jobs\BookingPendingEmail;
use App\V1\Application\Requests\Auth\BookingStoreRequest;
use App\V1\Domain\Decoder;
use App\V1\Domain\dtos\UserDto;
use App\V1\Domain\UserCreator;
use Carbon\Carbon;
use Google\Service\Calendar\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    protected CalendarEventInserter $calendarEventInserter;
    protected CalendarEventRemover $calendarEventRemover;
    protected BookingCreator $bookingCreator;
    private UserCreator $userCreator;
    private BookingEditor $bookingEditor;

    public function __construct(
        CalendarEventInserter $calendarEventInserter,
        CalendarEventRemover $calendarEventRemover,
        BookingCreator $bookingCreator,
        UserCreator $userCreator,
        BookingEditor $bookingEditor
    ) {
        $this->calendarEventInserter = $calendarEventInserter;
        $this->calendarEventRemover = $calendarEventRemover;
        $this->bookingCreator = $bookingCreator;
        $this->userCreator = $userCreator;
        $this->bookingEditor = $bookingEditor;
    }

    public function index(): AnonymousResourceCollection
    {
        return BookingResource::collection(
            Booking::with('user')
                ->where('status', 'active')
                ->get()
        );
    }

    public function pending(): AnonymousResourceCollection
    {
        return BookingResource::collection(
            Booking::with('user')
            ->where('status', 'pending')->get()
        );
    }

    public function store(BookingStoreRequest $request): array
    {
        $name = $request->getName();
        $email = $request->getEmail();
        $phone = $request->getPhone();
        $note = $request->getBookingNote();

        $bookingDate = Carbon::parse($request->getDateTime());

        /** @var AvailableTimes $availableTimes */
        $availableTimes = app(AvailableTimes::class);
        $times = $availableTimes->get($bookingDate->toDateString());

        if(! in_array($bookingDate->hour, $times)) {
            return ['status' => "fail"];
        }

        $userDto = new UserDto($name, $email, $phone);

        $user = $this->userCreator->create($userDto);

        $booking = $this->bookingCreator->create(
            $user->id,
            $request->getDateTime(),
            $request->getMinutesTotal(),
            sprintf('name: %s, Note: %s', $name, $note),
            $request->getProducts(),
            $request->getImagePath(),
        );

        BookingApprovalEmail::dispatch($user, $booking);
        BookingPendingEmail::dispatch($user);

        return ['status' => "success"];
    }

    public function upload(Request $request)
    {
        $year = now()->year;

        $filePath = $request->file('file')->storePublicly('public/' . $year, 's3');

        return response()->json(['path'=>$filePath]);
    }

    public function edit(Request $request, int $bookingId): array
    {
        /** @var Booking $booking */
        $booking = Booking::find($bookingId);

        $minutes = $booking->calculateMinutes();

        $this->bookingEditor->update($booking, $request->input('start'), $minutes);

        return ['status' => "success"];
    }

    public function approve($bookingId): array
    {
        $booking = Booking::find($bookingId);

        $existingBooking = Booking::where('start_time', $booking->start_time)->active()->first();

        if($existingBooking) {
            return ['status' => 'exists'];
        }

        $user = User::find($booking->user_id);

        $products = $booking->products()->pluck('name')->toArray();

        if(app()->environment('local')) {
            $event = new Event();
            $event->setId(0);
        }else {
            $event = $this->calendarEventInserter->addEvent($user->name, $products, $booking);
        }

        $booking->setActive($event->getId());

        $token = Decoder::encode(['booking_id' => $booking->id]);

        BookingCreatedEmail::dispatch($user, $token, $booking);

        return ['status' => "success"];
    }

    public function cancel(Request $request)
    {
        $token = $request->token;
        $data = Decoder::decode($token);
        $booking = Booking::find($data['booking_id']);

        $user = $booking->user;

        BookingCanceledEmail::dispatch($user);

        $booking->delete();

        if(! is_null($booking->event_id)) {
            $this->calendarEventRemover->remove($booking);
        }

        return view('cancel');
    }

    public function remove(Request $request, int $id): array
    {
        $booking = Booking::find($id);
        $user = $booking->user;

        if($request->sendEmail){
            BookingCanceledEmail::dispatch($user);
        }

        $booking->delete();

        if(! is_null($booking->event_id)) {
            $this->calendarEventRemover->remove($booking);
        }

        return ['status' => "success"];
    }
}
