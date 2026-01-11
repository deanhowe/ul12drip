<?php

namespace Tests\Feature;

use App\Features\NewApi;
use App\Features\TeamBilling;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Pennant\Feature;
use Tests\TestCase;

class PennantClassFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the class-based NewApi feature.
     */
    public function test_new_api_feature_resolves_to_boolean(): void
    {
        $user = User::factory()->create();

        $result = Feature::for($user)->active(NewApi::class);

        $this->assertIsBool($result);
    }

    /**
     * Test the class-based TeamBilling feature with rich values.
     */
    public function test_team_billing_resolves_correctly_based_on_user_id(): void
    {
        // User ID % 3 === 0 => 'enterprise'
        $user1 = User::factory()->create(['id' => 3]);
        $this->assertEquals('enterprise', Feature::for($user1)->value(TeamBilling::class));

        // User ID % 2 === 0 (and not % 3 === 0) => 'premium'
        $user2 = User::factory()->create(['id' => 2]);
        $this->assertEquals('premium', Feature::for($user2)->value(TeamBilling::class));

        // default => 'basic'
        $user3 = User::factory()->create(['id' => 1]);
        $this->assertEquals('basic', Feature::for($user3)->value(TeamBilling::class));
    }

    /**
     * Test that class-based features can be manually activated.
     */
    public function test_class_based_features_can_be_manually_activated(): void
    {
        $user = User::factory()->create();

        Feature::for($user)->activate(NewApi::class);

        $this->assertTrue(Feature::for($user)->active(NewApi::class));
    }

    /**
     * Test that class-based features can be manually deactivated.
     */
    public function test_class_based_features_can_be_manually_deactivated(): void
    {
        $user = User::factory()->create();

        Feature::for($user)->deactivate(NewApi::class);

        $this->assertFalse(Feature::for($user)->active(NewApi::class));
    }
}
