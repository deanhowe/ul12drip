<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;

/**
 * Stripe Event Listener.
 *
 * Listens to Laravel Cashier's webhook events for additional processing.
 * This is an alternative to extending the WebhookController - useful when
 * you want to handle events without modifying the controller.
 *
 * Demonstrates:
 * - Listening to Cashier's WebhookReceived event
 * - Listening to Cashier's WebhookHandled event
 * - Event-driven architecture for webhook processing
 * - Decoupled event handling
 *
 * Register this listener in EventServiceProvider or use Event::listen()
 * in a service provider's boot method.
 *
 * Example registration in AppServiceProvider:
 * ```php
 * use App\Listeners\StripeEventListener;
 * use Laravel\Cashier\Events\WebhookReceived;
 * use Laravel\Cashier\Events\WebhookHandled;
 *
 * Event::listen(WebhookReceived::class, [StripeEventListener::class, 'handleWebhookReceived']);
 * Event::listen(WebhookHandled::class, [StripeEventListener::class, 'handleWebhookHandled']);
 * ```
 *
 * Tinker Examples:
 * ----------------
 * // Dispatch a test event:
 * event(new \Laravel\Cashier\Events\WebhookReceived(['type' => 'test.event']));
 */
class StripeEventListener
{
    /**
     * Handle the WebhookReceived event.
     *
     * This is called BEFORE Cashier processes the webhook.
     * Use this for logging, validation, or pre-processing.
     */
    public function handleWebhookReceived(WebhookReceived $event): void
    {
        $payload = $event->payload;
        $eventType = $payload['type'] ?? 'unknown';

        Log::debug('Stripe Webhook Received (Listener)', [
            'type' => $eventType,
            'id' => $payload['id'] ?? null,
        ]);

        // Handle specific events before Cashier processes them
        match ($eventType) {
            'checkout.session.completed' => $this->onCheckoutSessionCompleted($payload),
            'customer.created' => $this->onCustomerCreated($payload),
            'payment_intent.succeeded' => $this->onPaymentIntentSucceeded($payload),
            'payment_intent.payment_failed' => $this->onPaymentIntentFailed($payload),
            default => null,
        };
    }

    /**
     * Handle the WebhookHandled event.
     *
     * This is called AFTER Cashier has processed the webhook.
     * Use this for post-processing, notifications, or cleanup.
     */
    public function handleWebhookHandled(WebhookHandled $event): void
    {
        $payload = $event->payload;
        $eventType = $payload['type'] ?? 'unknown';

        Log::debug('Stripe Webhook Handled (Listener)', [
            'type' => $eventType,
            'id' => $payload['id'] ?? null,
        ]);

        // Post-processing after Cashier has handled the event
        match ($eventType) {
            'customer.subscription.created' => $this->afterSubscriptionCreated($payload),
            'customer.subscription.deleted' => $this->afterSubscriptionDeleted($payload),
            'invoice.payment_succeeded' => $this->afterPaymentSucceeded($payload),
            'invoice.payment_failed' => $this->afterPaymentFailed($payload),
            default => null,
        };
    }

    /**
     * Handle checkout session completed.
     *
     * Called when a Stripe Checkout session is completed.
     */
    protected function onCheckoutSessionCompleted(array $payload): void
    {
        $session = $payload['data']['object'] ?? [];

        Log::info('Checkout session completed', [
            'session_id' => $session['id'] ?? null,
            'customer' => $session['customer'] ?? null,
            'mode' => $session['mode'] ?? null, // 'payment', 'subscription', or 'setup'
            'payment_status' => $session['payment_status'] ?? null,
        ]);

        // Custom logic: Update order status, send confirmation email, etc.
    }

    /**
     * Handle customer created.
     *
     * Called when a new customer is created in Stripe.
     */
    protected function onCustomerCreated(array $payload): void
    {
        $customer = $payload['data']['object'] ?? [];

        Log::info('Stripe customer created', [
            'customer_id' => $customer['id'] ?? null,
            'email' => $customer['email'] ?? null,
        ]);

        // Custom logic: Sync customer data, send welcome email, etc.
    }

    /**
     * Handle payment intent succeeded.
     *
     * Called when a one-time payment succeeds.
     */
    protected function onPaymentIntentSucceeded(array $payload): void
    {
        $paymentIntent = $payload['data']['object'] ?? [];

        Log::info('Payment intent succeeded', [
            'payment_intent_id' => $paymentIntent['id'] ?? null,
            'amount' => $paymentIntent['amount'] ?? null,
            'currency' => $paymentIntent['currency'] ?? null,
            'customer' => $paymentIntent['customer'] ?? null,
        ]);

        // Custom logic: Fulfill order, send receipt, etc.
    }

    /**
     * Handle payment intent failed.
     *
     * Called when a payment attempt fails.
     */
    protected function onPaymentIntentFailed(array $payload): void
    {
        $paymentIntent = $payload['data']['object'] ?? [];
        $error = $paymentIntent['last_payment_error'] ?? [];

        Log::warning('Payment intent failed', [
            'payment_intent_id' => $paymentIntent['id'] ?? null,
            'error_code' => $error['code'] ?? null,
            'error_message' => $error['message'] ?? null,
            'customer' => $paymentIntent['customer'] ?? null,
        ]);

        // Custom logic: Notify user, retry payment, etc.
    }

    /**
     * After subscription created (post-Cashier processing).
     *
     * Called after Cashier has saved the subscription to the database.
     */
    protected function afterSubscriptionCreated(array $payload): void
    {
        $subscription = $payload['data']['object'] ?? [];

        Log::info('Subscription created - post processing', [
            'subscription_id' => $subscription['id'] ?? null,
            'customer' => $subscription['customer'] ?? null,
            'status' => $subscription['status'] ?? null,
        ]);

        // Custom logic: Send welcome email, provision resources, etc.
        // The subscription is now in the database, so you can query it:
        // $dbSubscription = \Laravel\Cashier\Subscription::where('stripe_id', $subscription['id'])->first();
    }

    /**
     * After subscription deleted (post-Cashier processing).
     *
     * Called after Cashier has marked the subscription as cancelled.
     */
    protected function afterSubscriptionDeleted(array $payload): void
    {
        $subscription = $payload['data']['object'] ?? [];

        Log::info('Subscription deleted - post processing', [
            'subscription_id' => $subscription['id'] ?? null,
            'customer' => $subscription['customer'] ?? null,
        ]);

        // Custom logic: Revoke access, send farewell email, cleanup resources, etc.
    }

    /**
     * After payment succeeded (post-Cashier processing).
     *
     * Called after Cashier has processed a successful payment.
     */
    protected function afterPaymentSucceeded(array $payload): void
    {
        $invoice = $payload['data']['object'] ?? [];

        Log::info('Payment succeeded - post processing', [
            'invoice_id' => $invoice['id'] ?? null,
            'customer' => $invoice['customer'] ?? null,
            'amount_paid' => $invoice['amount_paid'] ?? null,
        ]);

        // Custom logic: Send receipt, update analytics, etc.
    }

    /**
     * After payment failed (post-Cashier processing).
     *
     * Called after Cashier has processed a failed payment.
     */
    protected function afterPaymentFailed(array $payload): void
    {
        $invoice = $payload['data']['object'] ?? [];

        Log::warning('Payment failed - post processing', [
            'invoice_id' => $invoice['id'] ?? null,
            'customer' => $invoice['customer'] ?? null,
            'attempt_count' => $invoice['attempt_count'] ?? null,
        ]);

        // Custom logic: Send dunning email, notify support, etc.
    }
}
