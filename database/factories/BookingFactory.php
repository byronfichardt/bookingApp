<?php

namespace Database\Factories;

use App\Application\Models\Booking;
use App\Application\Models\User;
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
            'name' => $this->faker->name,
            'start_time' => now(),
            'end_time' => now(),
            'status' => 'active',
            'user_id' => $this->faker->randomDigitNotNull,
        ];
    }
}
