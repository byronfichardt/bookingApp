<?php

namespace Database\Factories\V1\Application\Models;

use App\V1\Application\Models\Booking;
use App\V1\Application\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomDigitNotNull,
            'minutes' => $this->faker->numberBetween(100,200),
            'display_quantity' => true,
            'sort_order' => 0,
        ];
    }
}
