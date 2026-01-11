<?php

namespace App\Interfaces;

interface SmsServiceInterface
{
    public function send(string $to, string $message): bool;
}
