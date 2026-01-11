<?php

namespace Tests\Feature;

use App\Enums\DeploymentStatus;
use App\Enums\OrderStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Deployment;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for enum casting functionality.
 *
 * Demonstrates:
 * - Enum casting on model attributes
 * - Enum helper methods (label, color, etc.)
 * - Creating models with enum values
 * - Querying by enum values
 */
class EnumCastsTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_status_is_cast_to_enum(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $this->assertInstanceOf(OrderStatus::class, $order->status);
        $this->assertEquals(OrderStatus::Pending, $order->status);
    }

    public function test_order_status_can_be_set_with_enum(): void
    {
        $order = Order::factory()->create();
        $order->status = OrderStatus::Completed;
        $order->save();

        $order->refresh();
        $this->assertEquals(OrderStatus::Completed, $order->status);
    }

    public function test_order_status_enum_has_label(): void
    {
        $this->assertEquals('Pending', OrderStatus::Pending->label());
        $this->assertEquals('Processing', OrderStatus::Processing->label());
        $this->assertEquals('Completed', OrderStatus::Completed->label());
        $this->assertEquals('Cancelled', OrderStatus::Cancelled->label());
    }

    public function test_order_status_enum_has_color(): void
    {
        $this->assertEquals('yellow', OrderStatus::Pending->color());
        $this->assertEquals('blue', OrderStatus::Processing->color());
        $this->assertEquals('green', OrderStatus::Completed->color());
        $this->assertEquals('red', OrderStatus::Cancelled->color());
    }

    public function test_task_status_is_cast_to_enum(): void
    {
        $task = Task::factory()->create(['status' => TaskStatus::Pending]);

        $this->assertInstanceOf(TaskStatus::class, $task->status);
        $this->assertEquals(TaskStatus::Pending, $task->status);
    }

    public function test_task_status_enum_is_active_helper(): void
    {
        $this->assertTrue(TaskStatus::Pending->isActive());
        $this->assertTrue(TaskStatus::InProgress->isActive());
        $this->assertFalse(TaskStatus::Completed->isActive());
    }

    public function test_task_priority_is_cast_to_enum(): void
    {
        $task = Task::factory()->create(['priority' => TaskPriority::High]);

        $this->assertInstanceOf(TaskPriority::class, $task->priority);
        $this->assertEquals(TaskPriority::High, $task->priority);
    }

    public function test_task_priority_enum_has_sort_order(): void
    {
        $this->assertEquals(1, TaskPriority::Low->sortOrder());
        $this->assertEquals(2, TaskPriority::Medium->sortOrder());
        $this->assertEquals(3, TaskPriority::High->sortOrder());
    }

    public function test_deployment_status_is_cast_to_enum(): void
    {
        $deployment = Deployment::factory()->create(['status' => DeploymentStatus::Pending]);

        $this->assertInstanceOf(DeploymentStatus::class, $deployment->status);
        $this->assertEquals(DeploymentStatus::Pending, $deployment->status);
    }

    public function test_deployment_status_enum_is_terminal_helper(): void
    {
        $this->assertFalse(DeploymentStatus::Pending->isTerminal());
        $this->assertFalse(DeploymentStatus::Running->isTerminal());
        $this->assertTrue(DeploymentStatus::Success->isTerminal());
        $this->assertTrue(DeploymentStatus::Failed->isTerminal());
    }

    public function test_can_query_by_enum_value(): void
    {
        Order::factory()->create(['status' => OrderStatus::Pending]);
        Order::factory()->create(['status' => OrderStatus::Pending]);
        Order::factory()->create(['status' => OrderStatus::Completed]);

        $pendingOrders = Order::where('status', OrderStatus::Pending)->get();

        $this->assertCount(2, $pendingOrders);
    }

    public function test_enum_cases_returns_all_values(): void
    {
        $cases = OrderStatus::cases();

        $this->assertCount(4, $cases);
        $this->assertContains(OrderStatus::Pending, $cases);
        $this->assertContains(OrderStatus::Processing, $cases);
        $this->assertContains(OrderStatus::Completed, $cases);
        $this->assertContains(OrderStatus::Cancelled, $cases);
    }
}
