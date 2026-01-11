<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Order model.
 *
 * Demonstrates:
 * - Enum value transformation
 * - Conditional attributes with when()
 * - Conditional relationships with whenLoaded()
 * - Formatted currency values
 *
 * Usage:
 *   return new OrderResource($order);
 *   return new OrderResource(Order::with(['user', 'products'])->find(1));
 */
class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,

            // Enum status with value and label
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),

            // Currency formatting
            'total' => $this->total,
            'total_formatted' => '$'.number_format($this->total, 2),

            // Conditional - status change timestamp
            'status_changed_at' => $this->when(
                $this->status_changed_at !== null,
                $this->status_changed_at
            ),

            // Conditional relationship - customer (user)
            'user' => new UserResource($this->whenLoaded('user')),
            'user_id' => $this->user_id,

            // Conditional relationship - products (many-to-many with pivot)
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'products_count' => $this->when(
                $this->products_count !== null,
                $this->products_count
            ),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Soft delete timestamp
            'deleted_at' => $this->when(
                $this->deleted_at !== null,
                $this->deleted_at
            ),
        ];
    }
}
