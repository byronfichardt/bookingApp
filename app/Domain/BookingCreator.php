<?php

namespace App\Domain;

use App\Application\Models\Booking;
use App\Application\Models\User;
use Carbon\Carbon;

class BookingCreator
{
    public function create(User $user, string $dateTime, int $totalTime, ?string $note, array $products)
    {
        $booking = Booking::create([
            'start_time' => Carbon::parse($dateTime),
            'end_time' => Carbon::parse($dateTime)->addMinutes($totalTime),
            'user_id' => $user->id,
            'note' => $note,
            'status' => 'pending'
        ]);

        foreach ($products as $product) {
            $booking->products()->attach($product['id'], ['quantity' => (int) data_get($product, 'quantity', 1)]);
        }
    }
}
