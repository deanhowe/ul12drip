<?php

namespace App\Features;

use App\Models\User;

/**
 * PurchaseButton Feature Flag (Nullable Scope - Guest Users)
 *
 * This feature demonstrates handling nullable scopes, which is useful
 * when you need to check features for guest (unauthenticated) users.
 * The nullable type hint allows the feature to work without a user.
 *
 * Usage in Tinker:
 *   use App\Features\PurchaseButton;
 *   use Laravel\Pennant\Feature;
 *   Feature::for(null)->active(PurchaseButton::class); // Check for guest
 *   Feature::active(PurchaseButton::class); // Check for current user (or guest if not auth'd)
 *
 * @see https://laravel.com/docs/pennant#nullable-scope
 */
class PurchaseButton
{
    /**
     * Resolve the feature's initial value.
     *
     * The nullable User type allows this feature to be checked
     * for both authenticated users and guests.
     */
    public function resolve(?User $user): bool
    {
        // If no user (guest), show purchase button to encourage sign-up
        if ($user === null) {
            return true;
        }

        // For authenticated users, only show if they haven't purchased
        // In a real app, you might check $user->hasPurchased() or similar
        return $user->id > 0; // Always true for demo, but shows the pattern
    }
}
