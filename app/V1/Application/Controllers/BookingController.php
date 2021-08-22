<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Jobs\BookingApprovalEmail;
use App\V1\Application\Jobs\SaveImage;
use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use App\V1\Application\Resources\BookingResource;
use App\V1\Domain\BookingCreator;
use App\V1\Domain\CalendarEventInserter;
use App\V1\Domain\CalendarEventRemover;
use App\Http\Controllers\Controller;
use App\V1\Application\Jobs\BookingCanceledEmail;
use App\V1\Application\Jobs\BookingCreatedEmail;
use App\V1\Application\Jobs\BookingPendingEmail;
use App\V1\Application\Requests\Auth\BookingStoreRequest;
use App\V1\Domain\Decoder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

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
        return BookingResource::collection(Booking::with('user')->where('status', 'pending')->get());
    }

    public function fetch(Request $request, string $date)
    {
        return Booking::where('created_at', $date)->where('status', 'active')->get()->toJson();
    }

    public function store(BookingStoreRequest $request): array
    {
        //check if user already exist
        $user = User::findByEmail($request->getEmail());

        if (!$user) {
            $user = User::create([
                'name' => $request->getName(),
                'email' => $request->getEmail(),
                'phone' => $request->getPhone()
            ]);
        }

        $booking = $this->bookingCreator->create(
            $user->id,
            $request->getDateTime(),
            $request->getMinutesTotal(),
            'name: ' . $request->getName() . ' Note: ' . $request->getBookingNote(),
            $request->getProducts(),
        );

        if($image = $request->image) {
            SaveImage::dispatch($image, $booking->id);
        }

        BookingApprovalEmail::dispatch($user, $booking);

        BookingPendingEmail::dispatch($user);

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

        $event = $this->calendarEventInserter->addEvent($user->name, $products, $booking);

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

    public function remove(Request $request, int $id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;

        BookingCanceledEmail::dispatch($user);

        $booking->delete();

        if(! is_null($booking->event_id)) {
            $this->calendarEventRemover->remove($booking);
        }

        return ['status' => "success"];
    }
}
