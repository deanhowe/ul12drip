<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Comment;
use App\Models\Flight;
use App\Models\Podcast;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_user_has_many_posts(): void
    {
        $user = User::factory()
            ->has(Post::factory()->count(3))
            ->create();

        $user->load('posts');
        $this->assertCount(3, $user->posts);
        $this->assertInstanceOf(Post::class, $user->posts->first());
    }

    public function test_post_can_be_created_with_factory(): void
    {
        $post = Post::factory()->create();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $post->title,
        ]);
    }

    public function test_post_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $post->load('user');
        $this->assertEquals($user->id, $post->user->id);
        $this->assertInstanceOf(User::class, $post->user);
    }

    public function test_post_has_many_comments(): void
    {
        $post = Post::factory()->create();

        // Create comments using the polymorphic relationship
        Comment::factory()->count(5)->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
        ]);

        $post->load('comments');
        $this->assertCount(5, $post->comments);
        $this->assertInstanceOf(Comment::class, $post->comments->first());
    }

    public function test_comment_can_be_created_with_factory(): void
    {
        $comment = Comment::factory()->create();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => $comment->body,
            'commentable_type' => Post::class,
        ]);
    }

    public function test_comment_belongs_to_commentable(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
        ]);

        $comment->load('commentable');
        $this->assertEquals($post->id, $comment->commentable->id);
        $this->assertInstanceOf(Post::class, $comment->commentable);
    }

    public function test_podcast_can_be_created_with_factory(): void
    {
        $podcast = Podcast::factory()->create();

        $this->assertDatabaseHas('podcasts', [
            'id' => $podcast->id,
            'title' => $podcast->title,
        ]);
    }

    public function test_car_can_be_created_with_factory(): void
    {
        $car = Car::factory()->create();

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'make' => $car->make,
            'model' => $car->model,
            'year' => $car->year,
        ]);
    }

    public function test_flight_can_be_created_with_factory(): void
    {
        $flight = Flight::factory()->create();

        $this->assertDatabaseHas('flights', [
            'id' => $flight->id,
            'flight_number' => $flight->flight_number,
            'origin' => $flight->origin,
            'destination' => $flight->destination,
        ]);
    }

    public function test_flight_departure_time_is_cast_to_datetime(): void
    {
        $flight = Flight::factory()->create();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $flight->departure_time);
    }

    public function test_complete_relationship_chain(): void
    {
        $user = User::factory()->create();

        // Create posts for the user
        $posts = Post::factory()->count(2)->create(['user_id' => $user->id]);

        // Create comments for each post using polymorphic relationship
        foreach ($posts as $post) {
            Comment::factory()->count(3)->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ]);
        }

        $user->load('posts.comments.commentable.user');
        $this->assertCount(2, $user->posts);
        $this->assertCount(3, $user->posts->first()->comments);
        $this->assertEquals($user->id, $user->posts->first()->comments->first()->commentable->user->id);
    }
}
