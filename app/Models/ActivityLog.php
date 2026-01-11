<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ActivityLog model for audit trail.
 *
 * Demonstrates:
 * - Using a separate database connection (SQLite)
 * - Polymorphic relationships (subject)
 * - JSON casting for properties
 * - Query scopes for filtering
 * - Static helper methods for logging
 *
 * Usage in Tinker:
 *   // Log an activity
 *   ActivityLog::log($user, $post, 'updated', 'Post was updated', ['title' => 'New Title']);
 *
 *   // Query activities
 *   ActivityLog::forSubject($post)->get();
 *   ActivityLog::byUser($user)->recent()->get();
 */
class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasFactory, Prunable;

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(30));
    }

    /**
     * The database connection that should be used by the model.
     */
    protected $connection = 'activity_log';

    /**
     * The table associated with the model.
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'subject_type',
        'subject_id',
        'event',
        'description',
        'properties',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'batch_uuid',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    /**
     * Get the subject of the activity (polymorphic).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed the activity.
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by subject.
     */
    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser(Builder $query, Model $user): Builder
    {
        return $query
            ->where('user_type', $user->getMorphClass())
            ->where('user_id', $user->getKey());
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeOfEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    /**
     * Scope to get recent activities.
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at');
    }

    /**
     * Scope to filter by batch.
     */
    public function scopeInBatch(Builder $query, string $batchUuid): Builder
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    /**
     * Log an activity.
     */
    public static function log(
        ?Model $user,
        Model $subject,
        string $event,
        ?string $description = null,
        ?array $properties = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $batchUuid = null
    ): self {
        return static::create([
            'user_id' => $user?->getKey(),
            'user_type' => $user?->getMorphClass(),
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'event' => $event,
            'description' => $description,
            'properties' => $properties,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'url' => request()?->fullUrl(),
            'batch_uuid' => $batchUuid,
        ]);
    }

    /**
     * Log a model creation.
     */
    public static function logCreated(?Model $user, Model $subject, ?string $description = null): self
    {
        return static::log(
            $user,
            $subject,
            'created',
            $description ?? class_basename($subject).' was created',
            $subject->toArray()
        );
    }

    /**
     * Log a model update.
     */
    public static function logUpdated(?Model $user, Model $subject, ?string $description = null): self
    {
        return static::log(
            $user,
            $subject,
            'updated',
            $description ?? class_basename($subject).' was updated',
            null,
            $subject->getOriginal(),
            $subject->getChanges()
        );
    }

    /**
     * Log a model deletion.
     */
    public static function logDeleted(?Model $user, Model $subject, ?string $description = null): self
    {
        return static::log(
            $user,
            $subject,
            'deleted',
            $description ?? class_basename($subject).' was deleted',
            $subject->toArray()
        );
    }
}
