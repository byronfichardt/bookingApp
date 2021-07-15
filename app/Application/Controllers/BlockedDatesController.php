<?php

namespace App\Application\Controllers;

use App\Application\Models\BlockedDate;
use App\Application\Models\Booking;
use App\Application\Resources\BlockedDatesResource;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlockedDatesController extends Controller
{
    public function index()
    {
        $blockedDates = BlockedDate::get();

        return BlockedDatesResource::collection($blockedDates);
    }

    public function getAvailableTimes(Request $request)
    {
        $date = $request->input('date');
        $date = Carbon::parse($date)->format('Y-m-d');
        $bookings = Booking::where('status', 'active')->whereDate('start_time', $date)->get();
        $hours = [9, 13, 16];
        $not_available_hours = [];
        foreach($bookings as $booking) {
            $start = Carbon::parse($booking->start_time)->hour;
            foreach ($hours as $hour) {
                if($hour == $start) {
                    $not_available_hours[] = $start;
                }
            }
        }

        $blockedDates = BlockedDate::whereDate('blocked_date', $date)->get();

        foreach($blockedDates as $blockedDate) {
            if(! $blockedDate->times) {
                $not_available_hours = $hours;
                continue;
            }

            $times = explode(',', $blockedDate->times);

            foreach($times as $time) {
                $not_available_hours[] = (int) $time;
            }
        }

        $not_available_hours = array_unique($not_available_hours);
        return array_diff($hours,$not_available_hours);
    }

    public function store(Request $request)
    {
        BlockedDate::create([
            'blocked_date' => $request->date,
            'times' => $request->times
        ]);

        return ['status' => "success"];
    }

    public function delete(Request $request, int $id)
    {
        $blockedDate = BlockedDate::find($id);

        $blockedDate->delete();

        return ['status' => "success"];
    }
}
