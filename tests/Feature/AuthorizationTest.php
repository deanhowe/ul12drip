<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the access-admin gate.
     */
    public function test_access_admin_gate(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue(Gate::forUser($admin)->allows('access-admin'));
        $this->assertFalse(Gate::forUser($user)->allows('access-admin'));
    }

    /**
     * Test PostPolicy update method.
     */
    public function test_post_policy_update(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('update', $post));
        $this->assertFalse($otherUser->can('update', $post));
        $this->assertTrue($admin->can('update', $post));
    }

    /**
     * Test PostPolicy delete method.
     */
    public function test_post_policy_delete(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('delete', $post));
        $this->assertFalse($otherUser->can('delete', $post));
        $this->assertTrue($admin->can('delete', $post));
    }
}
