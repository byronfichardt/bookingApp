<?php

namespace App\V1\Application\Jobs;

use App\V1\Application\Mail\BookingApproval;
use App\V1\Application\Mail\BookingPending;
use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BookingApprovalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public Booking $booking;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Booking $booking)
    {
        $this->user = $user;
        $this->booking = $booking;
    }

    public function handle(): void
    {
        $email = config('admin.email');
        Mail::to($email)
            ->send(new BookingApproval($this->user, $this->booking));
    }
}
