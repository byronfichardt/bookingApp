<?php

namespace App\V1\Domain;

use App\V1\Application\Models\BlockedDate;
use App\V1\Application\Models\Booking;
use App\V1\Domain\Booking\BookingHours;
use Carbon\Carbon;

class AvailableTimes
{
    public const HOURS = [9, 13, 16];

     public function get(string $date): array
     {
         $hours = collect(self::HOURS);
         $notAvailableHours = BookingHours::hoursByDate($hours, $date);

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

     public function getNextAvailable(): string
     {
         $datesAndTimes = $this->transformAndMergeBookingAndBlocked();

         $dates = $datesAndTimes->reduce(function($carry, $item) {
             if(isset($carry[$item['date']])) {
                 $carry[$item['date']][] = $item['time'];
                 return $carry;
             }
             $carry[$item['date']] = [$item['time']];
             return $carry;
         }, []);

         ksort($dates);

         foreach ($dates as $key => $date) {
             if(count($date) < count(AvailableTimes::HOURS)){
                 return $key;
             }
         }
     }

    /**
     * @return mixed
     */
    protected function transformAndMergeBookingAndBlocked()
    {
        $bookings = Booking::where('start_time','>', now()->addDay())->get()->map(function ($booking) {
            return [
                'date' => Carbon::parse($booking->start_time)->format('Y-m-d'),
                'time' => Carbon::parse($booking->start_time)->hour
            ];
        });

        $blockedDates = BlockedDate::where('blocked_date','>', now()->addDay())->get()->map(function ($blockedDate) {
            $all = [];
            if (!$blockedDate->times) {
                $times = AvailableTimes::HOURS;
            } else {
                $times = explode(',', $blockedDate->times);
            }

            foreach ($times as $time) {
                $all[] = [
                    'date' => Carbon::parse($blockedDate->blocked_date)->format('Y-m-d'),
                    'time' => (int)$time
                ];
            }
            return $all;
        })->flatten(1);

        return $blockedDates->merge($bookings);
    }
}
