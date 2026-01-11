<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\HasImages;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Product model with soft deletes and polymorphic traits.
 *
 * Demonstrates:
 * - SoftDeletes trait
 * - Commentable trait (polymorphic comments)
 * - Taggable trait (polymorphic many-to-many)
 * - HasImages trait (polymorphic images)
 * - Query scopes for active/inactive/in-stock
 */
#[CollectedBy(\App\Collections\ProductCollection::class)]
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use Commentable;

    use HasFactory;
    use HasImages;
    use SoftDeletes;
    use Taggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'active',
        'metadata',
    ];

    /**
     * Get the categories for the product (Many to Many).
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Scope to get active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get inactive products.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('active', false);
    }

    /**
     * Scope to get products in stock.
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope to get products out of stock.
     */
    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Scope to get products on sale.
     */
    public function scopeOnSale(Builder $query): Builder
    {
        return $query->whereNotNull('sale_price');
    }

    /**
     * Check if the product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if the product is on sale.
     */
    public function isOnSale(): bool
    {
        return $this->sale_price !== null;
    }

    /**
     * Get the current price (sale price if on sale, otherwise regular price).
     */
    public function getCurrentPriceAttribute(): string
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'active' => 'boolean',
            'metadata' => AsArrayObject::class,
        ];
    }
}
