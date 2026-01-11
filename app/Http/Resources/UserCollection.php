<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Resource Collection for User models with pagination.
 *
 * Demonstrates:
 * - Resource collections with pagination
 * - Adding meta data with with() method
 * - Custom collection transformation
 *
 * Usage:
 *   // With pagination (recommended)
 *   return new UserCollection(User::paginate(15));
 *
 *   // With simple pagination
 *   return new UserCollection(User::simplePaginate(15));
 *
 *   // With cursor pagination
 *   return new UserCollection(User::cursorPaginate(15));
 */
class UserCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = UserResource::class;

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
                'documentation' => url('/api/docs'),
            ],
            'links' => [
                'self' => url('/api/users'),
            ],
        ];
    }
}
