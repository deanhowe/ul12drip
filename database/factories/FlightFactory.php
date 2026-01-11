<?php

namespace Database\Factories;

use App\Models\Airline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'airline_id' => Airline::factory(),
            'flight_number' => fake()->bothify('??###'),
            'origin' => fake()->city(),
            'destination' => fake()->city(),
            'departure_time' => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
