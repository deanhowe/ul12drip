<?php

namespace App\Features;

use App\Models\User;

/**
 * TeamBilling Feature Flag (Class-based Pennant Feature with Rich Values)
 *
 * This feature demonstrates returning rich values (not just boolean) from
 * a feature flag. This is useful when you need to return configuration
 * values or variant names for A/B testing.
 *
 * Usage in Tinker:
 *   use App\Features\TeamBilling;
 *   use Laravel\Pennant\Feature;
 *   Feature::value(TeamBilling::class); // Returns 'basic', 'premium', or 'enterprise'
 *   Feature::when(TeamBilling::class,
 *       fn ($variant) => match($variant) {
 *           'basic' => 'Basic billing',
 *           'premium' => 'Premium billing',
 *           'enterprise' => 'Enterprise billing',
 *       }
 *   );
 *
 * @see https://laravel.com/docs/pennant#rich-feature-values
 */
class TeamBilling
{
    /**
     * Resolve the feature's initial value.
     *
     * Returns a billing tier based on user attributes.
     * This demonstrates rich feature values for A/B testing variants.
     */
    public function resolve(User $user): string
    {
        // Determine billing tier based on user ID for demo purposes
        // In production, this might check team subscription, user role, etc.
        return match (true) {
            $user->id % 3 === 0 => 'enterprise',
            $user->id % 2 === 0 => 'premium',
            default => 'basic',
        };
    }
}
