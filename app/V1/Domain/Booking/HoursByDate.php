<?php

namespace App\V1\Domain\Booking;

use App\V1\Application\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class HoursByDate
{
    public static function get(Collection $hours, string $date): Collection
    {
        $bookings = Booking::active()->whereDate('start_time', $date)->get();

        return $bookings->map(function ($booking) use($hours) {
            $start = Carbon::parse($booking->start_time)->hour;
            if($hours->search($start) >= 0) {
                return $start;
            };
            return null;
        })->filter()->values();
    }
}
