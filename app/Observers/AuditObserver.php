<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Generic Audit Observer for tracking model changes.
 *
 * This observer can be registered for any model that needs automatic
 * activity logging for created, updated, and deleted events.
 */
class AuditObserver
{
    /**
     * Handle the model "created" event.
     */
    public function created(Model $model): void
    {
        ActivityLog::logCreated(Auth::user(), $model);
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(Model $model): void
    {
        ActivityLog::logUpdated(Auth::user(), $model);
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        ActivityLog::logDeleted(Auth::user(), $model);
    }
}
