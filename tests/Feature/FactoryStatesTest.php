<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for factory state functionality.
 *
 * Demonstrates:
 * - Factory states for different model configurations
 * - Combining multiple states
 * - States that set specific attribute values
 */
class FactoryStatesTest extends TestCase
{
    use RefreshDatabase;

    // User Factory States

    public function test_user_unverified_state(): void
    {
        $user = User::factory()->unverified()->create();

        $this->assertNull($user->email_verified_at);
    }

    public function test_user_verified_state(): void
    {
        $user = User::factory()->verified()->create();

        $this->assertNotNull($user->email_verified_at);
    }

    public function test_user_suspended_state(): void
    {
        $user = User::factory()->suspended()->create();

        $this->assertNotNull($user->suspended_at);
    }

    public function test_user_premium_state(): void
    {
        $user = User::factory()->premium()->create();

        $this->assertTrue($user->is_premium);
    }

    public function test_user_admin_state(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->is_admin);
    }

    public function test_user_premium_admin_state_combines_both(): void
    {
        $user = User::factory()->premiumAdmin()->create();

        $this->assertTrue($user->is_premium);
        $this->assertTrue($user->is_admin);
    }

    // Post Factory States

    public function test_post_published_state(): void
    {
        $post = Post::factory()->published()->create();

        $this->assertNotNull($post->published_at);
        $this->assertTrue($post->published_at->isPast());
    }

    public function test_post_draft_state(): void
    {
        $post = Post::factory()->draft()->create();

        $this->assertNull($post->published_at);
    }

    public function test_post_scheduled_state(): void
    {
        $post = Post::factory()->scheduled()->create();

        $this->assertNotNull($post->published_at);
        $this->assertTrue($post->published_at->isFuture());
    }

    public function test_post_published_today_state(): void
    {
        $post = Post::factory()->publishedToday()->create();

        $this->assertNotNull($post->published_at);
        $this->assertTrue($post->published_at->isToday());
    }

    // Product Factory States

    public function test_product_out_of_stock_state(): void
    {
        $product = Product::factory()->outOfStock()->create();

        $this->assertEquals(0, $product->stock);
    }

    public function test_product_inactive_state(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->assertFalse($product->active);
    }

    public function test_product_on_sale_state(): void
    {
        $product = Product::factory()->onSale()->create();

        $this->assertNotNull($product->sale_price);
        $this->assertLessThan($product->price, $product->sale_price);
    }

    public function test_product_big_discount_state(): void
    {
        $product = Product::factory()->bigDiscount()->create();

        $this->assertNotNull($product->sale_price);
        // bigDiscount sets sale_price to 50% of price (rounded to 2 decimals)
        $this->assertEquals(round($product->price * 0.5, 2), $product->sale_price);
    }

    public function test_product_low_stock_state(): void
    {
        $product = Product::factory()->lowStock()->create();

        $this->assertLessThanOrEqual(5, $product->stock);
        $this->assertGreaterThan(0, $product->stock);
    }

    // Order Factory States

    public function test_order_pending_state(): void
    {
        $order = Order::factory()->pending()->create();

        $this->assertEquals(OrderStatus::Pending, $order->status);
    }

    public function test_order_processing_state(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertEquals(OrderStatus::Processing, $order->status);
    }

    public function test_order_completed_state(): void
    {
        $order = Order::factory()->completed()->create();

        $this->assertEquals(OrderStatus::Completed, $order->status);
    }

    public function test_order_cancelled_state(): void
    {
        $order = Order::factory()->cancelled()->create();

        $this->assertEquals(OrderStatus::Cancelled, $order->status);
    }

    public function test_order_high_value_state(): void
    {
        $order = Order::factory()->highValue()->create();

        $this->assertGreaterThanOrEqual(1000, $order->total);
    }

    // Task Factory States

    public function test_task_pending_state(): void
    {
        $task = Task::factory()->pending()->create();

        $this->assertEquals(TaskStatus::Pending, $task->status);
    }

    public function test_task_in_progress_state(): void
    {
        $task = Task::factory()->inProgress()->create();

        $this->assertEquals(TaskStatus::InProgress, $task->status);
    }

    public function test_task_completed_state(): void
    {
        $task = Task::factory()->completed()->create();

        $this->assertEquals(TaskStatus::Completed, $task->status);
    }

    public function test_task_high_priority_state(): void
    {
        $task = Task::factory()->highPriority()->create();

        $this->assertEquals(TaskPriority::High, $task->priority);
    }

    public function test_task_low_priority_state(): void
    {
        $task = Task::factory()->lowPriority()->create();

        $this->assertEquals(TaskPriority::Low, $task->priority);
    }

    public function test_task_overdue_state(): void
    {
        $task = Task::factory()->overdue()->create();

        $this->assertTrue($task->due_date->isPast());
        $this->assertNotEquals(TaskStatus::Completed, $task->status);
    }

    public function test_task_due_soon_state(): void
    {
        $task = Task::factory()->dueSoon()->create();

        $this->assertTrue($task->due_date->isFuture());
        $this->assertTrue($task->due_date->diffInDays(now()) <= 3);
    }

    public function test_task_urgent_state_combines_high_priority_and_due_soon(): void
    {
        $task = Task::factory()->urgent()->create();

        $this->assertEquals(TaskPriority::High, $task->priority);
        $this->assertTrue($task->due_date->isFuture());
    }

    // Combining States

    public function test_combining_multiple_factory_states(): void
    {
        $product = Product::factory()->onSale()->lowStock()->create();

        $this->assertNotNull($product->sale_price);
        $this->assertLessThanOrEqual(5, $product->stock);
    }
}
