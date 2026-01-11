<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airline>
 */
class AirlineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $airlines = [
            ['name' => 'American Airlines', 'code' => 'AAL'],
            ['name' => 'Delta Air Lines', 'code' => 'DAL'],
            ['name' => 'United Airlines', 'code' => 'UAL'],
            ['name' => 'Southwest Airlines', 'code' => 'SWA'],
            ['name' => 'JetBlue Airways', 'code' => 'JBU'],
            ['name' => 'Alaska Airlines', 'code' => 'ASA'],
            ['name' => 'Spirit Airlines', 'code' => 'NKS'],
            ['name' => 'Frontier Airlines', 'code' => 'FFT'],
        ];

        $airline = fake()->randomElement($airlines);

        return [
            'name' => $airline['name'],
            'code' => $airline['code'].fake()->unique()->numberBetween(1, 999),
            'country' => fake()->country(),
            'active' => fake()->boolean(90),
        ];
    }
}
