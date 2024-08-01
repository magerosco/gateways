<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peripheral>
 */
class PeripheralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UID' => fake()->randomNumber(6),
            'vendor' => fake()->company(),
            'gateway_id' => 1,
        ];
    }
}
