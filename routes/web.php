<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use Laravel\Folio\Folio;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Stripe Webhook Route
|--------------------------------------------------------------------------
|
| This route handles incoming Stripe webhooks. It uses our custom
| StripeWebhookController which extends Cashier's WebhookController.
|
| The route is excluded from CSRF verification (see VerifyCsrfToken middleware).
| Webhook signature verification is handled by Cashier's middleware.
|
| Configure this URL in Stripe Dashboard: https://dashboard.stripe.com/webhooks
| Webhook URL: https://your-domain.com/stripe/webhook
|
| Required environment variable: STRIPE_WEBHOOK_SECRET
|
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook');

/*
|--------------------------------------------------------------------------
| Health Check Route (Invokable Controller)
|--------------------------------------------------------------------------
|
| Demonstrates the invokable controller pattern. This route uses a
| single-action controller that handles health checks for monitoring.
|
*/
Route::get('/health', HealthCheckController::class)->name('health');

/*
|--------------------------------------------------------------------------
| Billing Routes (Laravel Cashier / Stripe)
|--------------------------------------------------------------------------
|
| These routes handle subscription billing using Laravel Cashier.
| All billing routes require authentication.
|
| Demonstrates:
| - Subscription management (create, swap, cancel, resume)
| - Payment method management (add, update, remove)
| - Invoice viewing and PDF download
| - Stripe Checkout integration
| - Stripe Customer Portal redirect
| - Coupon/promotion code application
| - Metered billing usage reporting
|
| Note: Webhook routes are registered separately (see below).
|
*/
Route::middleware(['auth'])->prefix('billing')->name('billing.')->group(function () {
    // Billing dashboard - shows subscription, payment methods, invoices
    Route::get('/', [BillingController::class, 'index'])->name('index');

    // Available plans page
    Route::get('/plans', [BillingController::class, 'plans'])->name('plans');

    // Stripe Checkout flow
    Route::get('/checkout/{plan}', [BillingController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/success', [BillingController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/checkout/cancel', [BillingController::class, 'checkoutCancel'])->name('checkout.cancel');

    // Custom subscription form (alternative to Checkout)
    Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('subscribe');

    // Subscription management
    Route::post('/swap', [BillingController::class, 'swap'])->name('swap');
    Route::post('/cancel', [BillingController::class, 'cancel'])->name('cancel');
    Route::post('/resume', [BillingController::class, 'resume'])->name('resume');

    // Payment methods
    Route::post('/payment-method', [BillingController::class, 'updatePaymentMethod'])->name('payment-method.update');
    Route::post('/payment-method/add', [BillingController::class, 'addPaymentMethod'])->name('payment-method.add');
    Route::delete('/payment-method/{paymentMethodId}', [BillingController::class, 'removePaymentMethod'])->name('payment-method.remove');

    // Invoices
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoiceId}/download', [BillingController::class, 'downloadInvoice'])->name('invoices.download');

    // Stripe Customer Portal
    Route::get('/portal', [BillingController::class, 'portal'])->name('portal');

    // Coupons
    Route::post('/coupon', [BillingController::class, 'applyCoupon'])->name('coupon.apply');

    // Metered usage (for usage-based billing)
    Route::post('/usage', [BillingController::class, 'reportUsage'])->name('usage.report');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
|
| This route will be executed when no other route matches the incoming
| request. This is useful for handling 404 errors gracefully.
|
*/
Route::fallback(fn () => response()->view('errors.404', [], 404));

Folio::path(resource_path('views/pages'))->middleware([
    '*' => [
        //
    ],
]);
