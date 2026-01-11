<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class ShellService
{
    /**
     * Execute a simple shell command.
     */
    public function getSystemDate(): string
    {
        $result = Process::run('date');

        return trim($result->output());
    }

    /**
     * Execute a command and handle failure.
     */
    public function runCustomCommand(string $command): string
    {
        $result = Process::run($command);

        if ($result->successful()) {
            return $result->output();
        }

        return 'Error: '.$result->errorOutput();
    }
}
