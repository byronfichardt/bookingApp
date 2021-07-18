<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Models\User;
use App\Http\Controllers\Controller;
use App\V1\Domain\Decoder;
use Illuminate\Http\Request;

class BookingViewController extends Controller
{
    public function cancel(Request $request)
    {
        $token = $request->get('token');
        $userDetails = Decoder::decode($token);
        $user = User::findByEmail($userDetails['email']);
        $bookings = $user->bookings;

        foreach ($bookings as $booking) {
            $booking->status = 'canceled';
            $booking->save();
        }

        return view('cancel');
    }
}
