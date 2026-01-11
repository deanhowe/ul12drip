<?php

use function Laravel\Folio\name;

name('features');

?>

<x-app-layout title="Features">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Laravel Features Demo</h1>
        <p class="text-[#706f6c] dark:text-[#A1A09A]">
            Comprehensive examples of Laravel's key features including Pennant, Enums, Factory States, Query Scopes, and more.
        </p>
    </div>

    {{-- Pennant Feature Flags --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Pennant Feature Flags</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Class-Based Features --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold text-lg mb-3 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                    Class-Based Features
                </h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">NewApi</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Lottery-based rollout (10% of users)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">TeamBilling</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Rich values: basic, premium, enterprise tiers</p>
                    </div>
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">PurchaseButton</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Nullable scope (guest users)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">SiteBanner</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Global feature (no scope)</p>
                    </div>
                </div>
            </div>

            {{-- Closure-Based Features --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold text-lg mb-3 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    Closure-Based Features
                </h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">dark-mode</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Simple boolean (even user IDs)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">beta-tester</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Lottery odds (1 in 5 users)</p>
                    </div>
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">homepage-variant</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">A/B testing: control, variant-a, variant-b</p>
                    </div>
                    <div>
                        <code class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">maintenance-mode</code>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Global toggle (no user scope)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pennant Tinker Examples --}}
        <div class="mt-4 bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code><span class="text-[#A1A09A]">// Check feature flags</span>
Feature::active('dark-mode');
Feature::for($user)->active(NewApi::class);
Feature::value('homepage-variant'); <span class="text-[#A1A09A]">// Returns 'control', 'variant-a', or 'variant-b'</span>
Feature::value(TeamBilling::class); <span class="text-[#A1A09A]">// Returns 'basic', 'premium', or 'enterprise'</span></code></pre>
        </div>
    </div>

    {{-- Enum Casts --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Enum Casts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">OrderStatus</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span>pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>processing</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>completed</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span>cancelled</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">TaskStatus</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                        <span>pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>in_progress</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>completed</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">TaskPriority</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>low</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span>medium</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span>high</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">DeploymentStatus</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                        <span>pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>running</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>success</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span>failed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code><span class="text-[#A1A09A]">// Enum helper methods</span>
$order->status->label();  <span class="text-[#A1A09A]">// "Pending"</span>
$order->status->color();  <span class="text-[#A1A09A]">// "yellow"</span>
$task->priority->sortOrder();  <span class="text-[#A1A09A]">// 1, 2, or 3</span>
$deployment->status->isTerminal();  <span class="text-[#A1A09A]">// true for success/failed</span></code></pre>
        </div>
    </div>

    {{-- Factory States --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Factory States</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">UserFactory</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">suspended()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">verified()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">premium()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">admin()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">premiumAdmin()</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">PostFactory</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">published()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">draft()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">scheduled()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">publishedToday()</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">OrderFactory</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">pending()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">processing()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">completed()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">cancelled()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">highValue()</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">ProductFactory</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">outOfStock()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">inactive()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">onSale()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">bigDiscount()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">lowStock()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">premium()</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">TaskFactory</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">pending()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">inProgress()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">completed()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">highPriority()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">overdue()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">urgent()</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">CommentFactory</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">forPost()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">forVideo()</span>
                    <span class="text-xs bg-[#f5f5f4] dark:bg-[#1a1a19] px-2 py-1 rounded">forProduct()</span>
                </div>
            </div>
        </div>

        <div class="mt-4 bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code><span class="text-[#A1A09A]">// Using factory states</span>
User::factory()->premium()->admin()->create();
Post::factory()->published()->count(5)->create();
Order::factory()->completed()->highValue()->create();
Product::factory()->onSale()->lowStock()->create();</code></pre>
        </div>
    </div>

    {{-- Traits --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Reusable Traits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">HasComments / Commentable</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Polymorphic comments for any model</p>
                <div class="text-sm space-y-1">
                    <code class="block text-xs">$model->comments</code>
                    <code class="block text-xs">$model->addComment('text', $user)</code>
                    <code class="block text-xs">$model->comments_count</code>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">HasTags / Taggable</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Polymorphic many-to-many tags</p>
                <div class="text-sm space-y-1">
                    <code class="block text-xs">$model->tags</code>
                    <code class="block text-xs">$model->attachTags(['php', 'laravel'])</code>
                    <code class="block text-xs">$model->hasTag('php')</code>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">HasImages</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Polymorphic images with ordering</p>
                <div class="text-sm space-y-1">
                    <code class="block text-xs">$model->images</code>
                    <code class="block text-xs">$model->addImage($url, $alt)</code>
                    <code class="block text-xs">$model->primary_image</code>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="font-semibold mb-3">HasAddresses</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Polymorphic addresses with types</p>
                <div class="text-sm space-y-1">
                    <code class="block text-xs">$model->addresses</code>
                    <code class="block text-xs">$model->primary_address</code>
                    <code class="block text-xs">$model->billing_address</code>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
