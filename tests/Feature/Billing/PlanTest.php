<?php

namespace Tests\Feature\Billing;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for the Plan model.
 *
 * Tests Plan model functionality including:
 * - Model creation and attributes
 * - Query scopes (active, featured, monthly, yearly, ordered)
 * - Accessors (formatted_price, interval_label)
 * - Helper methods (isMonthly, isYearly)
 * - Seeder functionality
 */
class PlanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that plans can be created with all attributes.
     */
    public function test_plan_can_be_created(): void
    {
        $plan = Plan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan',
            'stripe_price_id' => 'price_test_123',
            'price' => 1999,
            'interval' => 'month',
            'interval_count' => 1,
            'description' => 'A test plan',
            'features' => ['Feature 1', 'Feature 2'],
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('plans', [
            'slug' => 'test-plan',
            'stripe_price_id' => 'price_test_123',
            'price' => 1999,
        ]);

        $this->assertEquals('Test Plan', $plan->name);
        $this->assertEquals(['Feature 1', 'Feature 2'], $plan->features);
    }

    /**
     * Test that PlanSeeder creates expected plans.
     */
    public function test_plan_seeder_creates_plans(): void
    {
        $this->seed(\Database\Seeders\PlanSeeder::class);

        $this->assertDatabaseCount('plans', 7);
        $this->assertDatabaseHas('plans', ['slug' => 'basic-monthly']);
        $this->assertDatabaseHas('plans', ['slug' => 'pro-monthly']);
        $this->assertDatabaseHas('plans', ['slug' => 'enterprise-monthly']);
        $this->assertDatabaseHas('plans', ['slug' => 'basic-yearly']);
        $this->assertDatabaseHas('plans', ['slug' => 'pro-yearly']);
        $this->assertDatabaseHas('plans', ['slug' => 'enterprise-yearly']);
        $this->assertDatabaseHas('plans', ['slug' => 'metered']);
    }

    /**
     * Test the active scope returns only active plans.
     */
    public function test_active_scope(): void
    {
        Plan::create([
            'name' => 'Active Plan',
            'slug' => 'active-plan',
            'stripe_price_id' => 'price_active',
            'price' => 999,
            'interval' => 'month',
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Inactive Plan',
            'slug' => 'inactive-plan',
            'stripe_price_id' => 'price_inactive',
            'price' => 999,
            'interval' => 'month',
            'is_active' => false,
        ]);

        $activePlans = Plan::active()->get();

        $this->assertCount(1, $activePlans);
        $this->assertEquals('Active Plan', $activePlans->first()->name);
    }

    /**
     * Test the featured scope returns only featured plans.
     */
    public function test_featured_scope(): void
    {
        Plan::create([
            'name' => 'Featured Plan',
            'slug' => 'featured-plan',
            'stripe_price_id' => 'price_featured',
            'price' => 999,
            'interval' => 'month',
            'is_featured' => true,
        ]);

        Plan::create([
            'name' => 'Regular Plan',
            'slug' => 'regular-plan',
            'stripe_price_id' => 'price_regular',
            'price' => 999,
            'interval' => 'month',
            'is_featured' => false,
        ]);

        $featuredPlans = Plan::featured()->get();

        $this->assertCount(1, $featuredPlans);
        $this->assertEquals('Featured Plan', $featuredPlans->first()->name);
    }

    /**
     * Test the monthly scope returns only monthly plans.
     */
    public function test_monthly_scope(): void
    {
        Plan::create([
            'name' => 'Monthly Plan',
            'slug' => 'monthly-plan',
            'stripe_price_id' => 'price_monthly',
            'price' => 999,
            'interval' => 'month',
        ]);

        Plan::create([
            'name' => 'Yearly Plan',
            'slug' => 'yearly-plan',
            'stripe_price_id' => 'price_yearly',
            'price' => 9999,
            'interval' => 'year',
        ]);

        $monthlyPlans = Plan::monthly()->get();

        $this->assertCount(1, $monthlyPlans);
        $this->assertEquals('Monthly Plan', $monthlyPlans->first()->name);
    }

    /**
     * Test the yearly scope returns only yearly plans.
     */
    public function test_yearly_scope(): void
    {
        Plan::create([
            'name' => 'Monthly Plan',
            'slug' => 'monthly-plan',
            'stripe_price_id' => 'price_monthly',
            'price' => 999,
            'interval' => 'month',
        ]);

        Plan::create([
            'name' => 'Yearly Plan',
            'slug' => 'yearly-plan',
            'stripe_price_id' => 'price_yearly',
            'price' => 9999,
            'interval' => 'year',
        ]);

        $yearlyPlans = Plan::yearly()->get();

        $this->assertCount(1, $yearlyPlans);
        $this->assertEquals('Yearly Plan', $yearlyPlans->first()->name);
    }

    /**
     * Test the ordered scope sorts by sort_order.
     */
    public function test_ordered_scope(): void
    {
        Plan::create([
            'name' => 'Third Plan',
            'slug' => 'third-plan',
            'stripe_price_id' => 'price_third',
            'price' => 999,
            'interval' => 'month',
            'sort_order' => 3,
        ]);

        Plan::create([
            'name' => 'First Plan',
            'slug' => 'first-plan',
            'stripe_price_id' => 'price_first',
            'price' => 999,
            'interval' => 'month',
            'sort_order' => 1,
        ]);

        Plan::create([
            'name' => 'Second Plan',
            'slug' => 'second-plan',
            'stripe_price_id' => 'price_second',
            'price' => 999,
            'interval' => 'month',
            'sort_order' => 2,
        ]);

        $orderedPlans = Plan::ordered()->get();

        $this->assertEquals('First Plan', $orderedPlans[0]->name);
        $this->assertEquals('Second Plan', $orderedPlans[1]->name);
        $this->assertEquals('Third Plan', $orderedPlans[2]->name);
    }

    /**
     * Test the formatted_price accessor.
     */
    public function test_formatted_price_accessor(): void
    {
        $plan = Plan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan',
            'stripe_price_id' => 'price_test',
            'price' => 999,
            'interval' => 'month',
        ]);

        $this->assertEquals('$9.99', $plan->formatted_price);

        $plan->price = 2999;
        $this->assertEquals('$29.99', $plan->formatted_price);

        $plan->price = 0;
        $this->assertEquals('$0.00', $plan->formatted_price);
    }

    /**
     * Test the interval_label accessor for monthly plans.
     */
    public function test_interval_label_accessor_monthly(): void
    {
        $plan = Plan::create([
            'name' => 'Monthly Plan',
            'slug' => 'monthly-plan',
            'stripe_price_id' => 'price_monthly',
            'price' => 999,
            'interval' => 'month',
            'interval_count' => 1,
        ]);

        $this->assertEquals('per month', $plan->interval_label);
    }

    /**
     * Test the interval_label accessor for yearly plans.
     */
    public function test_interval_label_accessor_yearly(): void
    {
        $plan = Plan::create([
            'name' => 'Yearly Plan',
            'slug' => 'yearly-plan',
            'stripe_price_id' => 'price_yearly',
            'price' => 9999,
            'interval' => 'year',
            'interval_count' => 1,
        ]);

        $this->assertEquals('per year', $plan->interval_label);
    }

    /**
     * Test the interval_label accessor with multiple intervals.
     */
    public function test_interval_label_accessor_multiple_intervals(): void
    {
        $plan = Plan::create([
            'name' => 'Quarterly Plan',
            'slug' => 'quarterly-plan',
            'stripe_price_id' => 'price_quarterly',
            'price' => 2499,
            'interval' => 'month',
            'interval_count' => 3,
        ]);

        $this->assertEquals('per 3 months', $plan->interval_label);
    }

    /**
     * Test the isMonthly helper method.
     */
    public function test_is_monthly_method(): void
    {
        $monthlyPlan = Plan::create([
            'name' => 'Monthly Plan',
            'slug' => 'monthly-plan',
            'stripe_price_id' => 'price_monthly',
            'price' => 999,
            'interval' => 'month',
            'interval_count' => 1,
        ]);

        $yearlyPlan = Plan::create([
            'name' => 'Yearly Plan',
            'slug' => 'yearly-plan',
            'stripe_price_id' => 'price_yearly',
            'price' => 9999,
            'interval' => 'year',
            'interval_count' => 1,
        ]);

        $this->assertTrue($monthlyPlan->isMonthly());
        $this->assertFalse($yearlyPlan->isMonthly());
    }

    /**
     * Test the isYearly helper method.
     */
    public function test_is_yearly_method(): void
    {
        $monthlyPlan = Plan::create([
            'name' => 'Monthly Plan',
            'slug' => 'monthly-plan',
            'stripe_price_id' => 'price_monthly',
            'price' => 999,
            'interval' => 'month',
            'interval_count' => 1,
        ]);

        $yearlyPlan = Plan::create([
            'name' => 'Yearly Plan',
            'slug' => 'yearly-plan',
            'stripe_price_id' => 'price_yearly',
            'price' => 9999,
            'interval' => 'year',
            'interval_count' => 1,
        ]);

        $this->assertFalse($monthlyPlan->isYearly());
        $this->assertTrue($yearlyPlan->isYearly());
    }

    /**
     * Test that features are cast to array.
     */
    public function test_features_cast_to_array(): void
    {
        $plan = Plan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan',
            'stripe_price_id' => 'price_test',
            'price' => 999,
            'interval' => 'month',
            'features' => ['Feature 1', 'Feature 2', 'Feature 3'],
        ]);

        $this->assertIsArray($plan->features);
        $this->assertCount(3, $plan->features);
        $this->assertContains('Feature 1', $plan->features);
    }

    /**
     * Test chaining multiple scopes.
     */
    public function test_chaining_scopes(): void
    {
        $this->seed(\Database\Seeders\PlanSeeder::class);

        $activeFeaturedMonthly = Plan::active()->featured()->monthly()->get();

        // Pro Monthly is the only active, featured, monthly plan
        $this->assertCount(1, $activeFeaturedMonthly);
        $this->assertEquals('Pro Monthly', $activeFeaturedMonthly->first()->name);
    }
}
