<?php

namespace Tests\Feature\Billing;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for Billing functionality.
 *
 * Tests Laravel Cashier integration including:
 * - Billing dashboard access
 * - Plans page display
 * - Authentication requirements
 * - Subscription management routes
 *
 * Note: These tests focus on route accessibility and basic functionality.
 * Full Stripe integration tests require Stripe test keys and are typically
 * run in a separate test suite or with mocked Stripe responses.
 */
class BillingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();

        // Seed plans for testing
        $this->seed(\Database\Seeders\PlanSeeder::class);
    }

    /**
     * Check if Stripe is configured for testing.
     */
    protected function stripeIsConfigured(): bool
    {
        return ! empty(config('cashier.key')) && ! empty(config('cashier.secret'));
    }

    /**
     * Test that unauthenticated users cannot access billing dashboard.
     */
    public function test_billing_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('billing.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that authenticated users can access billing dashboard.
     *
     * Note: This test requires Stripe API credentials to be configured
     * because the billing dashboard creates a SetupIntent.
     */
    public function test_authenticated_user_can_access_billing_dashboard(): void
    {
        if (! $this->stripeIsConfigured()) {
            $this->markTestSkipped('Stripe API credentials not configured.');
        }

        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertViewIs('billing.index');
        $response->assertViewHas('user');
        $response->assertViewHas('plans');
        $response->assertViewHas('intent');
    }

    /**
     * Test that billing dashboard displays subscription status.
     *
     * Note: This test requires Stripe API credentials to be configured.
     */
    public function test_billing_dashboard_shows_no_subscription_message(): void
    {
        if (! $this->stripeIsConfigured()) {
            $this->markTestSkipped('Stripe API credentials not configured.');
        }

        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertSee('No Active Subscription');
        $response->assertSee('View Plans');
    }

    /**
     * Test that plans page is accessible to authenticated users.
     */
    public function test_authenticated_user_can_access_plans_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.plans'));

        $response->assertStatus(200);
        $response->assertViewIs('billing.plans');
        $response->assertViewHas('monthlyPlans');
        $response->assertViewHas('yearlyPlans');
    }

    /**
     * Test that plans page displays available plans.
     */
    public function test_plans_page_displays_available_plans(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.plans'));

        $response->assertStatus(200);
        $response->assertSee('Basic Monthly');
        $response->assertSee('Pro Monthly');
        $response->assertSee('Enterprise Monthly');
    }

    /**
     * Test that plans page shows pricing information.
     */
    public function test_plans_page_shows_pricing(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.plans'));

        $response->assertStatus(200);
        $response->assertSee('$9.99');  // Basic Monthly
        $response->assertSee('$29.99'); // Pro Monthly
    }

    /**
     * Test that invoices page requires authentication.
     */
    public function test_invoices_page_requires_authentication(): void
    {
        $response = $this->get(route('billing.invoices'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that authenticated users can access invoices page.
     *
     * Note: This test requires Stripe API credentials because
     * the invoices page calls $user->invoices() which requires Stripe.
     */
    public function test_authenticated_user_can_access_invoices_page(): void
    {
        if (! $this->stripeIsConfigured()) {
            $this->markTestSkipped('Stripe API credentials not configured.');
        }

        $response = $this->actingAs($this->user)
            ->get(route('billing.invoices'));

        $response->assertStatus(200);
        $response->assertViewIs('billing.invoices');
        $response->assertViewHas('invoices');
    }

    /**
     * Test that invoices page shows empty state when no invoices.
     *
     * Note: This test requires Stripe API credentials.
     */
    public function test_invoices_page_shows_empty_state(): void
    {
        if (! $this->stripeIsConfigured()) {
            $this->markTestSkipped('Stripe API credentials not configured.');
        }

        $response = $this->actingAs($this->user)
            ->get(route('billing.invoices'));

        $response->assertStatus(200);
        $response->assertSee('No Invoices Yet');
    }

    /**
     * Test that cancel subscription requires authentication.
     */
    public function test_cancel_subscription_requires_authentication(): void
    {
        $response = $this->post(route('billing.cancel'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that cancel returns error when no subscription exists.
     */
    public function test_cancel_returns_error_when_no_subscription(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.cancel'));

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('error', 'No active subscription to cancel.');
    }

    /**
     * Test that resume returns error when no subscription on grace period.
     */
    public function test_resume_returns_error_when_no_grace_period(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.resume'));

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('error', 'No subscription available to resume.');
    }

    /**
     * Test that swap requires authentication.
     */
    public function test_swap_requires_authentication(): void
    {
        $response = $this->post(route('billing.swap'), [
            'plan' => 1,
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that swap returns error when no subscription exists.
     */
    public function test_swap_returns_error_when_no_subscription(): void
    {
        $plan = Plan::first();

        $response = $this->actingAs($this->user)
            ->post(route('billing.swap'), [
                'plan' => $plan->id,
            ]);

        $response->assertRedirect(route('billing.plans'));
        $response->assertSessionHas('error', 'You do not have an active subscription to swap.');
    }

    /**
     * Test that swap validates plan exists.
     */
    public function test_swap_validates_plan_exists(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.swap'), [
                'plan' => 99999,
            ]);

        $response->assertSessionHasErrors('plan');
    }

    /**
     * Test that apply coupon requires authentication.
     */
    public function test_apply_coupon_requires_authentication(): void
    {
        $response = $this->post(route('billing.coupon.apply'), [
            'coupon' => 'TESTCODE',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that apply coupon returns error when no subscription.
     */
    public function test_apply_coupon_returns_error_when_no_subscription(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.coupon.apply'), [
                'coupon' => 'TESTCODE',
            ]);

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('error', 'No active subscription to apply coupon to.');
    }

    /**
     * Test that update payment method requires authentication.
     */
    public function test_update_payment_method_requires_authentication(): void
    {
        $response = $this->post(route('billing.payment-method.update'), [
            'payment_method' => 'pm_test_123',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that update payment method validates input.
     */
    public function test_update_payment_method_validates_input(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.payment-method.update'), []);

        $response->assertSessionHasErrors('payment_method');
    }

    /**
     * Test that subscribe validates required fields.
     */
    public function test_subscribe_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.subscribe'), []);

        $response->assertSessionHasErrors(['plan', 'payment_method']);
    }

    /**
     * Test that report usage requires authentication.
     */
    public function test_report_usage_requires_authentication(): void
    {
        $response = $this->post(route('billing.usage.report'), [
            'quantity' => 10,
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that report usage validates quantity.
     */
    public function test_report_usage_validates_quantity(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.usage.report'), [
                'quantity' => 0,
            ]);

        $response->assertSessionHasErrors('quantity');
    }
}
