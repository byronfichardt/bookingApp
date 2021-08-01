<?php

namespace Database\Factories\V1\Application\Models;

use App\V1\Application\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'refresh_token' => $this->faker->uuid,
                'type' => 'admin'
            ];
        });
    }

    public function adminNotAuthorized()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'admin'
            ];
        });
    }
}
