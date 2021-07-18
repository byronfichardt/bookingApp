<?php

namespace App\V1\Application\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingPending extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): BookingPending
    {
        return $this->markdown('emails.bookings.pending');
    }
}
