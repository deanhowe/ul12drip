<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Observer for Order model events.
 *
 * Demonstrates:
 * - Model event handling (created, updated, deleted, etc.)
 * - Status change tracking
 * - Audit logging
 * - Automatic timestamp updates
 */
class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     * Called before the order is saved to the database.
     */
    public function creating(Order $order): void
    {
        // Generate order number if not set
        if (empty($order->order_number)) {
            $order->order_number = 'ORD-'.strtoupper(uniqid());
        }
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        Log::info('Order created', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'user_id' => $order->user_id,
            'total' => $order->total,
            'status' => $order->status->value ?? $order->status,
        ]);

        ActivityLog::logCreated(Auth::user(), $order, 'Order '.$order->order_number.' created');
    }

    /**
     * Handle the Order "updating" event.
     * Called before the order is updated in the database.
     */
    public function updating(Order $order): void
    {
        // Track status changes
        if ($order->isDirty('status')) {
            $order->status_changed_at = now();

            Log::info('Order status changing', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $order->status->value ?? $order->status,
            ]);

            $oldStatus = $order->getOriginal('status');
            $oldStatusValue = $oldStatus instanceof \BackedEnum ? $oldStatus->value : $oldStatus;
            $newStatusValue = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;

            ActivityLog::log(
                Auth::user(),
                $order,
                'status_changing',
                "Order status changing from {$oldStatusValue} to {$newStatusValue}",
                ['old_status' => $oldStatusValue, 'new_status' => $newStatusValue]
            );
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Log when order is completed
        if ($order->wasChanged('status') && $order->status === OrderStatus::Completed) {
            Log::info('Order completed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'completed_at' => now()->toDateTimeString(),
            ]);

            ActivityLog::log(Auth::user(), $order, 'completed', 'Order '.$order->order_number.' completed');
        }

        // Log when order is cancelled
        if ($order->wasChanged('status') && $order->status === OrderStatus::Cancelled) {
            Log::info('Order cancelled', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'cancelled_at' => now()->toDateTimeString(),
            ]);

            ActivityLog::log(Auth::user(), $order, 'cancelled', 'Order '.$order->order_number.' cancelled');
        }

        ActivityLog::logUpdated(Auth::user(), $order);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        Log::info('Order deleted', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'deleted_at' => now()->toDateTimeString(),
        ]);

        ActivityLog::logDeleted(Auth::user(), $order);
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        Log::info('Order restored', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'restored_at' => now()->toDateTimeString(),
        ]);

        ActivityLog::log(Auth::user(), $order, 'restored', 'Order '.$order->order_number.' restored');
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        Log::warning('Order permanently deleted', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        ActivityLog::log(Auth::user(), $order, 'force_deleted', 'Order '.$order->order_number.' permanently deleted');
    }
}
