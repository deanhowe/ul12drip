<?php

namespace App\Enums;

/**
 * Task status enum for project tasks.
 *
 * Demonstrates Laravel's native enum casting feature.
 *
 * Usage:
 *   $task->status === TaskStatus::InProgress
 *   $task->status->value // 'in_progress'
 *   TaskStatus::cases() // All cases
 */
enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }

    /**
     * Get the color associated with the status (for UI).
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::InProgress => 'blue',
            self::Completed => 'green',
        };
    }

    /**
     * Check if the task is considered active (not completed).
     */
    public function isActive(): bool
    {
        return $this !== self::Completed;
    }
}
