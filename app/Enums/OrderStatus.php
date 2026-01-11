<?php

namespace App\Enums;

/**
 * Order status enum for e-commerce orders.
 *
 * Demonstrates Laravel's native enum casting feature.
 *
 * Usage:
 *   $order->status === OrderStatus::Pending
 *   $order->status->value // 'pending'
 *   OrderStatus::cases() // All cases
 */
enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Get the color associated with the status (for UI).
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Processing => 'blue',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }
}
