<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ActivityLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $events = ['created', 'updated', 'deleted', 'restored', 'viewed', 'exported'];
        $event = fake()->randomElement($events);

        return [
            'user_id' => User::factory(),
            'user_type' => User::class,
            'subject_type' => Post::class,
            'subject_id' => Post::factory(),
            'event' => $event,
            'description' => fake()->sentence(),
            'properties' => ['key' => fake()->word()],
            'old_values' => $event === 'updated' ? ['title' => fake()->sentence()] : null,
            'new_values' => $event === 'updated' ? ['title' => fake()->sentence()] : null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'url' => fake()->url(),
            'batch_uuid' => fake()->boolean(20) ? Str::uuid()->toString() : null,
        ];
    }

    /**
     * Indicate that the activity is a creation event.
     */
    public function created(): static
    {
        return $this->state(fn (array $attributes) => [
            'event' => 'created',
            'description' => 'Record was created',
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    /**
     * Indicate that the activity is an update event.
     */
    public function updated(): static
    {
        return $this->state(fn (array $attributes) => [
            'event' => 'updated',
            'description' => 'Record was updated',
            'old_values' => ['title' => fake()->sentence()],
            'new_values' => ['title' => fake()->sentence()],
        ]);
    }

    /**
     * Indicate that the activity is a deletion event.
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'event' => 'deleted',
            'description' => 'Record was deleted',
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    /**
     * Indicate that the activity has no user (system action).
     */
    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'user_type' => null,
            'description' => 'System action',
        ]);
    }

    /**
     * Indicate that the activity is part of a batch.
     */
    public function inBatch(?string $batchUuid = null): static
    {
        return $this->state(fn (array $attributes) => [
            'batch_uuid' => $batchUuid ?? Str::uuid()->toString(),
        ]);
    }
}
