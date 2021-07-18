<?php

namespace App\V1\Application\Jobs;

use App\V1\Application\Mail\BookingCanceled;
use App\V1\Application\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BookingCanceledEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $email = config('admin.email');
        Mail::to($this->user->email)->bcc($email)
            ->send(new BookingCanceled());
    }
}
