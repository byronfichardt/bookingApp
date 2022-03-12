<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use App\V1\Domain\Booking\BookingHours;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AvailableTimes
{
    public const HOURS = [9, 13, 16];
    public const LONG_HOURS = [9, 13, 16, 19];

     public function get(string $date): array
     {
         $hours = $this->getHoursForDate($date);

         $notAvailableHours = BookingHours::hoursByDate($hours, $date);

         return $hours->diff($notAvailableHours)->unique()->values()->toArray();
     }

     public function getNextAvailable(): string
     {
         $date = now()->addDay()->startOfDay();
         $bookings = Booking::where('start_time','>', $date)
             ->select(DB::raw('date(start_time) as date'), DB::raw('count(*) as total'))
             ->groupBy('date')
             ->orderBy('date')
             ->get();

         foreach ($bookings as $booking) {
             $hours = $this->getHoursForDate($booking->date);

             if($booking->total < $hours->count()){
                 return $booking->date;
             }
         }

         return now()->addDay()->format('Y-m-d');
     }

    public function getHoursForDate(string $date): Collection
    {
        $wednesday = Carbon::parse($date)->isWednesday();
        $tuesday = Carbon::parse($date)->isTuesday();
        $monday = Carbon::parse($date)->isMonday();

        if($monday || $tuesday || $wednesday) {
            $hours = collect(self::LONG_HOURS);
        }else {
            $hours = collect(self::HOURS);
        }

        return $hours;
    }
}
