<?php

namespace App\V1\Application\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCanceled extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): BookingCanceled
    {
        return $this->markdown('emails.bookings.canceled');
    }
}
