<?php

namespace App\V1\Application\Mail;

use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use App\V1\Domain\Decoder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingApproval extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Booking $booking;
    public string $cancelUrl;
    public string $approveUrl;
    public $totalTime;
    public $totalPrice;

    public function __construct(User $user, Booking $booking)
    {
        $this->user = $user;
        $this->booking = $booking;
        $token = Decoder::encode(['booking_id' => $booking->id]);
        $this->cancelUrl = config('app.url') . '/cancel?token=' . $token;
        $this->approveUrl = config('app.url') . "/bookings/$booking->id/approve";
        $this->totalTime = $this->sumTime($booking);
        $this->totalPrice = $this->sumPrice($booking);
    }

    public function build(): BookingApproval
    {
        return $this->markdown('emails.bookings.approval');
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
            ->map(function ($product) {
                return (int)$product->price * (int)$product->pivot->quantity;
            })->sum();
    }
}
