<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExhaustiveFeature>
 */
class ExhaustiveFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'string_col' => $this->faker->sentence(),
            'integer_col' => $this->faker->randomNumber(),
            'boolean_col' => $this->faker->boolean(),
            'date_col' => $this->faker->date(),
            'decimal_col' => $this->faker->randomFloat(2, 0, 1000),
            'json_col' => ['key' => 'value'],
            'uuid_col' => $this->faker->uuid(),
            'ulid_col' => \Illuminate\Support\Str::ulid(),
        ];
    }
}
