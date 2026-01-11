<?php

namespace App\Enums;

/**
 * Deployment status enum for CI/CD deployments.
 *
 * Demonstrates Laravel's native enum casting feature.
 *
 * Usage:
 *   $deployment->status === DeploymentStatus::Success
 *   $deployment->status->value // 'success'
 *   DeploymentStatus::cases() // All cases
 */
enum DeploymentStatus: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Success = 'success';
    case Failed = 'failed';

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Running => 'Running',
            self::Success => 'Success',
            self::Failed => 'Failed',
        };
    }

    /**
     * Get the color associated with the status (for UI).
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Running => 'blue',
            self::Success => 'green',
            self::Failed => 'red',
        };
    }

    /**
     * Check if the deployment is in a terminal state.
     */
    public function isTerminal(): bool
    {
        return in_array($this, [self::Success, self::Failed]);
    }

    /**
     * Check if the deployment was successful.
     */
    public function isSuccessful(): bool
    {
        return $this === self::Success;
    }
}
