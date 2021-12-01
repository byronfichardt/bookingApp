<?php

namespace App\V1\Domain;

use App\V1\Application\Models\BlockedDate;
use App\V1\Domain\Booking\HoursByDate;

class AvailableTimes
{
    public const HOURS = [9, 13, 16, 19];

     public function get(string $date): array
     {
         $hours = collect(self::HOURS);
         $notAvailableHours = HoursByDate::get($hours, $date);

         $blockedDates = BlockedDate::whereDate('blocked_date', $date)->get();

         if ($blockedDates->count() == 1) {
             $blockedDate = $blockedDates->first();
             if(! $blockedDate) {
                 return $hours->diff($notAvailableHours)->values()->toArray();
             }

             if(! $blockedDate->hasTimes()) {
                 return [];
             }

             foreach($blockedDate->times() as $time) {
                 $notAvailableHours->add((int) $time);
             }

             return $hours->diff($notAvailableHours)->unique()->values()->toArray();
         }

         $times = $blockedDates->pluck('times')->toArray();

         foreach($times as $time) {
             $notAvailableHours->add((int) $time);
         }

         return $hours->diff($notAvailableHours)->unique()->values()->toArray();

     }
}
