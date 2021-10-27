<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use Carbon\Carbon;

class BookingReminders
{
    public function fetch()
    {
        return Booking::where('status', 'active')
            ->whereDate('start_time', now()->addDays(2))
            ->get();
    }
}
