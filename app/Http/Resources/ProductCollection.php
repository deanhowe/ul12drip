<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Resource Collection for Product models with pagination.
 *
 * Demonstrates:
 * - Resource collections with pagination
 * - Adding meta data with with() method
 * - Inventory statistics in meta data
 *
 * Usage:
 *   return new ProductCollection(Product::active()->paginate(15));
 */
class ProductCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ProductResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'api_version' => '1.0',
                'total_active' => \App\Models\Product::active()->count(),
                'total_on_sale' => \App\Models\Product::onSale()->count(),
                'total_out_of_stock' => \App\Models\Product::outOfStock()->count(),
            ],
            'links' => [
                'self' => url('/api/products'),
            ],
        ];
    }
}
