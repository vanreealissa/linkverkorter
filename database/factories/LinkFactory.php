<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('??##??'),
            'url' => fake()->url(),
        ];
    }
}
