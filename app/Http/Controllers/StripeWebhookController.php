<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom Stripe Webhook Controller.
 *
 * Extends Laravel Cashier's WebhookController to handle additional
 * Stripe webhook events for subscription lifecycle management.
 *
 * Demonstrates:
 * - Extending Cashier's webhook handling
 * - Custom event handlers for Stripe webhooks
 * - Logging webhook events for debugging
 * - Handling subscription lifecycle events
 *
 * Webhook URL: POST /stripe/webhook
 *
 * Required Stripe Webhooks (configure in Stripe Dashboard):
 * - customer.subscription.created
 * - customer.subscription.updated
 * - customer.subscription.deleted
 * - customer.updated
 * - customer.deleted
 * - payment_method.automatically_updated
 * - invoice.payment_action_required
 * - invoice.payment_succeeded
 * - invoice.payment_failed
 *
 * Environment Variables Required:
 * - STRIPE_KEY: Your Stripe publishable key
 * - STRIPE_SECRET: Your Stripe secret key
 * - STRIPE_WEBHOOK_SECRET: Webhook signing secret from Stripe Dashboard
 *
 * To create webhook in Stripe via CLI:
 * php artisan cashier:webhook
 *
 * Tinker Examples:
 * ----------------
 * // Manually trigger webhook handling (for testing):
 * // Use Stripe CLI: stripe trigger invoice.payment_succeeded
 */
class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle customer subscription created.
     *
     * Called when a new subscription is created in Stripe.
     * Use this to send welcome emails, provision resources, etc.
     */
    protected function handleCustomerSubscriptionCreated(array $payload): Response
    {
        // Let Cashier handle the database updates first
        $response = parent::handleCustomerSubscriptionCreated($payload);

        // Log the event for debugging/auditing
        Log::info('Stripe Webhook: Subscription created', [
            'customer_id' => $payload['data']['object']['customer'] ?? null,
            'subscription_id' => $payload['data']['object']['id'] ?? null,
            'status' => $payload['data']['object']['status'] ?? null,
        ]);

        // Custom logic: Send welcome email, provision resources, etc.
        // Example:
        // $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        // if ($user) {
        //     $user->notify(new SubscriptionCreatedNotification());
        // }

        return $response;
    }

    /**
     * Handle customer subscription updated.
     *
     * Called when a subscription is modified (plan change, quantity change, etc.).
     * Use this to handle plan upgrades/downgrades, update feature access, etc.
     */
    protected function handleCustomerSubscriptionUpdated(array $payload): Response
    {
        // Let Cashier handle the database updates first
        $response = parent::handleCustomerSubscriptionUpdated($payload);

        $subscription = $payload['data']['object'];
        $previousAttributes = $payload['data']['previous_attributes'] ?? [];

        Log::info('Stripe Webhook: Subscription updated', [
            'customer_id' => $subscription['customer'] ?? null,
            'subscription_id' => $subscription['id'] ?? null,
            'status' => $subscription['status'] ?? null,
            'previous_attributes' => array_keys($previousAttributes),
        ]);

        // Detect plan changes
        if (isset($previousAttributes['items'])) {
            Log::info('Stripe Webhook: Subscription plan changed', [
                'subscription_id' => $subscription['id'] ?? null,
            ]);
            // Custom logic: Update feature access, send notification, etc.
        }

        // Detect cancellation scheduled
        if (isset($previousAttributes['cancel_at_period_end'])) {
            $cancelAtPeriodEnd = $subscription['cancel_at_period_end'] ?? false;
            Log::info('Stripe Webhook: Subscription cancellation status changed', [
                'subscription_id' => $subscription['id'] ?? null,
                'cancel_at_period_end' => $cancelAtPeriodEnd,
            ]);
        }

        return $response;
    }

    /**
     * Handle customer subscription deleted.
     *
     * Called when a subscription is fully cancelled/deleted.
     * Use this to revoke access, send farewell emails, etc.
     */
    protected function handleCustomerSubscriptionDeleted(array $payload): Response
    {
        // Let Cashier handle the database updates first
        $response = parent::handleCustomerSubscriptionDeleted($payload);

        Log::info('Stripe Webhook: Subscription deleted', [
            'customer_id' => $payload['data']['object']['customer'] ?? null,
            'subscription_id' => $payload['data']['object']['id'] ?? null,
        ]);

        // Custom logic: Revoke access, send farewell email, etc.
        // Example:
        // $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        // if ($user) {
        //     $user->notify(new SubscriptionCancelledNotification());
        //     $user->revokeAllPremiumFeatures();
        // }

        return $response;
    }

    /**
     * Handle invoice payment succeeded.
     *
     * Called when a payment is successfully processed.
     * Use this to send receipts, update billing history, etc.
     */
    protected function handleInvoicePaymentSucceeded(array $payload): Response
    {
        $invoice = $payload['data']['object'];

        Log::info('Stripe Webhook: Invoice payment succeeded', [
            'customer_id' => $invoice['customer'] ?? null,
            'invoice_id' => $invoice['id'] ?? null,
            'amount_paid' => $invoice['amount_paid'] ?? null,
            'currency' => $invoice['currency'] ?? null,
        ]);

        // Custom logic: Send receipt email, update internal records, etc.
        // Example:
        // $user = $this->getUserByStripeId($invoice['customer']);
        // if ($user) {
        //     $user->notify(new PaymentSucceededNotification($invoice));
        // }

        return $this->successMethod();
    }

    /**
     * Handle invoice payment failed.
     *
     * Called when a payment attempt fails.
     * Use this to notify users, retry logic, etc.
     */
    protected function handleInvoicePaymentFailed(array $payload): Response
    {
        $invoice = $payload['data']['object'];

        Log::warning('Stripe Webhook: Invoice payment failed', [
            'customer_id' => $invoice['customer'] ?? null,
            'invoice_id' => $invoice['id'] ?? null,
            'amount_due' => $invoice['amount_due'] ?? null,
            'attempt_count' => $invoice['attempt_count'] ?? null,
            'next_payment_attempt' => $invoice['next_payment_attempt'] ?? null,
        ]);

        // Custom logic: Notify user, send dunning email, etc.
        // Example:
        // $user = $this->getUserByStripeId($invoice['customer']);
        // if ($user) {
        //     $user->notify(new PaymentFailedNotification($invoice));
        // }

        return $this->successMethod();
    }

    /**
     * Handle invoice payment action required.
     *
     * Called when additional authentication is needed (3D Secure, etc.).
     * Use this to notify users to complete payment.
     */
    protected function handleInvoicePaymentActionRequired(array $payload): Response
    {
        $invoice = $payload['data']['object'];

        Log::info('Stripe Webhook: Invoice payment action required', [
            'customer_id' => $invoice['customer'] ?? null,
            'invoice_id' => $invoice['id'] ?? null,
            'hosted_invoice_url' => $invoice['hosted_invoice_url'] ?? null,
        ]);

        // Custom logic: Notify user to complete payment authentication
        // Example:
        // $user = $this->getUserByStripeId($invoice['customer']);
        // if ($user) {
        //     $user->notify(new PaymentActionRequiredNotification($invoice));
        // }

        return $this->successMethod();
    }

    /**
     * Handle customer updated.
     *
     * Called when customer details are updated in Stripe.
     */
    protected function handleCustomerUpdated(array $payload): Response
    {
        $response = parent::handleCustomerUpdated($payload);

        Log::info('Stripe Webhook: Customer updated', [
            'customer_id' => $payload['data']['object']['id'] ?? null,
            'email' => $payload['data']['object']['email'] ?? null,
        ]);

        return $response;
    }

    /**
     * Handle charge refunded.
     *
     * Called when a charge is refunded.
     * Use this to update order status, notify users, etc.
     */
    protected function handleChargeRefunded(array $payload): Response
    {
        $charge = $payload['data']['object'];

        Log::info('Stripe Webhook: Charge refunded', [
            'customer_id' => $charge['customer'] ?? null,
            'charge_id' => $charge['id'] ?? null,
            'amount_refunded' => $charge['amount_refunded'] ?? null,
            'currency' => $charge['currency'] ?? null,
        ]);

        // Custom logic: Update order status, notify user, etc.

        return $this->successMethod();
    }

    /**
     * Handle any unhandled webhook event.
     *
     * Override this to log or handle events not explicitly defined.
     */
    public function handleWebhook(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        $eventType = $payload['type'] ?? 'unknown';

        // Log unhandled events for debugging
        if (! method_exists($this, $this->eventToMethod($eventType))) {
            Log::debug('Stripe Webhook: Unhandled event type', [
                'type' => $eventType,
            ]);
        }

        return parent::handleWebhook($request);
    }

    /**
     * Convert event type to method name.
     */
    private function eventToMethod(string $eventType): string
    {
        return 'handle'.str_replace('.', '', ucwords(str_replace('.', ' ', $eventType)));
    }
}
