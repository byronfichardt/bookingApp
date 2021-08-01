<?php

namespace Database\Factories\V1\Application\Models;

use App\V1\Application\Models\BlockedDate;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockedDateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlockedDate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'blocked_date' => now()->format('Y-m-d'),
            'times' => $this->faker->randomElement(['9,16','13,16','9,13,16']),
        ];
    }
}
