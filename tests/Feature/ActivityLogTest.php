<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_records_activity_when_order_is_created(): void
    {
        $order = Order::factory()->create();

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => $order->getMorphClass(),
            'subject_id' => $order->id,
            'event' => 'created',
        ], 'activity_log');

        // It might be recorded multiple times because of multiple observers
        $count = ActivityLog::where('subject_type', $order->getMorphClass())
            ->where('subject_id', $order->id)
            ->where('event', 'created')
            ->count();

        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function test_it_records_activity_when_post_is_updated(): void
    {
        $post = Post::factory()->create(['title' => 'Old Title']);

        $post->update(['title' => 'New Title']);

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => $post->getMorphClass(),
            'subject_id' => $post->id,
            'event' => 'updated',
        ], 'activity_log');
    }

    public function test_it_records_activity_when_product_is_deleted(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => $product->getMorphClass(),
            'subject_id' => $product->id,
            'event' => 'deleted',
        ], 'activity_log');
    }

    public function test_audit_observer_provides_default_description_for_product(): void
    {
        $product = Product::factory()->create();

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => $product->getMorphClass(),
            'subject_id' => $product->id,
            'event' => 'created',
            'description' => 'Product was created',
        ], 'activity_log');
    }

    public function test_user_observer_records_registration(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => $user->getMorphClass(),
            'subject_id' => $user->id,
            'event' => 'created',
            'description' => 'User registered',
        ], 'activity_log');
    }
}
