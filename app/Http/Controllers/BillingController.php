<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Cashier\Exceptions\IncompletePayment;

/**
 * BillingController handles all subscription billing operations.
 *
 * Demonstrates Laravel Cashier features:
 * - Subscription management (create, swap, cancel, resume)
 * - Payment method management
 * - Invoice viewing and downloading
 * - Stripe Customer Portal integration
 * - Checkout sessions
 * - Proration handling
 *
 * All routes require authentication via the 'auth' middleware.
 *
 * Tinker Examples:
 * ----------------
 * // Get user's current subscription:
 * $user = User::find(1);
 * $user->subscription('default');
 *
 * // Check subscription status:
 * $user->subscribed('default');
 * $user->subscription('default')->onGracePeriod();
 * $user->subscription('default')->cancelled();
 *
 * // Get invoices:
 * $user->invoices();
 * $user->invoicesIncludingPending();
 */
class BillingController extends Controller
{
    /**
     * Display the billing dashboard.
     *
     * Shows current subscription, payment methods, and recent invoices.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('billing.index', [
            'user' => $user,
            'subscription' => $user->subscription('default'),
            'plans' => Plan::active()->ordered()->get(),
            'invoices' => $user->invoices()->take(10),
            'defaultPaymentMethod' => $user->defaultPaymentMethod(),
            'paymentMethods' => $user->paymentMethods(),
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * Display available subscription plans.
     */
    public function plans(): View
    {
        return view('billing.plans', [
            'monthlyPlans' => Plan::active()->monthly()->ordered()->get(),
            'yearlyPlans' => Plan::active()->yearly()->ordered()->get(),
        ]);
    }

    /**
     * Subscribe to a plan using Stripe Checkout.
     *
     * This redirects to Stripe's hosted checkout page for secure payment collection.
     * After payment, Stripe redirects back to our success/cancel URLs.
     */
    public function checkout(Request $request, Plan $plan): RedirectResponse
    {
        $request->validate([
            'promotion_code' => ['nullable', 'string', 'max:50'],
        ]);

        $user = $request->user();

        // If already subscribed to this plan, redirect back
        if ($user->subscribedToPrice($plan->stripe_price_id, 'default')) {
            return redirect()->route('billing.index')
                ->with('info', 'You are already subscribed to this plan.');
        }

        // Build checkout session
        $checkout = $user->newSubscription('default', $plan->stripe_price_id)
            ->allowPromotionCodes();

        // Redirect to Stripe Checkout
        return $checkout->checkout([
            'success_url' => route('billing.checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.checkout.cancel'),
        ]);
    }

    /**
     * Handle successful checkout.
     */
    public function checkoutSuccess(Request $request): RedirectResponse
    {
        return redirect()->route('billing.index')
            ->with('success', 'Thank you! Your subscription is now active.');
    }

    /**
     * Handle cancelled checkout.
     */
    public function checkoutCancel(): RedirectResponse
    {
        return redirect()->route('billing.plans')
            ->with('info', 'Checkout was cancelled. You can try again anytime.');
    }

    /**
     * Subscribe using a payment method (for custom checkout forms).
     *
     * This demonstrates creating a subscription with a payment method ID
     * collected via Stripe Elements on your own form.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'exists:plans,id'],
            'payment_method' => ['required', 'string'],
        ]);

        $user = $request->user();
        $plan = Plan::findOrFail($request->plan);

        try {
            // Create subscription with the provided payment method
            $user->newSubscription('default', $plan->stripe_price_id)
                ->create($request->payment_method);

            return redirect()->route('billing.index')
                ->with('success', 'Successfully subscribed to '.$plan->name.'!');
        } catch (IncompletePayment $exception) {
            // Payment requires additional confirmation (3D Secure, etc.)
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('billing.index')]
            );
        }
    }

    /**
     * Swap to a different subscription plan.
     *
     * Demonstrates proration - Stripe automatically calculates
     * credits/charges when switching plans mid-cycle.
     */
    public function swap(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'exists:plans,id'],
        ]);

        $user = $request->user();
        $plan = Plan::findOrFail($request->plan);
        $subscription = $user->subscription('default');

        if (! $subscription) {
            return redirect()->route('billing.plans')
                ->with('error', 'You do not have an active subscription to swap.');
        }

        // Swap with proration (default behavior)
        // Use ->noProrate() to disable proration
        // Use ->setProrationBehavior('always_invoice') to invoice immediately
        $subscription->swap($plan->stripe_price_id);

        return redirect()->route('billing.index')
            ->with('success', 'Successfully switched to '.$plan->name.'!');
    }

    /**
     * Cancel the current subscription.
     *
     * By default, cancellation takes effect at the end of the billing period.
     * The user retains access until then (grace period).
     */
    public function cancel(Request $request): RedirectResponse
    {
        $user = $request->user();
        $subscription = $user->subscription('default');

        if (! $subscription) {
            return redirect()->route('billing.index')
                ->with('error', 'No active subscription to cancel.');
        }

        // Cancel at end of billing period (grace period)
        $subscription->cancel();

        // To cancel immediately: $subscription->cancelNow();
        // To cancel and invoice final usage: $subscription->cancelNowAndInvoice();

        return redirect()->route('billing.index')
            ->with('success', 'Your subscription has been cancelled. You will retain access until the end of your billing period.');
    }

    /**
     * Resume a cancelled subscription (during grace period).
     */
    public function resume(Request $request): RedirectResponse
    {
        $user = $request->user();
        $subscription = $user->subscription('default');

        if (! $subscription || ! $subscription->onGracePeriod()) {
            return redirect()->route('billing.index')
                ->with('error', 'No subscription available to resume.');
        }

        $subscription->resume();

        return redirect()->route('billing.index')
            ->with('success', 'Your subscription has been resumed!');
    }

    /**
     * Update the default payment method.
     *
     * Uses a Setup Intent to securely collect payment details via Stripe.js.
     */
    public function updatePaymentMethod(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Update the default payment method
        $user->updateDefaultPaymentMethod($request->payment_method);

        return redirect()->route('billing.index')
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Add a new payment method (without setting as default).
     */
    public function addPaymentMethod(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Add payment method without setting as default
        $user->addPaymentMethod($request->payment_method);

        return redirect()->route('billing.index')
            ->with('success', 'Payment method added successfully!');
    }

    /**
     * Remove a payment method.
     */
    public function removePaymentMethod(Request $request, string $paymentMethodId): RedirectResponse
    {
        $user = $request->user();

        $paymentMethod = $user->findPaymentMethod($paymentMethodId);

        if ($paymentMethod) {
            $paymentMethod->delete();
        }

        return redirect()->route('billing.index')
            ->with('success', 'Payment method removed.');
    }

    /**
     * Display all invoices.
     */
    public function invoices(Request $request): View
    {
        $user = $request->user();

        return view('billing.invoices', [
            'invoices' => $user->invoices(),
            'upcomingInvoice' => $user->subscription('default')?->upcomingInvoice(),
        ]);
    }

    /**
     * Download an invoice as PDF.
     *
     * Cashier generates a PDF invoice using the data from Stripe.
     */
    public function downloadInvoice(Request $request, string $invoiceId)
    {
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor' => config('app.name'),
            'product' => 'Subscription',
            'street' => '123 Example Street',
            'location' => 'City, State 12345',
            'phone' => '+1 (555) 123-4567',
            'email' => config('mail.from.address'),
            'url' => config('app.url'),
        ]);
    }

    /**
     * Redirect to Stripe Customer Portal.
     *
     * The Customer Portal allows users to manage their subscription,
     * update payment methods, view invoices, and more - all hosted by Stripe.
     *
     * Configure the portal in Stripe Dashboard > Settings > Billing > Customer Portal.
     */
    public function portal(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('billing.index'));
    }

    /**
     * Apply a coupon/promotion code to the subscription.
     */
    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon' => ['required', 'string', 'max:50'],
        ]);

        $user = $request->user();
        $subscription = $user->subscription('default');

        if (! $subscription) {
            return redirect()->route('billing.index')
                ->with('error', 'No active subscription to apply coupon to.');
        }

        try {
            $subscription->applyCoupon($request->coupon);

            return redirect()->route('billing.index')
                ->with('success', 'Coupon applied successfully!');
        } catch (\Exception $e) {
            return redirect()->route('billing.index')
                ->with('error', 'Invalid coupon code.');
        }
    }

    /**
     * Report metered usage for usage-based billing.
     *
     * This demonstrates how to report usage for metered subscriptions.
     * Call this whenever a billable event occurs (API call, storage used, etc.).
     */
    public function reportUsage(Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        $subscription = $user->subscription('default');

        if (! $subscription) {
            return redirect()->route('billing.index')
                ->with('error', 'No active subscription.');
        }

        // Report usage for the metered price
        // The price must be configured as metered in Stripe
        $subscription->reportUsage($request->quantity);

        return redirect()->route('billing.index')
            ->with('success', 'Usage reported: '.$request->quantity.' units.');
    }
}
