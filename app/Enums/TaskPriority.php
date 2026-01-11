<?php

namespace App\Enums;

/**
 * Task priority enum for project tasks.
 *
 * Demonstrates Laravel's native enum casting feature.
 *
 * Usage:
 *   $task->priority === TaskPriority::High
 *   $task->priority->value // 'high'
 *   TaskPriority::cases() // All cases
 */
enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    /**
     * Get a human-readable label for the priority.
     */
    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
        };
    }

    /**
     * Get the color associated with the priority (for UI).
     */
    public function color(): string
    {
        return match ($this) {
            self::Low => 'gray',
            self::Medium => 'yellow',
            self::High => 'red',
        };
    }

    /**
     * Get the sort order for the priority (higher = more urgent).
     */
    public function sortOrder(): int
    {
        return match ($this) {
            self::Low => 1,
            self::Medium => 2,
            self::High => 3,
        };
    }
}
