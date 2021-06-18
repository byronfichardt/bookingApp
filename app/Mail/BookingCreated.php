<?php

namespace App\Mail;

use App\Application\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public string $cancelUrl;

    public string $bookingDate;

    public $co_address;

    public $address_line;

    public $city;

    public $zip;
    public string $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $token, Booking $booking, string $name)
    {
        $this->cancelUrl = config('app.url') . '/cancel?token=' . $token;
        $this->bookingDate = $booking->start_time;
        $this->co_address = config('admin.address.co');
        $this->address_line = config('admin.address.line');
        $this->city = config('admin.address.city');
        $this->zip = config('admin.address.zip');
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.bookings.created')
            ->subject('Impulse Nails - Appointment confirmation.');
    }
}
