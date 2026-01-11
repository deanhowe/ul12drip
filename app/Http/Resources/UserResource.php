<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for User model.
 *
 * Demonstrates:
 * - Conditional attributes with when()
 * - Conditional relationships with whenLoaded()
 * - Merging conditional attributes with mergeWhen()
 * - Nested resource relationships
 *
 * Usage:
 *   // Basic usage
 *   return new UserResource($user);
 *
 *   // With eager loaded relationships
 *   $user = User::with(['posts', 'orders', 'roles'])->find(1);
 *   return new UserResource($user);
 *
 *   // Collection
 *   return UserResource::collection(User::paginate());
 */
class UserResource extends JsonResource
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
            'email' => $this->email,

            // Conditional attribute - only include if user is verified
            'email_verified_at' => $this->when(
                $this->email_verified_at !== null,
                $this->email_verified_at
            ),

            // Conditional attributes based on request user permissions
            $this->mergeWhen($request->user()?->is_admin ?? false, [
                'is_admin' => $this->is_admin,
                'is_premium' => $this->is_premium,
                'suspended_at' => $this->suspended_at,
            ]),

            // Conditional relationship - only included if loaded
            'posts' => PostResource::collection($this->whenLoaded('posts')),

            // Conditional relationship with count
            'posts_count' => $this->when(
                $this->posts_count !== null,
                $this->posts_count
            ),

            // Conditional relationship - orders
            'orders' => OrderResource::collection($this->whenLoaded('orders')),

            // Conditional relationship - roles (many-to-many)
            'roles' => RoleResource::collection($this->whenLoaded('roles')),

            // Conditional relationship - phone (one-to-one)
            'phone' => new PhoneResource($this->whenLoaded('phone')),

            // Conditional relationship - address (polymorphic)
            'address' => new AddressResource($this->whenLoaded('address')),

            // Conditional relationship - images (polymorphic many)
            'images' => ImageResource::collection($this->whenLoaded('images')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Conditional - only show deleted_at for soft deleted models when trashed
            'deleted_at' => $this->when(
                $this->deleted_at !== null,
                $this->deleted_at
            ),
        ];
    }
}
