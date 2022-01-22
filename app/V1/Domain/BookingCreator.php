<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use Carbon\Carbon;

class BookingCreator
{
    public function create(
        int $userId,
        string $dateTime,
        int $totalTime,
        string $note,
        array $products,
        ?string $path,
        string $status = 'pending'
    ): Booking {
        $booking = Booking::create([
            'start_time' => Carbon::parse($dateTime),
            'end_time' => Carbon::parse($dateTime)->addMinutes($totalTime),
            'user_id' => $userId,
            'note' => $note,
            'status' => $status,
            'path' => $path
        ]);

        foreach ($products as $product) {
            $booking->products()->attach($product['id'], ['quantity' => (int) data_get($product, 'quantity', 1)]);
        }

        return $booking;
    }
}
