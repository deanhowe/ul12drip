<?php

namespace App\Models;

use App\Traits\HasComments;
use App\Traits\HasImages;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * Post model with soft deletes and polymorphic traits.
 *
 * Demonstrates:
 * - SoftDeletes trait
 * - Searchable trait (Laravel Scout)
 * - HasComments trait (polymorphic comments)
 * - HasTags trait (polymorphic many-to-many)
 * - HasImages trait (polymorphic images)
 * - Query scopes for published/draft/recent
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasComments;

    use HasFactory;
    use HasImages;
    use HasTags;
    use Searchable;
    use SoftDeletes;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Models\Scopes\PublishedScope);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'published_at',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to get draft posts (not published).
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    /**
     * Scope to get scheduled posts (published_at in the future).
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    /**
     * Scope to get recent posts.
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Check if the post is published.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at <= now();
    }

    /**
     * Check if the post is a draft.
     */
    public function isDraft(): bool
    {
        return $this->published_at === null;
    }

    /**
     * Check if the post is scheduled for future publication.
     */
    public function isScheduled(): bool
    {
        return $this->published_at !== null && $this->published_at > now();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
