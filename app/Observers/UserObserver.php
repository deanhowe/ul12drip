<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Observer for User model events.
 *
 * Demonstrates:
 * - Model event handling for user lifecycle
 * - Welcome email dispatch patterns
 * - Account status tracking
 * - Audit logging for user changes
 */
class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::info('User registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'created_at' => $user->created_at->toDateTimeString(),
        ]);

        ActivityLog::logCreated(Auth::user(), $user, 'User registered');
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        // Track email changes for re-verification
        if ($user->isDirty('email')) {
            Log::info('User email changing', [
                'user_id' => $user->id,
                'old_email' => $user->getOriginal('email'),
                'new_email' => $user->email,
            ]);

            ActivityLog::log(
                Auth::user(),
                $user,
                'email_changing',
                'User email is changing',
                ['old_email' => $user->getOriginal('email'), 'new_email' => $user->email]
            );

            // Reset email verification when email changes
            $user->email_verified_at = null;
        }

        // Track suspension
        if ($user->isDirty('suspended_at')) {
            if ($user->suspended_at !== null) {
                Log::warning('User suspended', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'suspended_at' => $user->suspended_at,
                ]);

                ActivityLog::log(Auth::user(), $user, 'suspended', 'User was suspended');
            } else {
                Log::info('User unsuspended', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                ActivityLog::log(Auth::user(), $user, 'unsuspended', 'User was unsuspended');
            }
        }

        // Track premium status changes
        if ($user->isDirty('is_premium')) {
            Log::info('User premium status changed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_premium' => $user->is_premium,
            ]);

            ActivityLog::log(
                Auth::user(),
                $user,
                'premium_status_changed',
                'User premium status changed to '.($user->is_premium ? 'true' : 'false')
            );
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Log::info('User updated', [
            'user_id' => $user->id,
            'changes' => array_keys($user->getChanges()),
        ]);

        ActivityLog::logUpdated(Auth::user(), $user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Log::info('User deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_at' => now()->toDateTimeString(),
        ]);

        ActivityLog::logDeleted(Auth::user(), $user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Log::info('User restored', [
            'user_id' => $user->id,
            'email' => $user->email,
            'restored_at' => now()->toDateTimeString(),
        ]);

        ActivityLog::log(Auth::user(), $user, 'restored', 'User was restored');
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Log::warning('User permanently deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        ActivityLog::log(Auth::user(), $user, 'force_deleted', 'User was permanently deleted');
    }
}
