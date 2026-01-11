<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for soft delete functionality across models.
 *
 * Demonstrates:
 * - Soft deleting records
 * - Restoring soft deleted records
 * - Force deleting records
 * - Querying with/without trashed records
 */
class SoftDeletesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertNull(User::find($user->id));
        $this->assertNotNull(User::withTrashed()->find($user->id));
    }

    public function test_soft_deleted_user_can_be_restored(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $user->restore();

        $this->assertNotSoftDeleted('users', ['id' => $user->id]);
        $this->assertNotNull(User::find($user->id));
    }

    public function test_user_can_be_force_deleted(): void
    {
        $user = User::factory()->create();

        $user->forceDelete();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertNull(User::withTrashed()->find($user->id));
    }

    public function test_post_can_be_soft_deleted(): void
    {
        $post = Post::factory()->create();

        $post->delete();

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        $this->assertNull(Post::find($post->id));
        $this->assertNotNull(Post::withTrashed()->find($post->id));
    }

    public function test_soft_deleted_post_can_be_restored(): void
    {
        $post = Post::factory()->create();
        $post->delete();

        $post->restore();

        $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
        $this->assertNotNull(Post::find($post->id));
    }

    public function test_product_can_be_soft_deleted(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNull(Product::find($product->id));
        $this->assertNotNull(Product::withTrashed()->find($product->id));
    }

    public function test_order_can_be_soft_deleted(): void
    {
        $order = Order::factory()->create();

        $order->delete();

        $this->assertSoftDeleted('orders', ['id' => $order->id]);
        $this->assertNull(Order::find($order->id));
        $this->assertNotNull(Order::withTrashed()->find($order->id));
    }

    public function test_only_trashed_scope_returns_only_soft_deleted(): void
    {
        $activeUser = User::factory()->create();
        $deletedUser = User::factory()->create();
        $deletedUser->delete();

        $trashedUsers = User::onlyTrashed()->get();

        $this->assertCount(1, $trashedUsers);
        $this->assertEquals($deletedUser->id, $trashedUsers->first()->id);
    }

    public function test_with_trashed_scope_returns_all_records(): void
    {
        $activeUser = User::factory()->create();
        $deletedUser = User::factory()->create();
        $deletedUser->delete();

        $allUsers = User::withTrashed()->get();

        $this->assertCount(2, $allUsers);
    }
}
