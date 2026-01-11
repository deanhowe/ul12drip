<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait for models that can have images.
 *
 * Apply this trait to any model that should support polymorphic images.
 *
 * Usage:
 *   class Product extends Model
 *   {
 *       use HasImages;
 *   }
 *
 *   $product->images;
 *   $product->addImage('https://example.com/image.jpg', 'Product photo');
 */
trait HasImages
{
    /**
     * Get all of the model's images.
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('order');
    }

    /**
     * Add an image to the model.
     */
    public function addImage(string $url, ?string $alt = null, int $order = 0): Image
    {
        return $this->images()->create([
            'url' => $url,
            'alt' => $alt,
            'order' => $order,
        ]);
    }

    /**
     * Get the primary (first) image.
     */
    public function getPrimaryImageAttribute(): ?Image
    {
        return $this->images()->first();
    }

    /**
     * Get the primary image URL or a default.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->primaryImage?->url;
    }

    /**
     * Check if the model has any images.
     */
    public function hasImages(): bool
    {
        return $this->images()->exists();
    }
}
