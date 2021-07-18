<?php

namespace Database\Factories\V1\Application\Models;

use App\V1\Application\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'note' => $this->faker->name,
            'start_time' => now(),
            'end_time' => now(),
            'status' => 'active',
            'user_id' => $this->faker->randomDigitNotNull,
        ];
    }
}
