<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Models\BlockedDate;
use App\V1\Application\Models\User;
use App\V1\Application\Resources\BlockedDatesResource;
use App\V1\Domain\AvailableTimes;
use App\Http\Controllers\Controller;
use App\V1\Domain\BookingCreator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JetBrains\PhpStorm\ArrayShape;

class BlockedDatesController extends Controller
{
    private BookingCreator $bookingCreator;

    public function __construct(BookingCreator $bookingCreator)
    {
        $this->bookingCreator = $bookingCreator;
    }

    #[ArrayShape(['status' => "string"])]
    public function store(Request $request): array
    {
        $adminUser = User::admin();
        $date = Carbon::parse($request->date);
        foreach($request->times as $time) {
            $date->hour($time);
            $this->bookingCreator->create(
                $adminUser->id,
                $date,
                0,
                'BLOCKED',
                [],
                null,
                'active'
            );
        }

        return ['status' => "success"];
    }
}
