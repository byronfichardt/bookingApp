<?php

namespace App\V1\Application\Mail;

use App\V1\Application\Models\Booking;
use App\V1\Application\Models\Product;
use App\V1\Domain\dtos\ProductDto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class BookingReminder extends Mailable
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
     * @var int|mixed
     */
    public $totalTime;
    /**
     * @var int|mixed
     */
    public $totalPrice;
    /**
     * @var mixed
     */
    public $bookingId;

    public Collection $products;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $token, Booking $booking, string $name)
    {
        $this->cancelUrl = config('app.url') . '/cancel?token=' . $token;
        $this->bookingDate = $booking->start_time;
        $this->products = $booking->products->map(function (Product $product) use($booking) {
            return new ProductDto($product, $product->getPrice($booking->start_time), $product->pivot->quantity);
        });
        $this->bookingId = $booking->id;
        $this->totalTime = $this->sumTime($booking);
        $this->totalPrice = $this->sumPrice($booking);
        $this->co_address = config('admin.address.co');
        $this->address_line = config('admin.address.line');
        $this->city = config('admin.address.city');
        $this->zip = config('admin.address.zip');
        $this->name = $name;
    }

    public function build(): BookingReminder
    {
        return $this->markdown('emails.bookings.reminder')
            ->subject('Impulse Nails - Appointment reminder.');
    }

    private function sumTime(Booking $booking)
    {
        return $booking->products
            ->map(function ($product) {
                return (int)$product->minutes * (int)$product->pivot->quantity;
            })->sum();
    }

    private function sumPrice(Booking $booking)
    {
        return $booking->products
            ->map(function ($product) use($booking) {
                $price = $product->getPrice($booking->start_time);
                return (int)($price ? $price->price : $product->price) * (int)$product->pivot->quantity;
            })->sum();
    }
}
