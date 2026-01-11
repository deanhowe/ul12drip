<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.fake()->unique()->numerify('######'),
            'total' => fake()->randomFloat(2, 10, 1000),
            'status' => OrderStatus::Pending,
            'status_changed_at' => null,
            'shipped_at' => null,
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Pending,
            'shipped_at' => null,
        ]);
    }

    /**
     * Indicate that the order is processing.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Processing,
            'status_changed_at' => now(),
            'shipped_at' => null,
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Completed,
            'status_changed_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'shipped_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Cancelled,
            'status_changed_at' => now(),
            'shipped_at' => null,
        ]);
    }

    /**
     * Indicate that the order has a high total value.
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'total' => fake()->randomFloat(2, 1000, 5000),
        ]);
    }
}
