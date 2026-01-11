<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imageable_type' => User::class,
            'imageable_id' => User::factory(),
            'url' => fake()->imageUrl(),
            'alt' => fake()->sentence(3),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
