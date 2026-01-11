<?php
use function Laravel\Folio\name;
use App\Models\ExhaustiveFeature;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

name('exhaustive');
?>

<x-app-layout>
    <x-slot name="title">Exhaustive Features Showcase</x-slot>

    <div class="space-y-12">
        <header>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Exhaustive Features Showcase</h1>
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                This page demonstrates that the application implements EVERY available Laravel schema column type, validation rule, and core framework utility.
            </p>
        </header>

        <!-- Database Schema Section -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3M4 12c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3"/></svg>
                    Exhaustive Database Schema
                </h2>
                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ DB::getDriverName() }} driver
                </span>
            </div>
            <p class="text-gray-600 dark:text-gray-400">
                The <code class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded">exhaustive_features</code> table contains ALL supported Laravel column types.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $columns = Schema::getColumnListing('exhaustive_features');
                    sort($columns);
                @endphp
                @foreach($columns as $column)
                    <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex justify-between items-center shadow-sm">
                        <span class="font-mono text-sm text-indigo-600 dark:text-indigo-400">{{ $column }}</span>
                        <span class="text-xs text-gray-400 uppercase font-bold">{{ Schema::getColumnType('exhaustive_features', $column) }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Validation Rules Section -->
        <section class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Exhaustive Validation Rules
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                The <code class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded">StoreExhaustiveValidationRequest</code> implements examples for EVERY built-in validation rule.
            </p>

            <div class="bg-gray-900 rounded-xl p-6 overflow-x-auto shadow-inner border border-gray-800">
                <pre class="text-indigo-300 text-sm"><code>// app/Http/Requests/StoreExhaustiveValidationRequest.php
public function rules(): array
{
    return [
        'accepted_field' => 'accepted',
        'boolean_field' => 'boolean',
        'email_field' => 'email:rfc,dns',
        'enum_field' => [Rule::enum(OrderStatus::class)],
        'ip_field' => 'ip',
        'json_field' => 'json',
        'max_field' => 'max:255',
        'regex_field' => 'regex:/^[a-z]+$/i',
        'date_format_field' => 'date_format:Y-m-d',
        'dimensions_field' => 'dimensions:min_width=100,min_height=200',
        'exists_field' => 'exists:users,id',
        // ... and 80+ other rules
    ];
}</code></pre>
            </div>
        </section>

        <!-- Core Framework Section -->
        <section class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Core Framework Power
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Hashing & Encryption</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Native support for secure password hashing and AES-256 encryption.</p>
                    <div class="space-y-2">
                        <code class="block text-xs p-2 bg-gray-50 dark:bg-gray-900 rounded text-indigo-600 dark:text-indigo-400 font-mono">Hash::make('secret');</code>
                        <code class="block text-xs p-2 bg-gray-50 dark:bg-gray-900 rounded text-indigo-600 dark:text-indigo-400 font-mono">Crypt::encryptString('sensitive data');</code>
                    </div>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Session & Cache</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Seamless session management and high-performance caching drivers.</p>
                    <div class="space-y-2">
                        <code class="block text-xs p-2 bg-gray-50 dark:bg-gray-900 rounded text-indigo-600 dark:text-indigo-400 font-mono">Session::put('key', 'value');</code>
                        <code class="block text-xs p-2 bg-gray-50 dark:bg-gray-900 rounded text-indigo-600 dark:text-indigo-400 font-mono">Cache::remember('key', $seconds, fn() => ...);</code>
                    </div>
                </div>
            </div>
        </section>

        <footer class="pt-8 border-t border-gray-200 dark:border-gray-700 text-center">
            <p class="text-gray-500 dark:text-gray-500 font-medium italic">
                This application serves as the ultimate, exhaustive Laravel 12 Reference.
            </p>
        </footer>
    </div>
</x-app-layout>
