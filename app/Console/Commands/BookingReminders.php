<?php

namespace App\Console\Commands;

use App\V1\Application\Models\Booking;
use App\V1\Domain\SendReminders;
use Illuminate\Console\Command;

class BookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send out booking reminders';
    private \App\V1\Domain\BookingReminders $bookingReminders;
    private SendReminders $sendReminders;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        \App\V1\Domain\BookingReminders $bookingReminders,
        SendReminders $sendReminders
    ) {
        parent::__construct();
        $this->bookingReminders = $bookingReminders;
        $this->sendReminders = $sendReminders;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $bookings = $this->bookingReminders->fetch();

        $this->sendReminders->send($bookings);
    }
}
