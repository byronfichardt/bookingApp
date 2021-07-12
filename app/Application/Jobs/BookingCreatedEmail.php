<?php

namespace App\Application\Jobs;

use App\Application\Mail\BookingCreated;
use App\Application\Models\Booking;
use App\Application\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BookingCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    public string $token;

    public Booking $booking;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $token, Booking $booking)
    {
        $this->user = $user;
        $this->token = $token;
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = config('admin.email');
        Mail::to($this->user->email)->bcc($email)
            ->send(new BookingCreated($this->token, $this->booking, $this->user->name));
    }
}
