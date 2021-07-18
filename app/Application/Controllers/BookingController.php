<?php

namespace App\Application\Controllers;

use App\Application\Models\Booking;
use App\Application\Models\User;
use App\Application\Resources\BookingResource;
use App\Domain\BookingCreator;
use App\Domain\CalendarEventInserter;
use App\Domain\CalendarEventRemover;
use App\Http\Controllers\Controller;
use App\Application\Jobs\BookingCanceledEmail;
use App\Application\Jobs\BookingCreatedEmail;
use App\Application\Jobs\BookingPendingEmail;
use App\Http\Requests\Auth\BookingStoreRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected CalendarEventInserter $calendarEventInserter;
    protected CalendarEventRemover $calendarEventRemover;
    protected BookingCreator $bookingCreator;

    public function __construct(
        CalendarEventInserter $calendarEventInserter,
        CalendarEventRemover $calendarEventRemover,
        BookingCreator $bookingCreator
    ) {
        $this->calendarEventInserter = $calendarEventInserter;
        $this->calendarEventRemover = $calendarEventRemover;
        $this->bookingCreator = $bookingCreator;
    }

    public function index()
    {
        return BookingResource::collection(
            Booking::with('user')
                ->where('status', 'active')
                ->get()
        );
    }

    public function pending()
    {
        return BookingResource::collection(Booking::with('user')->where('status', 'pending')->get());
    }

    public function fetch(Request $request, string $date)
    {
        return Booking::where('created_at', $date)->where('status', 'active')->get()->toJson();
    }

    public function store(BookingStoreRequest $request)
    {
        //create  user or check if they already exist
        $user = User::where('email', $request->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->getName(),
                'email' => $request->getEmail(),
                'phone' => $request->getPhone()
            ]);
        }

        $this->bookingCreator->create(
            $user,
            $request->getDateTime(),
            $request->getMinutesTotal(),
            $request->getBookingNote(),
            $request->getProducts()
        );

        BookingPendingEmail::dispatch($user);

        return ['status' => "success"];
    }

    public function approve($bookingId)
    {
        $booking = Booking::find($bookingId);

        $existingBooking = Booking::where('start_time', $booking->start_time)->active()->first();

        if($existingBooking) {
            return ['status' => 'exists'];
        }

        $user = User::find($booking->user_id);

        $products = $booking->products()->pluck('name')->toArray();

        $event = $this->calendarEventInserter->addEvent($user->name, $products, $booking);

        $booking->status = 'active';
        $booking->event_id = $event->getId();
        $booking->save();

        $token = base64_encode(json_encode(['booking_id' => $booking->id]));

        BookingCreatedEmail::dispatch($user, $token, $booking);

        return ['status' => "success"];
    }

    public function cancel(Request $request)
    {
        $token = $request->token;
        $data = json_decode(base64_decode($token),true);
        $booking = Booking::find($data['booking_id']);
        $user = $booking->user;

        BookingCanceledEmail::dispatch($user);

        $booking->delete();

        $this->calendarEventRemover->remove($booking);

        return view('cancel');
    }

    public function remove(Request $request, int $id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;

        BookingCanceledEmail::dispatch($user);

        $booking->delete();

        $this->calendarEventRemover->remove($booking);

        return ['status' => "success"];
    }
}
