<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Models\BlockedDate;
use App\V1\Application\Resources\BlockedDatesResource;
use App\V1\Domain\AvailableTimes;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlockedDatesController extends Controller
{
    private AvailableTimes $availableTimes;

    public function __construct(AvailableTimes $availableTimes)
    {
        $this->availableTimes = $availableTimes;
    }

    public function index(): AnonymousResourceCollection
    {
        $blockedDates = BlockedDate::orderBy('blocked_date', 'desc')->limit(200)->get();

        return BlockedDatesResource::collection($blockedDates);
    }

    public function store(Request $request)
    {
        if(empty($request->times)) {
            $times = implode(',', AvailableTimes::HOURS);
        }else {
            $times = implode(',',$request->times);
        }
        BlockedDate::create([
            'blocked_date' => $request->date,
            'times' => $times
        ]);

        return ['status' => "success"];
    }

    public function delete(Request $request, int $id): array
    {
        $blockedDate = BlockedDate::find($id);

        $blockedDate->delete();

        return ['status' => "success"];
    }
}
