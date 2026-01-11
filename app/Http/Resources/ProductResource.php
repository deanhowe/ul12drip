<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Product model.
 *
 * Demonstrates:
 * - Conditional attributes with when()
 * - Conditional relationships with whenLoaded()
 * - Computed attributes (on_sale, discount_percentage)
 * - Currency formatting
 *
 * Usage:
 *   return new ProductResource($product);
 *   return new ProductResource(Product::with(['categories', 'tags'])->find(1));
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,

            // Pricing with formatting
            'price' => $this->price,
            'price_formatted' => '$'.number_format($this->price, 2),

            // Conditional sale price
            'sale_price' => $this->when(
                $this->sale_price !== null,
                $this->sale_price
            ),
            'sale_price_formatted' => $this->when(
                $this->sale_price !== null,
                '$'.number_format($this->sale_price ?? 0, 2)
            ),

            // Computed attributes
            'on_sale' => $this->sale_price !== null && $this->sale_price < $this->price,
            'discount_percentage' => $this->when(
                $this->sale_price !== null && $this->price > 0,
                fn () => round((($this->price - $this->sale_price) / $this->price) * 100)
            ),

            // Stock information
            'stock' => $this->stock,
            'in_stock' => $this->stock > 0,
            'low_stock' => $this->stock > 0 && $this->stock <= 5,

            // Status
            'active' => $this->active ?? true,

            // Conditional relationship - categories
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),

            // Conditional relationship - tags (polymorphic)
            'tags' => TagResource::collection($this->whenLoaded('tags')),

            // Conditional relationship - images (polymorphic)
            'images' => ImageResource::collection($this->whenLoaded('images')),

            // Conditional relationship - comments (polymorphic)
            'comments' => CommentResource::collection($this->whenLoaded('comments')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Soft delete
            'deleted_at' => $this->when(
                $this->deleted_at !== null,
                $this->deleted_at
            ),
        ];
    }
}
