<?php

namespace App\Console\Commands;

use App\Application\Models\Booking;
use Illuminate\Console\Command;

class deactivateBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deactivate:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bookings = Booking::where('status', 'active')->where('end_time', '<', now())->get();

        foreach ($bookings as $booking) {
            $booking->status = 'deactivated';
            $booking->save();
        }
    }
}
