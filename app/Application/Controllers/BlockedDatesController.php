<?php

namespace App\Application\Controllers;

use App\Application\Models\BlockedDate;
use App\Application\Resources\BlockedDatesResource;
use App\Domain\AvailableTimes;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlockedDatesController extends Controller
{
    private AvailableTimes $availableTimes;

    public function __construct(AvailableTimes $availableTimes)
    {
        $this->availableTimes = $availableTimes;
    }

    public function index()
    {
        $blockedDates = BlockedDate::get();

        return BlockedDatesResource::collection($blockedDates);
    }

    public function getAvailableTimes(Request $request)
    {
        $date = $request->input('date');

        $date = Carbon::parse($date)->format('Y-m-d');

        return $this->availableTimes->get($date);
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
