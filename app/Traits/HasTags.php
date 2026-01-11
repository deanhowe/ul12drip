<?php

namespace App\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Trait for models that can be tagged.
 *
 * Apply this trait to any model that should support polymorphic many-to-many tags.
 *
 * Usage:
 *   class Post extends Model
 *   {
 *       use HasTags;
 *   }
 *
 *   $post->tags;
 *   $post->attachTags(['laravel', 'php']);
 *   $post->syncTags([1, 2, 3]);
 */
trait HasTags
{
    /**
     * Get all of the tags for the model.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Attach tags to the model by name or ID.
     *
     * @param  array<int|string>  $tags
     */
    public function attachTags(array $tags): void
    {
        $tagIds = $this->resolveTagIds($tags);
        $this->tags()->syncWithoutDetaching($tagIds);
    }

    /**
     * Detach tags from the model by name or ID.
     *
     * @param  array<int|string>  $tags
     */
    public function detachTags(array $tags): void
    {
        $tagIds = $this->resolveTagIds($tags);
        $this->tags()->detach($tagIds);
    }

    /**
     * Sync tags on the model (replaces existing tags).
     *
     * @param  array<int|string>  $tags
     */
    public function syncTags(array $tags): void
    {
        $tagIds = $this->resolveTagIds($tags);
        $this->tags()->sync($tagIds);
    }

    /**
     * Check if the model has a specific tag.
     */
    public function hasTag(int|string $tag): bool
    {
        if (is_int($tag)) {
            return $this->tags()->where('tags.id', $tag)->exists();
        }

        return $this->tags()->where('tags.name', $tag)->exists();
    }

    /**
     * Resolve tag names to IDs, creating tags if they don't exist.
     *
     * @param  array<int|string>  $tags
     * @return Collection<int, int>
     */
    protected function resolveTagIds(array $tags): Collection
    {
        return collect($tags)->map(function ($tag) {
            if (is_int($tag)) {
                return $tag;
            }

            return Tag::firstOrCreate(
                ['slug' => str($tag)->slug()],
                ['name' => $tag]
            )->id;
        });
    }
}
