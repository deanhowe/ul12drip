<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Observer for Post model events.
 *
 * Demonstrates:
 * - Model event handling (creating, created, updating, etc.)
 * - Automatic slug generation
 * - Audit logging
 * - Cache invalidation patterns
 */
class PostObserver
{
    /**
     * Handle the Post "creating" event.
     * Called before the post is saved to the database.
     */
    public function creating(Post $post): void
    {
        // Generate slug from title if not provided
        if (empty($post->slug)) {
            $post->slug = Str::slug($post->title);
        }

        // Ensure unique slug
        $originalSlug = $post->slug;
        $count = 1;
        while (Post::where('slug', $post->slug)->exists()) {
            $post->slug = $originalSlug.'-'.$count++;
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        Log::info('Post created', [
            'post_id' => $post->id,
            'title' => $post->title,
            'user_id' => $post->user_id,
            'published' => $post->published_at !== null,
        ]);

        ActivityLog::logCreated(Auth::user(), $post, 'Post created via observer');
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        // Regenerate slug if title changed
        if ($post->isDirty('title')) {
            $post->slug = Str::slug($post->title);

            // Ensure unique slug (excluding current post)
            $originalSlug = $post->slug;
            $count = 1;
            while (Post::where('slug', $post->slug)->where('id', '!=', $post->id)->exists()) {
                $post->slug = $originalSlug.'-'.$count++;
            }
        }

        // Log publishing event
        if ($post->isDirty('published_at') && $post->published_at !== null) {
            Log::info('Post being published', [
                'post_id' => $post->id,
                'title' => $post->title,
                'published_at' => $post->published_at,
            ]);

            ActivityLog::log(Auth::user(), $post, 'publishing', 'Post is being published');
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        Log::info('Post updated', [
            'post_id' => $post->id,
            'title' => $post->title,
            'changes' => $post->getChanges(),
        ]);

        ActivityLog::logUpdated(Auth::user(), $post);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        Log::info('Post deleted', [
            'post_id' => $post->id,
            'title' => $post->title,
            'deleted_at' => now()->toDateTimeString(),
        ]);

        ActivityLog::logDeleted(Auth::user(), $post);
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        Log::info('Post restored', [
            'post_id' => $post->id,
            'title' => $post->title,
            'restored_at' => now()->toDateTimeString(),
        ]);

        ActivityLog::log(Auth::user(), $post, 'restored', 'Post was restored');
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        Log::warning('Post permanently deleted', [
            'post_id' => $post->id,
            'title' => $post->title,
        ]);

        ActivityLog::log(Auth::user(), $post, 'force_deleted', 'Post was permanently deleted');
    }
}
