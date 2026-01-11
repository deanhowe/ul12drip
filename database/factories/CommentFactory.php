<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commentable_type' => Post::class,
            'commentable_id' => Post::factory(),
            'user_id' => User::factory(),
            'body' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the comment belongs to a post.
     */
    public function forPost(?Post $post = null): static
    {
        return $this->state(fn (array $attributes) => [
            'commentable_type' => Post::class,
            'commentable_id' => $post?->id ?? Post::factory(),
        ]);
    }

    /**
     * Indicate that the comment belongs to a video.
     */
    public function forVideo(?\App\Models\Video $video = null): static
    {
        return $this->state(fn (array $attributes) => [
            'commentable_type' => \App\Models\Video::class,
            'commentable_id' => $video?->id ?? \App\Models\Video::factory(),
        ]);
    }

    /**
     * Indicate that the comment belongs to a product.
     */
    public function forProduct(?\App\Models\Product $product = null): static
    {
        return $this->state(fn (array $attributes) => [
            'commentable_type' => \App\Models\Product::class,
            'commentable_id' => $product?->id ?? \App\Models\Product::factory(),
        ]);
    }
}
