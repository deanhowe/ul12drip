<?php

namespace App\Services;

use App\Interfaces\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class LogSmsService implements SmsServiceInterface
{
    public function send(string $to, string $message): bool
    {
        Log::info("SMS sent to {$to}: {$message}");

        return true;
    }
}
