<?php

namespace Database\Factories;

use App\Models\Environment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deployment>
 */
class DeploymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'running', 'success', 'failed']);

        return [
            'environment_id' => Environment::factory(),
            'commit_hash' => fake()->sha1(),
            'status' => $status,
            'deployed_at' => in_array($status, ['success', 'failed']) ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
