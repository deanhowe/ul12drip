<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait for models that can have comments.
 *
 * Apply this trait to any model that should support polymorphic comments.
 *
 * Usage:
 *   class Post extends Model
 *   {
 *       use HasComments;
 *   }
 *
 *   $post->comments;
 *   $post->comments()->create(['body' => 'Great post!']);
 */
trait HasComments
{
    /**
     * Get all of the model's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Add a comment to the model.
     */
    public function addComment(string $body, ?int $userId = null): Comment
    {
        return $this->comments()->create([
            'body' => $body,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }

    /**
     * Get the number of comments.
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }
}
