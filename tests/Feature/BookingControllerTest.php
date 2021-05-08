<?php

namespace Tests\Feature;

use App\Application\Models\Booking;
use App\Application\Models\User;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    public function testItCanEdit()
    {
        $user = User::factory()->has(Booking::factory()->count(3))->create();

        $token = base64_encode(json_encode($user->toArray()));
        $response = $this->get('cancel?token=' . $token);
    }
}
