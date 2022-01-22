<?php

namespace App\V1\Domain;

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

         return $hours->diff($notAvailableHours)->unique()->values()->toArray();
     }

     public function getNextAvailable(): string
     {
         $datesAndTimes = $this->transformBookings();

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

         return now()->addDay()->format('Y-m-d');
     }

    /**
     * @return mixed
     */
    protected function transformBookings()
    {
        // make sure we start from tomorrow at the beginning of the day
        $date = now()->addDay()->hour(1);

        return Booking::where('start_time','>', $date)->get()
            ->map(function ($booking) {
                return [
                    'date' => Carbon::parse($booking->start_time)->format('Y-m-d'),
                    'time' => Carbon::parse($booking->start_time)->hour
                ];
            });
    }
}
