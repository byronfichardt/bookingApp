<?php

namespace App\V1\Application\Controllers;

use App\V1\Domain\AvailableTimes;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DatesController extends Controller
{
    private AvailableTimes $availableTimes;

    public function __construct(AvailableTimes $availableTimes)
    {
        $this->availableTimes = $availableTimes;
    }

    public function getAvailableTimes(Request $request): array
    {
        $date = $request->input('date');

        $date = Carbon::parse($date)->format('Y-m-d');

        return $this->availableTimes->get($date);
    }

    public function getNextAvailable(): string
    {
        return $this->availableTimes->getNextAvailable();
    }
}
