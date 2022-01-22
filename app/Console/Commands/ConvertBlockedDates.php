<?php

namespace App\Console\Commands;

use App\V1\Application\Models\BlockedDate;
use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use App\V1\Domain\BookingCreator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ConvertBlockedDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blockedDates:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Blocked dates to events';

    private BookingCreator $bookingCreator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BookingCreator $bookingCreator)
    {
        parent::__construct();
        $this->bookingCreator = $bookingCreator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $blockedDates = BlockedDate::all();

        foreach($blockedDates as $blockedDate) {
            $adminUser = User::admin();
            $date = Carbon::parse($blockedDate->blocked_date);
            $times = explode(',', $blockedDate->times);
            foreach($times as $time) {
                $time = trim($time);
                $date->hour($time);

                if(Booking::whereDate('start_time', $date)->first()) {
                    continue;
                }

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
        }
    }
}
