<?php

namespace Tests\Feature;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that OrderPlaced event is dispatched.
     */
    public function test_order_placed_event_is_dispatched(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        OrderPlaced::dispatch($order);

        Event::assertDispatched(OrderPlaced::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });
    }

    /**
     * Test that listeners are attached to OrderPlaced event.
     */
    public function test_order_placed_listeners_are_registered(): void
    {
        $this->assertTrue(Event::hasListeners(OrderPlaced::class));

        $listeners = Event::getListeners(OrderPlaced::class);

        // The listeners are wrapped in a closure by Laravel when using string-based registration in Event::listen
        // But we can check if they are indeed registered by checking for the listener classes

        $registeredListeners = [];
        foreach ($listeners as $listener) {
            // This is a bit tricky with Laravel's internal representation,
            // but we can check if the listeners are active.
        }

        // Simpler way: check if they are attached
        $this->assertCount(2, $listeners);
    }
}
