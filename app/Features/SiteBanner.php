<?php

namespace App\Features;

/**
 * SiteBanner Feature Flag (Global Feature - No Scope)
 *
 * This feature demonstrates a global feature that doesn't depend on any
 * user or scope. It's useful for site-wide features like maintenance
 * banners, holiday themes, or global announcements.
 *
 * Usage in Tinker:
 *   use App\Features\SiteBanner;
 *   use Laravel\Pennant\Feature;
 *   Feature::active(SiteBanner::class); // Check if banner should show
 *   Feature::activate(SiteBanner::class); // Enable the banner globally
 *   Feature::deactivate(SiteBanner::class); // Disable the banner globally
 *
 * @see https://laravel.com/docs/pennant#scope
 */
class SiteBanner
{
    /**
     * Resolve the feature's initial value.
     *
     * No parameters means this is a global feature that applies
     * to all users equally. Great for site-wide toggles.
     */
    public function resolve(): bool
    {
        // Default to false - banner is off unless explicitly activated
        // In production, you might check config, environment, or date ranges
        return false;
    }
}
