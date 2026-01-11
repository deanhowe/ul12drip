<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for Post resources.
 *
 * Demonstrates:
 * - API Resource responses
 * - Resource Collections with pagination
 * - Form Request validation
 * - Eager loading relationships
 * - Query scopes in API
 */
class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * GET /api/posts
     * GET /api/posts?status=published
     * GET /api/posts?per_page=25
     */
    public function index(Request $request): PostCollection
    {
        $query = Post::query()
            ->with(['user', 'tags'])
            ->withCount('comments');

        // Filter by status
        if ($request->has('status')) {
            match ($request->status) {
                'published' => $query->published(),
                'draft' => $query->draft(),
                'scheduled' => $query->scheduled(),
                default => null,
            };
        }

        // Default to recent posts
        $query->recent(30);

        $perPage = $request->input('per_page', 15);

        return new PostCollection($query->paginate($perPage));
    }

    /**
     * Store a newly created post.
     *
     * POST /api/posts
     */
    public function store(StorePostRequest $request): PostResource
    {
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'published_at' => $request->published_at,
            'user_id' => $request->user()->id,
        ]);

        // Sync tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        $post->load(['user', 'tags']);

        return new PostResource($post);
    }

    /**
     * Display the specified post.
     *
     * GET /api/posts/{post}
     */
    public function show(Post $post): PostResource
    {
        $post->load(['user', 'comments.user', 'tags', 'images']);
        $post->loadCount('comments');

        return new PostResource($post);
    }

    /**
     * Update the specified post.
     *
     * PUT/PATCH /api/posts/{post}
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post->update($request->validated());

        // Sync tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        $post->load(['user', 'tags']);

        return new PostResource($post);
    }

    /**
     * Remove the specified post.
     *
     * DELETE /api/posts/{post}
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}
