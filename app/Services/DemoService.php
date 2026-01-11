<?php

namespace App\Services;

use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;

class DemoService
{
    /**
     * Demonstrate Concurrency.
     */
    public function runParallelTasks(): array
    {
        return Concurrency::run([
            fn () => 'Task 1 Result',
            fn () => 'Task 2 Result',
            fn () => 'Task 3 Result',
        ]);
    }

    /**
     * Demonstrate Context.
     */
    public function demonstrateContext(): void
    {
        Context::add('request_id', (string) str()->uuid());
        Context::add('user_ip', '127.0.0.1');

        Log::info('Performing an action with context');

        // Context is automatically included in logs if configured
    }
}
