<?php

namespace App\Domain;

use App\Application\Models\BlockedDate;
use App\Application\Models\Booking;
use Illuminate\Support\Carbon;

class AvailableTimes
{
     public function get(string $date)
     {
         $hours = collect([9, 13, 16]);
         $not_available_hours = collect();

         Booking::active()
             ->whereDate('start_time', $date)
             ->get()
             ->each(function ($booking) use($hours, $not_available_hours) {
                 $start = Carbon::parse($booking->start_time)->hour;
                 if($hours->search($start) >= 0) {
                     $not_available_hours->add($start);
                 };
             });

         $blockedDate = BlockedDate::whereDate('blocked_date', $date)->first();

         if(! $blockedDate) {
             return $hours->diff($not_available_hours)->toArray();
         }

         if(! $blockedDate->hasTimes()) {
             return [];
         }

         foreach($blockedDate->times() as $time) {
             $not_available_hours->add((int) $time);
         }

         return $hours->diff($not_available_hours->unique())->values()->toArray();
     }
}
