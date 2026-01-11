<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExhaustiveValidationRequest;
use App\Models\ExhaustiveFeature;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ExhaustiveFeatureController extends Controller
{
    /**
     * Demonstrate exhaustive validation.
     */
    public function validateRequest(StoreExhaustiveValidationRequest $request)
    {
        return response()->json([
            'message' => 'Validation passed!',
            'data' => $request->validated(),
        ]);
    }

    /**
     * Demonstrate various Laravel core features.
     */
    public function demonstrateCoreFeatures()
    {
        // Benchmark (New-ish in L10/11)
        $benchmark = Benchmark::measure([
            'hashing' => fn () => Hash::make('password'),
            'encryption' => fn () => Crypt::encryptString('Secret'),
        ]);

        // Hashing
        $hashed = Hash::make('password');
        $check = Hash::check('password', $hashed);

        // Encryption
        $encrypted = Crypt::encryptString('Secret message');
        $decrypted = Crypt::decryptString($encrypted);

        // Session
        Session::put('key', 'value');
        $sessionValue = Session::get('key');
        Session::flash('status', 'Task completed!');

        // Cache
        Cache::put('cache_key', 'cache_value', now()->addMinutes(10));
        $cacheValue = Cache::remember('remember_key', 60, fn () => 'expensive_data');

        // Cookies
        Cookie::queue('cookie_name', 'cookie_value', 60);

        // Database Transactions
        DB::transaction(function () {
            // Ensure a user exists for the foreign key
            $user = \App\Models\User::first() ?: \App\Models\User::factory()->create();

            // Perform multiple database operations
            ExhaustiveFeature::create([
                'user_id' => $user->id,
                'string_col' => 'Transaction Demo',
            ]);
        });

        // Signed URLs
        $signedUrl = URL::signedRoute('api.demonstrate-core', ['user' => 1]);
        $temporarySignedUrl = URL::temporarySignedRoute('api.demonstrate-core', now()->addMinutes(30), ['user' => 1]);

        // Logging with custom channel and context
        Log::channel('custom_showcase')->info('Exhaustive features demonstrated', [
            'hash_check' => $check,
            'decrypted' => $decrypted,
            'session' => $sessionValue,
            'cache' => $cacheValue,
            'signed_url' => $signedUrl,
        ]);

        return response()->json([
            'benchmark' => $benchmark,
            'hashing' => $check,
            'encryption' => $decrypted,
            'session' => $sessionValue,
            'cache' => $cacheValue,
            'cookie' => 'Queued',
            'transaction' => 'Success',
            'urls' => [
                'signed' => $signedUrl,
                'temporary_signed' => $temporarySignedUrl,
            ],
        ]);
    }
}
