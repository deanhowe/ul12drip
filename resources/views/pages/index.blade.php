<?php

use function Laravel\Folio\name;

name('home');

?>

<x-app-layout title="Home">
    {{-- Hero Section --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Laravel Documentation Demo App</h1>
        <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
            A comprehensive demonstration of Laravel's Eloquent ORM, relationships, factories, seeders,
            API resources, and Pennant feature flags — all from the official documentation.
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="text-3xl font-bold text-[#f53003]">26+</div>
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Eloquent Models</div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="text-3xl font-bold text-[#f53003]">15+</div>
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Relationship Types</div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="text-3xl font-bold text-[#f53003]">128</div>
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Passing Tests</div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="text-3xl font-bold text-[#f53003]">8</div>
            <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Pennant Features</div>
        </div>
    </div>

    {{-- Feature Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        {{-- Eloquent Relationships --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="w-10 h-10 bg-[#f53003]/10 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-2">Eloquent Relationships</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                All relationship types: hasOne, hasMany, belongsTo, belongsToMany, hasOneThrough, hasManyThrough, morphOne, morphMany, morphToMany.
            </p>
            <a href="{{ route('models') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                View Models →
            </a>
        </div>

        {{-- Factory States --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="w-10 h-10 bg-[#f53003]/10 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-2">Factory States</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                Rich factory states: suspended(), verified(), premium(), admin(), published(), draft(), onSale(), highValue(), and more.
            </p>
            <a href="{{ route('features') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                View Features →
            </a>
        </div>

        {{-- Pennant Feature Flags --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="w-10 h-10 bg-[#f53003]/10 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-2">Pennant Feature Flags</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                Class-based and closure-based features, A/B testing with rich values, lottery-based rollouts, and global features.
            </p>
            <a href="{{ route('features') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                View Features →
            </a>
        </div>

        {{-- Query Scopes --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="w-10 h-10 bg-[#f53003]/10 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-2">Query Scopes</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                Reusable query scopes: active(), published(), recent(), pending(), completed(), onSale(), overdue(), and more.
            </p>
            <a href="{{ route('models') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                View Models →
            </a>
        </div>

        {{-- Enum Casts --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="w-10 h-10 bg-[#f53003]/10 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-2">Enum Casts</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                PHP 8.1 backed enums: OrderStatus, TaskStatus, TaskPriority, DeploymentStatus with helper methods.
            </p>
            <a href="{{ route('features') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                View Features →
            </a>
        </div>

        {{-- API Resources --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="w-10 h-10 bg-[#f53003]/10 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-2">API Resources</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                Eloquent API Resources with conditional attributes, nested relationships, and resource collections with pagination.
            </p>
            <a href="{{ route('api-docs') }}" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                View API →
            </a>
        </div>
    </div>

    {{-- Quick Start --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg p-8 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
        <h2 class="text-xl font-semibold mb-4">Quick Start with Tinker</h2>
        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
            Try these commands in <code class="bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded text-sm">php artisan tinker</code>:
        </p>
        <div class="bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code><span class="text-[#A1A09A]">// Create a user with posts and comments</span>
$user = User::factory()
    ->has(Post::factory()->count(3)->has(Comment::factory()->count(2)))
    ->create();

<span class="text-[#A1A09A]">// Query scopes</span>
User::active()->premium()->get();
Post::published()->recent()->get();
Order::pending()->get();

<span class="text-[#A1A09A]">// Pennant feature flags</span>
Feature::active('dark-mode');
Feature::for($user)->active(NewApi::class);
Feature::value('homepage-variant');</code></pre>
        </div>
    </div>
</x-app-layout>
