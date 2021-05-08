<?php

namespace App\Application\Controllers;

use App\Application\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingViewController extends Controller
{
    public function cancel(Request $request)
    {
        $token = $request->get('token');
        $userDetails = json_decode(base64_decode($token), true);
        $user = User::where('email', $userDetails['email'])->first();
        $bookings = $user->bookings;

        foreach ($bookings as $booking) {
            $booking->status = 'canceled';
            $booking->save();
        }

        return view('cancel');
    }
}
