<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Resource Collection for Post models with pagination.
 *
 * Demonstrates:
 * - Resource collections with pagination
 * - Adding meta data with with() method
 * - Statistics in meta data
 *
 * Usage:
 *   return new PostCollection(Post::published()->paginate(15));
 */
class PostCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = PostResource::class;

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
                'total_published' => \App\Models\Post::published()->count(),
                'total_draft' => \App\Models\Post::draft()->count(),
            ],
            'links' => [
                'self' => url('/api/posts'),
            ],
        ];
    }
}
