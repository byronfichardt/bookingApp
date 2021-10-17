<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use Carbon\Carbon;

class BookingReminders
{
    public function fetch()
    {
        $bookings = Booking::where('status', 'active')
            ->where('created_at', '<=', now()->subDays(5))
            ->get();

        return $bookings->map(function (Booking $booking) {
            if($booking->start_time->format('Y-m-d') == now()->addDays(2)->format('Y-m-d')) {
                return $booking;
            }
            return null;
        })->filter()->values();
    }
}
