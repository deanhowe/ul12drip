<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'url' => fake()->url(),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(60, 7200), // 1 min to 2 hours in seconds
        ];
    }
}
