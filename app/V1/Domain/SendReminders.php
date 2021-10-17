<?php

namespace App\V1\Domain;

use App\V1\Application\Jobs\BookingReminderEmail;
use App\V1\Application\Models\Booking;
use Illuminate\Support\Collection;

class SendReminders
{
    public function send(Collection $bookings)
    {
        $bookings->each(function (Booking $booking) {
            $user = $booking->user;
            $token = Decoder::encode(['booking_id' => $booking->id]);
            BookingReminderEmail::dispatch($user, $token, $booking);
        });
    }
}
