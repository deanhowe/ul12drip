<?php

namespace Database\Factories;

use App\Models\Country;
use App\ValueObjects\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'name' => fake()->company(),
            'email' => fake()->companyEmail(),
            'office_address' => new Address(
                fake()->streetAddress(),
                fake()->secondaryAddress(),
                fake()->city(),
                fake()->state(),
                fake()->postcode(),
                fake()->country()
            ),
        ];
    }
}
