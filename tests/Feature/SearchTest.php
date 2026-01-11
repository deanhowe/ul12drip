<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the search page loads.
     */
    public function test_search_page_loads(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('Laravel Scout Search Showcase');
    }

    /**
     * Test searching for a post.
     */
    public function test_can_search_for_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Unique Searchable Title',
            'body' => 'This is a unique body for searching.',
            'published_at' => now(),
        ]);

        // Create another post that shouldn't match
        Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Another random post',
            'published_at' => now(),
        ]);

        $response = $this->get('/search?q=Unique');

        $response->assertStatus(200);
        $response->assertSee('Unique Searchable Title');
        $response->assertSee('Search Results for');
    }
}
