<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Task model with enum casts for status and priority.
 *
 * Demonstrates:
 * - Multiple enum casts (TaskStatus, TaskPriority)
 * - Query scopes for filtering by status/priority
 * - Overdue task detection
 */
class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * Get the project that owns the task.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user assigned to the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get pending tasks.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Pending);
    }

    /**
     * Scope to get in-progress tasks.
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::InProgress);
    }

    /**
     * Scope to get completed tasks.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Completed);
    }

    /**
     * Scope to get high priority tasks.
     */
    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', TaskPriority::High);
    }

    /**
     * Scope to get medium priority tasks.
     */
    public function scopeMediumPriority(Builder $query): Builder
    {
        return $query->where('priority', TaskPriority::Medium);
    }

    /**
     * Scope to get low priority tasks.
     */
    public function scopeLowPriority(Builder $query): Builder
    {
        return $query->where('priority', TaskPriority::Low);
    }

    /**
     * Scope to get overdue tasks.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', TaskStatus::Completed);
    }

    /**
     * Scope to get tasks due soon (within specified days).
     */
    public function scopeDueSoon(Builder $query, int $days = 3): Builder
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)])
            ->where('status', '!=', TaskStatus::Completed);
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->due_date < now()
            && $this->status !== TaskStatus::Completed;
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === TaskStatus::Completed;
    }

    /**
     * Check if the task is high priority.
     */
    public function isHighPriority(): bool
    {
        return $this->priority === TaskPriority::High;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'due_date' => 'datetime',
        ];
    }
}
