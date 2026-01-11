<?php

namespace App\Models;

use App\Enums\DeploymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Deployment model with enum status cast.
 *
 * Demonstrates:
 * - DeploymentStatus enum casting
 * - Query scopes for deployment status
 * - CI/CD workflow status tracking
 */
class Deployment extends Model
{
    /** @use HasFactory<\Database\Factories\DeploymentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'environment_id',
        'commit_hash',
        'status',
        'deployed_at',
    ];

    /**
     * Get the environment that owns the deployment.
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }

    /**
     * Scope to get pending deployments.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', DeploymentStatus::Pending);
    }

    /**
     * Scope to get running deployments.
     */
    public function scopeRunning(Builder $query): Builder
    {
        return $query->where('status', DeploymentStatus::Running);
    }

    /**
     * Scope to get successful deployments.
     */
    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', DeploymentStatus::Success);
    }

    /**
     * Scope to get failed deployments.
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', DeploymentStatus::Failed);
    }

    /**
     * Scope to get recent deployments.
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Check if the deployment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === DeploymentStatus::Success;
    }

    /**
     * Check if the deployment failed.
     */
    public function isFailed(): bool
    {
        return $this->status === DeploymentStatus::Failed;
    }

    /**
     * Check if the deployment is in a terminal state.
     */
    public function isTerminal(): bool
    {
        return $this->status->isTerminal();
    }

    /**
     * Check if the deployment is still in progress.
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, [DeploymentStatus::Pending, DeploymentStatus::Running]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => DeploymentStatus::class,
            'deployed_at' => 'datetime',
        ];
    }
}
