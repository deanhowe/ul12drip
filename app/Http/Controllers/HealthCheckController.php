<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Invokable controller for application health checks.
 *
 * Demonstrates:
 * - Single-action (invokable) controller pattern
 * - Health check endpoint for monitoring
 * - Database and cache connectivity checks
 *
 * Usage:
 *   GET /health → Returns JSON health status
 */
class HealthCheckController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * Performs health checks on critical application services
     * and returns a JSON response with the status.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
        ];

        $healthy = collect($checks)->every(fn ($check) => $check['status'] === 'ok');

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }

    /**
     * Check database connectivity.
     *
     * @return array{status: string, message: string}
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connectivity.
     *
     * @return array{status: string, message: string}
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_'.uniqid();
            Cache::put($key, true, 10);
            $value = Cache::get($key);
            Cache::forget($key);

            if ($value === true) {
                return [
                    'status' => 'ok',
                    'message' => 'Cache is working',
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Cache read/write failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache check failed: '.$e->getMessage(),
            ];
        }
    }
}
