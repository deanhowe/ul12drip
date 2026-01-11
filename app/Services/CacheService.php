<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Service demonstrating Laravel caching patterns.
 *
 * Demonstrates:
 * - Basic cache operations (get, put, forget)
 * - Cache tags for grouped invalidation
 * - Remember pattern for lazy loading
 * - Cache locks for race condition prevention
 * - TTL (Time To Live) strategies
 *
 * Usage in Tinker:
 *   $service = new \App\Services\CacheService();
 *   $service->getPopularPosts();
 *   $service->getUserStats(1);
 *   $service->clearUserCache(1);
 */
class CacheService
{
    /**
     * Cache TTL constants (in seconds).
     */
    private const TTL_SHORT = 300;      // 5 minutes

    private const TTL_MEDIUM = 3600;    // 1 hour

    private const TTL_LONG = 86400;     // 24 hours

    /**
     * Get popular posts with caching.
     *
     * Demonstrates: remember() pattern for lazy loading
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPopularPosts(int $limit = 10)
    {
        return Cache::remember(
            "posts:popular:{$limit}",
            self::TTL_MEDIUM,
            fn () => Post::query()
                ->published()
                ->withCount('comments')
                ->orderByDesc('comments_count')
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Get featured products with cache tags.
     *
     * Demonstrates: Cache tags for grouped invalidation
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedProducts(int $limit = 8)
    {
        return Cache::tags(['products', 'featured'])->remember(
            "products:featured:{$limit}",
            self::TTL_LONG,
            fn () => Product::query()
                ->active()
                ->onSale()
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Get user statistics with caching.
     *
     * Demonstrates: User-specific cache with tags
     */
    public function getUserStats(int $userId): array
    {
        return Cache::tags(['users', "user:{$userId}"])->remember(
            "user:{$userId}:stats",
            self::TTL_SHORT,
            function () use ($userId) {
                $user = User::withCount(['posts', 'orders'])->find($userId);

                if (! $user) {
                    return [];
                }

                return [
                    'posts_count' => $user->posts_count,
                    'orders_count' => $user->orders_count,
                    'is_premium' => $user->is_premium,
                    'member_since' => $user->created_at->diffForHumans(),
                ];
            }
        );
    }

    /**
     * Get or set a value with atomic lock.
     *
     * Demonstrates: Cache locks for race condition prevention
     */
    public function getWithLock(string $key, callable $callback, int $ttl = 10)
    {
        $lock = Cache::lock("lock:{$key}", $ttl);

        if ($lock->get()) {
            try {
                $value = Cache::get($key);

                if ($value === null) {
                    $value = $callback();
                    Cache::put($key, $value, self::TTL_MEDIUM);
                }

                return $value;
            } finally {
                $lock->release();
            }
        }

        // If lock not acquired, wait and retry
        return Cache::get($key) ?? $lock->block(5, function () use ($key, $callback) {
            $value = Cache::get($key);

            if ($value === null) {
                $value = $callback();
                Cache::put($key, $value, self::TTL_MEDIUM);
            }

            return $value;
        });
    }

    /**
     * Increment a counter atomically.
     *
     * Demonstrates: Atomic increment operations
     */
    public function incrementViewCount(string $type, int $id): int
    {
        $key = "{$type}:{$id}:views";

        return Cache::increment($key);
    }

    /**
     * Clear all caches for a specific user.
     *
     * Demonstrates: Tag-based cache invalidation
     */
    public function clearUserCache(int $userId): bool
    {
        return Cache::tags(["user:{$userId}"])->flush();
    }

    /**
     * Clear all product caches.
     *
     * Demonstrates: Bulk cache invalidation by tag
     */
    public function clearProductCache(): bool
    {
        return Cache::tags(['products'])->flush();
    }

    /**
     * Clear specific cache key.
     *
     * Demonstrates: Single key invalidation
     */
    public function clearCache(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Check if a value exists in cache.
     *
     * Demonstrates: Cache existence check
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * Store a value forever (until manually cleared).
     *
     * Demonstrates: Permanent cache storage
     */
    public function storeForever(string $key, mixed $value): bool
    {
        return Cache::forever($key, $value);
    }

    /**
     * Get multiple values at once.
     *
     * Demonstrates: Batch cache retrieval
     *
     * @param  array<string>  $keys
     */
    public function getMany(array $keys): array
    {
        return Cache::many($keys);
    }

    /**
     * Store multiple values at once.
     *
     * Demonstrates: Batch cache storage
     *
     * @param  array<string, mixed>  $values
     */
    public function putMany(array $values, ?int $ttl = null): bool
    {
        return Cache::putMany($values, $ttl ?? self::TTL_MEDIUM);
    }
}
