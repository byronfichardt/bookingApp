<?php

namespace App\Mail;

use App\Application\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCreated extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $cancelUrl;

    public string $bookingDate;

    public $co_address;

    public $address_line;

    public $city;

    public $zip;

    public string $name;

    /**
     * @var mixed
     */
    public $products;
    /**
     * @var int|mixed
     */
    public $totalTime;
    /**
     * @var int|mixed
     */
    public $totalPrice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $token, Booking $booking, string $name)
    {
        $this->cancelUrl = config('app.url') . '/cancel?token=' . $token;
        $this->bookingDate = $booking->start_time;
        $this->products = $booking->products->toArray();
        $this->totalTime = $this->sumTime($booking);
        $this->totalPrice = $booking->products()->sum('price');
        $this->co_address = config('admin.address.co');
        $this->address_line = config('admin.address.line');
        $this->city = config('admin.address.city');
        $this->zip = config('admin.address.zip');
        $this->name = $name;
    }

    public function build(): BookingCreated
    {
        return $this->markdown('emails.bookings.created')
            ->subject('Impulse Nails - Appointment confirmation.');
    }

    private function sumTime($booking)
    {
        return $booking->products()->map(fn ($product) => (int)$product->minutes)->sum();
    }
}
