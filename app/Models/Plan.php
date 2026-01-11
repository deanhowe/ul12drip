<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Plan model for subscription billing plans.
 *
 * Demonstrates:
 * - Integration with Laravel Cashier / Stripe
 * - Storing Stripe Price IDs for subscription management
 * - Query scopes for filtering plans
 *
 * Usage Examples (Tinker-friendly):
 * ---------------------------------
 * // Get all active plans:
 * Plan::active()->get();
 *
 * // Get monthly plans:
 * Plan::monthly()->get();
 *
 * // Get yearly plans:
 * Plan::yearly()->get();
 *
 * // Find plan by Stripe Price ID:
 * Plan::where('stripe_price_id', 'price_monthly')->first();
 *
 * // Create subscription with a plan:
 * $plan = Plan::where('slug', 'pro-monthly')->first();
 * $user->newSubscription('default', $plan->stripe_price_id)->create($paymentMethod);
 *
 * // Get formatted price:
 * $plan->formatted_price; // "$9.99"
 */
class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'stripe_price_id',
        'price',
        'interval',
        'interval_count',
        'description',
        'features',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'interval_count' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope to get only active plans.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured plans.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get monthly plans.
     */
    public function scopeMonthly(Builder $query): Builder
    {
        return $query->where('interval', 'month');
    }

    /**
     * Scope to get yearly plans.
     */
    public function scopeYearly(Builder $query): Builder
    {
        return $query->where('interval', 'year');
    }

    /**
     * Scope to order plans by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the formatted price (e.g., "$9.99").
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price / 100, 2);
    }

    /**
     * Get the billing interval description (e.g., "per month", "per year").
     */
    public function getIntervalLabelAttribute(): string
    {
        $count = $this->interval_count > 1 ? $this->interval_count.' ' : '';

        return match ($this->interval) {
            'day' => "per {$count}day".($this->interval_count > 1 ? 's' : ''),
            'week' => "per {$count}week".($this->interval_count > 1 ? 's' : ''),
            'month' => "per {$count}month".($this->interval_count > 1 ? 's' : ''),
            'year' => "per {$count}year".($this->interval_count > 1 ? 's' : ''),
            default => '',
        };
    }

    /**
     * Check if this is a monthly plan.
     */
    public function isMonthly(): bool
    {
        return $this->interval === 'month' && $this->interval_count === 1;
    }

    /**
     * Check if this is a yearly plan.
     */
    public function isYearly(): bool
    {
        return $this->interval === 'year' && $this->interval_count === 1;
    }
}
