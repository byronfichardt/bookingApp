<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use Illuminate\Support\Carbon;

class BookingEditor
{
    public function update(
        Booking $booking,
        string $start,
        int $minutes
    ): Booking {
        $booking->start_time = $start;
        $booking->end_time = Carbon::parse($booking->start_time)->addMinutes($minutes)->format('Y-m-d H:i:s');
        $booking->save();
    }
}
