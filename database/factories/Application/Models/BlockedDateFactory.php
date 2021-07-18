<?php

namespace Database\Factories\Application\Models;

use App\Application\Models\BlockedDate;
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
            'times' => null,
        ];
    }
}
