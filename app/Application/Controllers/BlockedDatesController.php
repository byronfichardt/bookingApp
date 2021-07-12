<?php

namespace App\Application\Controllers;

use App\Application\Models\BlockedDate;
use App\Application\Resources\BlockedDatesResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockedDatesController extends Controller
{
    public function index()
    {
        $blockedDates = BlockedDate::get();

        return BlockedDatesResource::collection($blockedDates);
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
