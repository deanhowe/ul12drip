<?php

namespace Tests\Feature;

use App\Features\NewApi;
use App\Features\PurchaseButton;
use App\Features\SiteBanner;
use App\Features\TeamBilling;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Pennant\Feature;
use Tests\TestCase;

/**
 * Tests for Laravel Pennant feature flags.
 *
 * Demonstrates:
 * - Class-based feature flags
 * - Closure-based feature flags
 * - Rich feature values (A/B testing)
 * - Nullable scope (guest users)
 * - Global features (no scope)
 * - Feature activation/deactivation
 */
class PennantFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Purge all feature flags before each test
        Feature::purge();
    }

    // Class-based Feature: NewApi

    public function test_new_api_feature_resolves_for_user(): void
    {
        $user = User::factory()->create();

        // NewApi uses Lottery, so we just verify it returns a boolean
        $result = Feature::for($user)->active(NewApi::class);

        $this->assertIsBool($result);
    }

    public function test_new_api_feature_can_be_activated(): void
    {
        $user = User::factory()->create();

        Feature::for($user)->activate(NewApi::class);

        $this->assertTrue(Feature::for($user)->active(NewApi::class));
    }

    public function test_new_api_feature_can_be_deactivated(): void
    {
        $user = User::factory()->create();

        Feature::for($user)->deactivate(NewApi::class);

        $this->assertFalse(Feature::for($user)->active(NewApi::class));
    }

    // Class-based Feature: TeamBilling (Rich Values)

    public function test_team_billing_returns_rich_value(): void
    {
        $user = User::factory()->create();

        $value = Feature::for($user)->value(TeamBilling::class);

        $this->assertContains($value, ['basic', 'premium', 'enterprise']);
    }

    public function test_team_billing_returns_enterprise_for_user_id_divisible_by_3(): void
    {
        // Create users until we get one with ID divisible by 3
        $user = User::factory()->create(['id' => 3]);

        $value = Feature::for($user)->value(TeamBilling::class);

        $this->assertEquals('enterprise', $value);
    }

    public function test_team_billing_returns_premium_for_even_user_id_not_divisible_by_3(): void
    {
        $user = User::factory()->create(['id' => 2]);

        $value = Feature::for($user)->value(TeamBilling::class);

        $this->assertEquals('premium', $value);
    }

    public function test_team_billing_returns_basic_for_odd_user_id_not_divisible_by_3(): void
    {
        $user = User::factory()->create(['id' => 1]);

        $value = Feature::for($user)->value(TeamBilling::class);

        $this->assertEquals('basic', $value);
    }

    // Class-based Feature: PurchaseButton (Nullable Scope)

    public function test_purchase_button_works_for_guest_users(): void
    {
        // Test with null scope (guest user)
        $result = Feature::for(null)->active(PurchaseButton::class);

        $this->assertTrue($result);
    }

    public function test_purchase_button_works_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $result = Feature::for($user)->active(PurchaseButton::class);

        $this->assertIsBool($result);
    }

    // Class-based Feature: SiteBanner (Global Feature)

    public function test_site_banner_is_global_feature(): void
    {
        // Global features don't require a scope
        $result = Feature::active(SiteBanner::class);

        $this->assertFalse($result); // Default is false
    }

    public function test_site_banner_can_be_activated_globally(): void
    {
        Feature::activate(SiteBanner::class);

        $this->assertTrue(Feature::active(SiteBanner::class));
    }

    public function test_site_banner_can_be_deactivated_globally(): void
    {
        Feature::activate(SiteBanner::class);
        Feature::deactivate(SiteBanner::class);

        $this->assertFalse(Feature::active(SiteBanner::class));
    }

    // Closure-based Feature: dark-mode

    public function test_dark_mode_feature_for_even_user_id(): void
    {
        $user = User::factory()->create(['id' => 2]);

        $result = Feature::for($user)->active('dark-mode');

        $this->assertTrue($result);
    }

    public function test_dark_mode_feature_for_odd_user_id(): void
    {
        $user = User::factory()->create(['id' => 1]);

        $result = Feature::for($user)->active('dark-mode');

        $this->assertFalse($result);
    }

    // Closure-based Feature: beta-tester

    public function test_beta_tester_feature_resolves_for_user(): void
    {
        $user = User::factory()->create();

        // beta-tester uses Lottery, so we just verify it returns a boolean
        $result = Feature::for($user)->active('beta-tester');

        $this->assertIsBool($result);
    }

    // Closure-based Feature: homepage-variant (Rich Values / A/B Testing)

    public function test_homepage_variant_returns_rich_value(): void
    {
        $user = User::factory()->create();

        $value = Feature::for($user)->value('homepage-variant');

        $this->assertContains($value, ['control', 'variant-a', 'variant-b']);
    }

    public function test_homepage_variant_control_for_user_id_divisible_by_3(): void
    {
        $user = User::factory()->create(['id' => 3]);

        $value = Feature::for($user)->value('homepage-variant');

        $this->assertEquals('control', $value);
    }

    public function test_homepage_variant_a_for_user_id_mod_3_equals_1(): void
    {
        $user = User::factory()->create(['id' => 1]);

        $value = Feature::for($user)->value('homepage-variant');

        $this->assertEquals('variant-a', $value);
    }

    public function test_homepage_variant_b_for_user_id_mod_3_equals_2(): void
    {
        $user = User::factory()->create(['id' => 2]);

        $value = Feature::for($user)->value('homepage-variant');

        $this->assertEquals('variant-b', $value);
    }

    // Closure-based Feature: maintenance-mode (Global)

    public function test_maintenance_mode_is_global_feature(): void
    {
        $result = Feature::active('maintenance-mode');

        $this->assertFalse($result); // Default is false
    }

    public function test_maintenance_mode_can_be_toggled(): void
    {
        Feature::activate('maintenance-mode');
        $this->assertTrue(Feature::active('maintenance-mode'));

        Feature::deactivate('maintenance-mode');
        $this->assertFalse(Feature::active('maintenance-mode'));
    }

    // Feature::when() usage

    public function test_feature_when_executes_callback_when_active(): void
    {
        $user = User::factory()->create(['id' => 2]); // Even ID = dark-mode active

        $result = Feature::for($user)->when('dark-mode',
            fn () => 'dark theme applied',
            fn () => 'light theme applied'
        );

        $this->assertEquals('dark theme applied', $result);
    }

    public function test_feature_when_executes_fallback_when_inactive(): void
    {
        $user = User::factory()->create(['id' => 1]); // Odd ID = dark-mode inactive

        $result = Feature::for($user)->when('dark-mode',
            fn () => 'dark theme applied',
            fn () => 'light theme applied'
        );

        $this->assertEquals('light theme applied', $result);
    }

    // Feature values with when()

    public function test_feature_when_with_rich_values(): void
    {
        $user = User::factory()->create(['id' => 1]); // variant-a

        $result = Feature::for($user)->when('homepage-variant',
            fn ($variant) => "Showing: {$variant}"
        );

        $this->assertEquals('Showing: variant-a', $result);
    }

    // Bulk feature checks

    public function test_checking_multiple_features_at_once(): void
    {
        $user = User::factory()->create();

        $features = Feature::for($user)->values([
            'dark-mode',
            'beta-tester',
            'homepage-variant',
        ]);

        $this->assertIsArray($features);
        $this->assertArrayHasKey('dark-mode', $features);
        $this->assertArrayHasKey('beta-tester', $features);
        $this->assertArrayHasKey('homepage-variant', $features);
    }
}
