<?php

namespace App\Jobs;

use App\Models\Podcast;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPodcast implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Podcast $podcast) {}

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new \Illuminate\Queue\Middleware\ThrottlesExceptions(3, 10)];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing podcast: '.$this->podcast->title);
        // Simulate processing
        sleep(1);
        Log::info('Podcast processed: '.$this->podcast->title);
    }
}
