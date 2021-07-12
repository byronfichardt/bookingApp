<?php

namespace App\Application\Controllers;

use App\Application\Models\Booking;
use App\Application\Models\User;
use App\Application\Resources\BookingResource;
use App\Domain\CalendarEventInserter;
use App\Domain\CalendarEventRemover;
use App\Http\Controllers\Controller;
use App\Application\Jobs\BookingCanceledEmail;
use App\Application\Jobs\BookingCreatedEmail;
use App\Application\Jobs\BookingPendingEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private CalendarEventInserter $calendarEventInserter;
    private CalendarEventRemover $calendarEventRemover;

    public function __construct(CalendarEventInserter $calendarEventInserter, CalendarEventRemover $calendarEventRemover)
    {
        $this->calendarEventInserter = $calendarEventInserter;
        $this->calendarEventRemover = $calendarEventRemover;
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

    public function store(Request $request)
    {
        //create  user or check if they already exist
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);
        }

        $booking = Booking::create([
            'start_time' => Carbon::parse($request->date_time),
            'end_time' => Carbon::parse($request->date_time)->addMinutes($request->minutes_total),
            'user_id' => $user->id,
            'name' => $request->booking_note,
            'status' => 'pending'
        ]);

        foreach ($request->products as $product) {
            $booking->products()->attach($product['id'], ['quantity' => (int) data_get($product, 'quantity', 1)]);
        }

        BookingPendingEmail::dispatch($user);

        return ['status' => "success"];
    }

    public function approve($bookingId)
    {
        $booking = Booking::find($bookingId);

        $user = User::find($booking->user_id);

        $products = $booking->products()->pluck('name')->toArray();
        $description = implode(', ', $products);

        $start = Carbon::parse($booking->start_time, 'Europe/Copenhagen')->toIso8601String();
        $end = Carbon::parse($booking->end_time, 'Europe/Copenhagen')->toIso8601String();

        $event = $this->calendarEventInserter->addEvent($user->name, $description, $start, $end);

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
