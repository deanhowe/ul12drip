<?php

namespace Tests\Feature;

use App\Jobs\ProcessPodcast;
use App\Jobs\SendWelcomeEmail;
use App\Models\Podcast;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueuesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a job can be dispatched.
     */
    public function test_job_can_be_dispatched(): void
    {
        Queue::fake();

        $podcast = Podcast::factory()->create();

        ProcessPodcast::dispatch($podcast);

        Queue::assertPushed(ProcessPodcast::class, function ($job) use ($podcast) {
            return $job->podcast->id === $podcast->id;
        });
    }

    /**
     * Test job chaining.
     */
    public function test_jobs_can_be_chained(): void
    {
        Bus::fake();

        $user = User::factory()->create();
        $podcast = Podcast::factory()->create();

        Bus::chain([
            new SendWelcomeEmail($user),
            new ProcessPodcast($podcast),
        ])->dispatch();

        Bus::assertChained([
            SendWelcomeEmail::class,
            ProcessPodcast::class,
        ]);
    }

    /**
     * Test job batches.
     */
    public function test_jobs_can_be_batched(): void
    {
        Bus::fake();

        $podcasts = Podcast::factory()->count(3)->create();

        $batch = Bus::batch([
            new ProcessPodcast($podcasts[0]),
            new ProcessPodcast($podcasts[1]),
            new ProcessPodcast($podcasts[2]),
        ])->dispatch();

        Bus::assertBatched(function ($batch) {
            return $batch->jobs->count() === 3;
        });
    }
}
