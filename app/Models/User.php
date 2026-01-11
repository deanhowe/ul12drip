<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasAddresses;
use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

/**
 * User model with soft deletes, polymorphic traits, and Stripe billing.
 *
 * Demonstrates:
 * - SoftDeletes trait
 * - HasAddresses trait (polymorphic)
 * - HasImages trait (polymorphic)
 * - Query scopes
 * - Multiple relationship types
 * - Laravel Cashier Billable trait for Stripe subscriptions
 *
 * Cashier/Billing Examples (Tinker-friendly):
 * -------------------------------------------
 * // Create a subscription:
 * $user->newSubscription('default', 'price_monthly')->create($paymentMethod);
 *
 * // Check if user is subscribed:
 * $user->subscribed('default');
 *
 * // Get all invoices:
 * $user->invoices();
 *
 * // Download invoice PDF:
 * $user->downloadInvoice($invoiceId);
 *
 * // Cancel subscription:
 * $user->subscription('default')->cancel();
 *
 * // Resume subscription (during grace period):
 * $user->subscription('default')->resume();
 *
 * // Swap to a different plan:
 * $user->subscription('default')->swap('price_yearly');
 *
 * // Add payment method:
 * $user->addPaymentMethod($paymentMethodId);
 *
 * // Update default payment method:
 * $user->updateDefaultPaymentMethod($paymentMethodId);
 *
 * // Redirect to Stripe Customer Portal:
 * $user->redirectToBillingPortal(route('billing.index'));
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable;

    use HasAddresses;
    use HasFactory;
    use HasImages;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'suspended_at',
        'is_premium',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the phone associated with the user (One to One).
     */
    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
    }

    /**
     * Get the posts for the user (One to Many).
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments written by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the orders for the user (One to Many).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the projects for the user (One to Many).
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the tasks assigned to the user (One to Many).
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the roles for the user (Many to Many).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withPivot('assigned_at')->withTimestamps();
    }

    /**
     * Scope to get active (non-suspended) users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('suspended_at');
    }

    /**
     * Scope to get suspended users.
     */
    public function scopeSuspended(Builder $query): Builder
    {
        return $query->whereNotNull('suspended_at');
    }

    /**
     * Scope to get premium users.
     */
    public function scopePremium(Builder $query): Builder
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope to get admin users.
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope to get verified users.
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope to include the user's last order date.
     * Demonstrates subquery selects.
     */
    public function scopeWithLastOrderAt(Builder $query): Builder
    {
        return $query->addSelect(['last_order_at' => Order::select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1),
        ])->withCasts(['last_order_at' => 'datetime']);
    }

    /**
     * Check if the user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Check if the user has premium status.
     */
    public function isPremium(): bool
    {
        return $this->is_premium === true;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
            'is_premium' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }
}
