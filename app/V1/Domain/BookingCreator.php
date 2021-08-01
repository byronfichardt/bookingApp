<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use Carbon\Carbon;

class BookingCreator
{
    public function create(int $user, string $dateTime, int $totalTime, ?string $note, array $products): void
    {
        $booking = Booking::create([
            'start_time' => Carbon::parse($dateTime),
            'end_time' => Carbon::parse($dateTime)->addMinutes($totalTime),
            'user_id' => $user,
            'note' => $note,
            'status' => 'pending'
        ]);

        foreach ($products as $product) {
            $booking->products()->attach($product['id'], ['quantity' => (int) data_get($product, 'quantity', 1)]);
        }
    }
}
