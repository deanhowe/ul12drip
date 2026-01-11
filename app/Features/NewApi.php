<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

/**
 * NewApi Feature Flag (Class-based Pennant Feature)
 *
 * This feature demonstrates a class-based feature flag that can be used
 * to gradually roll out a new API to users. It uses Laravel's Lottery
 * to randomly enable the feature for a percentage of users.
 *
 * Usage in Tinker:
 *   use App\Features\NewApi;
 *   use Laravel\Pennant\Feature;
 *   Feature::active(NewApi::class); // Check if active for current user
 *   Feature::for($user)->active(NewApi::class); // Check for specific user
 *
 * @see https://laravel.com/docs/pennant#class-based-features
 */
class NewApi
{
    /**
     * Resolve the feature's initial value.
     *
     * This method is called when the feature is first checked for a user.
     * The result is stored and cached for subsequent checks.
     */
    public function resolve(User $user): mixed
    {
        // Roll out to 10% of users randomly
        return Lottery::odds(1, 10)->choose();
    }
}
