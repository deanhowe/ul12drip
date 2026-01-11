<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Post model.
 *
 * Demonstrates:
 * - Conditional attributes with when()
 * - Conditional relationships with whenLoaded()
 * - Nested resource relationships
 * - Computed attributes
 *
 * Usage:
 *   // Basic usage
 *   return new PostResource($post);
 *
 *   // With eager loaded relationships
 *   $post = Post::with(['user', 'comments', 'tags'])->find(1);
 *   return new PostResource($post);
 */
class PostResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,

            // Computed attribute - excerpt
            'excerpt' => $this->when(
                strlen($this->body) > 100,
                substr($this->body, 0, 100).'...'
            ),

            // Conditional attribute - published status
            'is_published' => $this->published_at !== null && $this->published_at <= now(),
            'published_at' => $this->when(
                $this->published_at !== null,
                $this->published_at
            ),

            // Conditional relationship - author (user)
            'user' => new UserResource($this->whenLoaded('user')),
            'user_id' => $this->user_id,

            // Conditional relationship - comments (polymorphic)
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'comments_count' => $this->when(
                $this->comments_count !== null,
                $this->comments_count
            ),

            // Conditional relationship - tags (polymorphic many-to-many)
            'tags' => TagResource::collection($this->whenLoaded('tags')),

            // Conditional relationship - images (polymorphic)
            'images' => ImageResource::collection($this->whenLoaded('images')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Soft delete timestamp - only when trashed
            'deleted_at' => $this->when(
                $this->deleted_at !== null,
                $this->deleted_at
            ),
        ];
    }
}
