<?php

namespace App\Application\Controllers;

use App\Application\Models\Booking;
use App\Application\Models\User;
use App\Application\Resources\BookingResource;
use App\Http\Controllers\Controller;
use App\Jobs\BookingCreatedEmail;
use App\Mail\BookingCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        return BookingResource::collection(Booking::all());
    }

    public function fetch(Request $request, string $date)
    {
        return Booking::where('created_at', $date)->get()->toJson();
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
            'name' => $request->booking_note
        ]);

        foreach ($request->products as $product) {
            $booking->products()->attach($product['id'], ['quantity' => (int) data_get($product, 'quantity', 1)]);
        }

        $token = base64_encode(json_encode($user->toArray()));

        BookingCreatedEmail::dispatch($user, $token);

        return ['status' => "success"];
    }
}
