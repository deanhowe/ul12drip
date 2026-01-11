<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Comment model (polymorphic).
 *
 * Demonstrates:
 * - Polymorphic relationship handling
 * - Conditional relationships with whenLoaded()
 */
class CommentResource extends JsonResource
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
            'body' => $this->body,

            // Polymorphic type info
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,

            // Conditional relationship - author
            'user' => new UserResource($this->whenLoaded('user')),
            'user_id' => $this->user_id,

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
