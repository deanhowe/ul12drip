<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for query scope functionality.
 *
 * Demonstrates:
 * - Local query scopes
 * - Scopes with parameters
 * - Chaining multiple scopes
 */
class QueryScopesTest extends TestCase
{
    use RefreshDatabase;

    // User Scopes

    public function test_user_active_scope(): void
    {
        User::factory()->create(['suspended_at' => null]);
        User::factory()->create(['suspended_at' => now()]);

        $activeUsers = User::active()->get();

        $this->assertCount(1, $activeUsers);
        $this->assertNull($activeUsers->first()->suspended_at);
    }

    public function test_user_suspended_scope(): void
    {
        User::factory()->create(['suspended_at' => null]);
        User::factory()->create(['suspended_at' => now()]);

        $suspendedUsers = User::suspended()->get();

        $this->assertCount(1, $suspendedUsers);
        $this->assertNotNull($suspendedUsers->first()->suspended_at);
    }

    public function test_user_premium_scope(): void
    {
        User::factory()->create(['is_premium' => true]);
        User::factory()->create(['is_premium' => false]);

        $premiumUsers = User::premium()->get();

        $this->assertCount(1, $premiumUsers);
        $this->assertTrue($premiumUsers->first()->is_premium);
    }

    public function test_user_admins_scope(): void
    {
        User::factory()->create(['is_admin' => true]);
        User::factory()->create(['is_admin' => false]);

        $admins = User::admins()->get();

        $this->assertCount(1, $admins);
        $this->assertTrue($admins->first()->is_admin);
    }

    // Post Scopes

    public function test_post_published_scope(): void
    {
        Post::factory()->create(['published_at' => now()->subDay()]);
        Post::factory()->create(['published_at' => null]);
        Post::factory()->create(['published_at' => now()->addDay()]);

        $publishedPosts = Post::published()->get();

        $this->assertCount(1, $publishedPosts);
    }

    public function test_post_draft_scope(): void
    {
        Post::factory()->create(['published_at' => now()->subDay()]);
        Post::factory()->create(['published_at' => null]);

        $draftPosts = Post::withoutGlobalScopes()->draft()->get();

        $this->assertCount(1, $draftPosts);
        $this->assertNull($draftPosts->first()->published_at);
    }

    public function test_post_scheduled_scope(): void
    {
        Post::factory()->create(['published_at' => now()->subDay()]);
        Post::factory()->create(['published_at' => null]);
        Post::factory()->create(['published_at' => now()->addDay()]);

        $scheduledPosts = Post::withoutGlobalScopes()->scheduled()->get();

        $this->assertCount(1, $scheduledPosts);
    }

    public function test_post_recent_scope_with_default_days(): void
    {
        Post::factory()->create(['created_at' => now()->subDays(3)]);
        Post::factory()->create(['created_at' => now()->subDays(10)]);

        $recentPosts = Post::recent()->get();

        $this->assertCount(1, $recentPosts);
    }

    public function test_post_recent_scope_with_custom_days(): void
    {
        Post::factory()->create(['created_at' => now()->subDays(3)]);
        Post::factory()->create(['created_at' => now()->subDays(10)]);
        Post::factory()->create(['created_at' => now()->subDays(20)]);

        $recentPosts = Post::recent(15)->get();

        $this->assertCount(2, $recentPosts);
    }

    // Product Scopes

    public function test_product_active_scope(): void
    {
        Product::factory()->create(['active' => true]);
        Product::factory()->create(['active' => false]);

        $activeProducts = Product::active()->get();

        $this->assertCount(1, $activeProducts);
        $this->assertTrue($activeProducts->first()->active);
    }

    public function test_product_in_stock_scope(): void
    {
        Product::factory()->create(['stock' => 10]);
        Product::factory()->create(['stock' => 0]);

        $inStockProducts = Product::inStock()->get();

        $this->assertCount(1, $inStockProducts);
        $this->assertGreaterThan(0, $inStockProducts->first()->stock);
    }

    public function test_product_out_of_stock_scope(): void
    {
        Product::factory()->create(['stock' => 10]);
        Product::factory()->create(['stock' => 0]);

        $outOfStockProducts = Product::outOfStock()->get();

        $this->assertCount(1, $outOfStockProducts);
        $this->assertEquals(0, $outOfStockProducts->first()->stock);
    }

    public function test_product_on_sale_scope(): void
    {
        Product::factory()->create(['sale_price' => 9.99, 'price' => 19.99]);
        Product::factory()->create(['sale_price' => null, 'price' => 19.99]);

        $onSaleProducts = Product::onSale()->get();

        $this->assertCount(1, $onSaleProducts);
        $this->assertNotNull($onSaleProducts->first()->sale_price);
    }

    // Order Scopes

    public function test_order_pending_scope(): void
    {
        Order::factory()->create(['status' => OrderStatus::Pending]);
        Order::factory()->create(['status' => OrderStatus::Completed]);

        $pendingOrders = Order::pending()->get();

        $this->assertCount(1, $pendingOrders);
        $this->assertEquals(OrderStatus::Pending, $pendingOrders->first()->status);
    }

    public function test_order_completed_scope(): void
    {
        Order::factory()->create(['status' => OrderStatus::Pending]);
        Order::factory()->create(['status' => OrderStatus::Completed]);

        $completedOrders = Order::completed()->get();

        $this->assertCount(1, $completedOrders);
        $this->assertEquals(OrderStatus::Completed, $completedOrders->first()->status);
    }

    // Chaining Scopes

    public function test_chaining_multiple_scopes(): void
    {
        User::factory()->create(['is_premium' => true, 'is_admin' => true, 'suspended_at' => null]);
        User::factory()->create(['is_premium' => true, 'is_admin' => false, 'suspended_at' => null]);
        User::factory()->create(['is_premium' => false, 'is_admin' => true, 'suspended_at' => null]);

        $premiumAdmins = User::premium()->admins()->active()->get();

        $this->assertCount(1, $premiumAdmins);
        $this->assertTrue($premiumAdmins->first()->is_premium);
        $this->assertTrue($premiumAdmins->first()->is_admin);
    }
}
