<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class UpdateInventory
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Log::info('Updating inventory for order: '.$event->order->order_number);
    }
}
