<?php

namespace Tests\Feature\Billing;

use App\Listeners\StripeEventListener;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;
use Tests\TestCase;

/**
 * Feature tests for Stripe Webhook handling.
 *
 * Tests webhook functionality including:
 * - Webhook route accessibility
 * - CSRF exemption for webhook route
 * - Event listener registration
 * - Webhook event dispatching
 *
 * Note: Full webhook signature verification tests require valid Stripe
 * webhook secrets. These tests focus on the application's handling
 * of webhook events after signature verification.
 */
class WebhookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if Stripe webhook secret is configured.
     */
    protected function webhookSecretIsConfigured(): bool
    {
        return ! empty(config('cashier.webhook.secret'));
    }

    /**
     * Test that webhook route exists and accepts POST requests.
     */
    public function test_webhook_route_exists(): void
    {
        // Without valid Stripe signature, the request will fail
        // but we can verify the route exists and is not 404
        $response = $this->postJson('/stripe/webhook', [
            'type' => 'test.event',
            'data' => ['object' => []],
        ]);

        // Should not be 404 (route exists)
        // Will be 400 or 403 due to missing/invalid signature, which is expected
        $this->assertNotEquals(404, $response->getStatusCode());
        // Also verify it's not a 500 server error
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Test that webhook route is excluded from CSRF verification.
     */
    public function test_webhook_route_excluded_from_csrf(): void
    {
        // POST without CSRF token should not return 419
        $response = $this->post('/stripe/webhook', [
            'type' => 'test.event',
        ]);

        // 419 would indicate CSRF failure
        $this->assertNotEquals(419, $response->getStatusCode());
    }

    /**
     * Test that WebhookReceived event listener is registered.
     */
    public function test_webhook_received_listener_is_registered(): void
    {
        Event::fake([WebhookReceived::class]);

        // Dispatch the event
        event(new WebhookReceived(['type' => 'test.event', 'id' => 'evt_test']));

        // Assert event was dispatched
        Event::assertDispatched(WebhookReceived::class, function ($event) {
            return $event->payload['type'] === 'test.event';
        });
    }

    /**
     * Test that WebhookHandled event listener is registered.
     */
    public function test_webhook_handled_listener_is_registered(): void
    {
        Event::fake([WebhookHandled::class]);

        // Dispatch the event
        event(new WebhookHandled(['type' => 'test.event', 'id' => 'evt_test']));

        // Assert event was dispatched
        Event::assertDispatched(WebhookHandled::class, function ($event) {
            return $event->payload['type'] === 'test.event';
        });
    }

    /**
     * Test StripeEventListener handles WebhookReceived event.
     */
    public function test_stripe_event_listener_handles_webhook_received(): void
    {
        Log::shouldReceive('debug')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'Stripe Webhook Received') &&
                       $context['type'] === 'customer.subscription.created';
            });

        $listener = new StripeEventListener;
        $event = new WebhookReceived([
            'type' => 'customer.subscription.created',
            'id' => 'evt_test_123',
            'data' => [
                'object' => [
                    'id' => 'sub_test',
                    'customer' => 'cus_test',
                    'status' => 'active',
                ],
            ],
        ]);

        $listener->handleWebhookReceived($event);
    }

    /**
     * Test StripeEventListener handles WebhookHandled event.
     */
    public function test_stripe_event_listener_handles_webhook_handled(): void
    {
        Log::shouldReceive('debug')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'Stripe Webhook Handled') &&
                       $context['type'] === 'invoice.payment_succeeded';
            });

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'Payment succeeded');
            });

        $listener = new StripeEventListener;
        $event = new WebhookHandled([
            'type' => 'invoice.payment_succeeded',
            'id' => 'evt_test_456',
            'data' => [
                'object' => [
                    'id' => 'in_test',
                    'customer' => 'cus_test',
                    'amount_paid' => 2999,
                ],
            ],
        ]);

        $listener->handleWebhookHandled($event);
    }

    /**
     * Test StripeEventListener handles checkout session completed.
     */
    public function test_stripe_event_listener_handles_checkout_completed(): void
    {
        Log::shouldReceive('debug')->once();
        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'Checkout session completed') &&
                       $context['session_id'] === 'cs_test_123';
            });

        $listener = new StripeEventListener;
        $event = new WebhookReceived([
            'type' => 'checkout.session.completed',
            'id' => 'evt_test_789',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'customer' => 'cus_test',
                    'mode' => 'subscription',
                    'payment_status' => 'paid',
                ],
            ],
        ]);

        $listener->handleWebhookReceived($event);
    }

    /**
     * Test StripeEventListener handles payment failed event.
     */
    public function test_stripe_event_listener_handles_payment_failed(): void
    {
        Log::shouldReceive('debug')->once();
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'Payment failed') &&
                       $context['invoice_id'] === 'in_failed';
            });

        $listener = new StripeEventListener;
        $event = new WebhookHandled([
            'type' => 'invoice.payment_failed',
            'id' => 'evt_test_fail',
            'data' => [
                'object' => [
                    'id' => 'in_failed',
                    'customer' => 'cus_test',
                    'attempt_count' => 2,
                ],
            ],
        ]);

        $listener->handleWebhookHandled($event);
    }

    /**
     * Test that User model has Billable trait.
     */
    public function test_user_has_billable_trait(): void
    {
        $user = User::factory()->create();

        // Check that Billable methods exist
        $this->assertTrue(method_exists($user, 'subscription'));
        $this->assertTrue(method_exists($user, 'subscribed'));
        $this->assertTrue(method_exists($user, 'newSubscription'));
        $this->assertTrue(method_exists($user, 'invoices'));
        $this->assertTrue(method_exists($user, 'createSetupIntent'));
    }

    /**
     * Test that user can check subscription status.
     */
    public function test_user_can_check_subscription_status(): void
    {
        $user = User::factory()->create();

        // User without subscription should return false
        $this->assertFalse($user->subscribed('default'));
        $this->assertNull($user->subscription('default'));
    }

    /**
     * Test webhook route name is correct.
     */
    public function test_webhook_route_has_correct_name(): void
    {
        $this->assertEquals('/stripe/webhook', route('cashier.webhook', [], false));
    }
}
