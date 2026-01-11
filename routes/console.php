<?php

use App\Jobs\HeartbeatJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('inspire')->hourly();
Schedule::job(new HeartbeatJob)->everyMinute();
Schedule::command('model:prune')->daily();
