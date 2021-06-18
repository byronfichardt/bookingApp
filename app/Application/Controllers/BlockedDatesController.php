<?php

namespace App\Application\Controllers;

use App\Application\Models\BlockedDate;
use App\Application\Models\Booking;
use App\Application\Models\User;
use App\Application\Resources\BlockedDatesResource;
use App\Application\Resources\BookingResource;
use App\Http\Controllers\Controller;
use App\Jobs\BookingCanceledEmail;
use App\Jobs\BookingCreatedEmail;
use App\Jobs\BookingPendingEmail;
use Carbon\Carbon;
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
            'blocked_date' => $request->date
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
