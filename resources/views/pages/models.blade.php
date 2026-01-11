<?php

use function Laravel\Folio\name;

name('models');

?>

<x-app-layout title="Models">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Eloquent Models</h1>
        <p class="text-[#706f6c] dark:text-[#A1A09A]">
            All models from the Laravel documentation examples, demonstrating every relationship type.
        </p>
    </div>

    {{-- Relationship Types Legend --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] mb-8">
        <h2 class="font-semibold mb-4">Relationship Types</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span>hasOne</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span>hasMany</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                <span>belongsTo</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                <span>belongsToMany</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                <span>hasOneThrough</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                <span>hasManyThrough</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                <span>morphOne/morphMany</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span>morphToMany</span>
            </div>
        </div>
    </div>

    {{-- Models Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- User Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">User</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Core user model with soft deletes, premium status, and admin flags.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <code class="text-xs">hasOne(Phone)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <code class="text-xs">hasMany(Post, Order, Project)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    <code class="text-xs">belongsToMany(Role)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <code class="text-xs">morphMany(Image, Address)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> active(), suspended(), premium(), admins(), verified()
                </p>
            </div>
        </div>

        {{-- Post Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Post</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Blog posts with soft deletes and publishing workflow.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <code class="text-xs">belongsTo(User)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <code class="text-xs">morphMany(Comment, Image)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <code class="text-xs">morphToMany(Tag)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> published(), draft(), scheduled(), recent()
                </p>
            </div>
        </div>

        {{-- Comment Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Comment</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Polymorphic comments for posts, videos, and products.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <code class="text-xs">morphTo(commentable)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <code class="text-xs">belongsTo(User)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> recent(), byUser()
                </p>
            </div>
        </div>

        {{-- Order Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Order</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">E-commerce orders with enum status and soft deletes.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <code class="text-xs">belongsTo(User)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    <code class="text-xs">belongsToMany(Product)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> pending(), processing(), completed(), cancelled(), recent()
                </p>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                    <strong>Enum:</strong> OrderStatus
                </p>
            </div>
        </div>

        {{-- Product Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Product</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">E-commerce products with categories and soft deletes.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    <code class="text-xs">belongsToMany(Category, Order)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <code class="text-xs">morphMany(Comment, Image)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <code class="text-xs">morphToMany(Tag)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> active(), inStock(), outOfStock(), onSale()
                </p>
            </div>
        </div>

        {{-- Mechanic/Car/Owner (Has One Through) --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Mechanic → Car → Owner</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Has One Through relationship from Laravel docs.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                    <code class="text-xs">hasOneThrough(Owner, Car)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <code class="text-xs">Mechanic hasMany(Car)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <code class="text-xs">Car hasOne(Owner)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Usage:</strong> $mechanic->carOwner
                </p>
            </div>
        </div>

        {{-- Project/Environment/Deployment (Has Many Through) --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Project → Environment → Deployment</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Has Many Through relationship from Laravel docs.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <code class="text-xs">hasManyThrough(Deployment, Environment)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <code class="text-xs">Project hasMany(Environment)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <code class="text-xs">Environment hasMany(Deployment)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Usage:</strong> $project->deployments
                </p>
            </div>
        </div>

        {{-- Tag (Polymorphic Many-to-Many) --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Tag</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Polymorphic many-to-many for posts, videos, products.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <code class="text-xs">morphedByMany(Post)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <code class="text-xs">morphedByMany(Video)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Trait:</strong> Taggable (HasTags)
                </p>
            </div>
        </div>

        {{-- Image (Polymorphic One-to-Many) --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Image</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Polymorphic images for users, posts, products.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <code class="text-xs">morphTo(imageable)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Trait:</strong> HasImages
                </p>
            </div>
        </div>

        {{-- Task Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Task</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Project tasks with status and priority enums.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <code class="text-xs">belongsTo(Project, User)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> pending(), inProgress(), completed(), highPriority(), overdue()
                </p>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                    <strong>Enums:</strong> TaskStatus, TaskPriority
                </p>
            </div>
        </div>

        {{-- Deployment Model --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Deployment</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Environment deployments with status tracking.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <code class="text-xs">belongsTo(Environment)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> pending(), running(), successful(), failed(), recent()
                </p>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                    <strong>Enum:</strong> DeploymentStatus
                </p>
            </div>
        </div>

        {{-- Flight/Airline --}}
        <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h3 class="font-semibold text-lg mb-3">Flight & Airline</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Classic Laravel docs example models.</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <code class="text-xs">Flight belongsTo(Airline)</code>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <code class="text-xs">Airline hasMany(Flight)</code>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <strong>Scopes:</strong> active(), inactive(), byCountry()
                </p>
            </div>
        </div>
    </div>

    {{-- Tinker Examples --}}
    <div class="mt-8 bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
        <h2 class="font-semibold text-lg mb-4">Try in Tinker</h2>
        <div class="bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code><span class="text-[#A1A09A]">// Has One Through: Mechanic -> Car -> Owner</span>
$mechanic = Mechanic::first();
$mechanic->carOwner;

<span class="text-[#A1A09A]">// Has Many Through: Project -> Environment -> Deployment</span>
$project = Project::first();
$project->deployments;

<span class="text-[#A1A09A]">// Polymorphic Tags</span>
$post = Post::first();
$post->tags;
$post->attachTags(['laravel', 'php']);

<span class="text-[#A1A09A]">// Polymorphic Comments</span>
$video = Video::first();
$video->comments;
$video->addComment('Great video!');</code></pre>
        </div>
    </div>
</x-app-layout>
