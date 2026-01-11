<?php

use function Laravel\Folio\name;

name('api-docs');

?>

<x-app-layout title="API Documentation">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">API Documentation</h1>
        <p class="text-[#706f6c] dark:text-[#A1A09A]">
            RESTful API endpoints with Eloquent API Resources, rate limiting, and authentication.
        </p>
    </div>

    {{-- Base URL --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] mb-8">
        <h2 class="font-semibold mb-2">Base URL</h2>
        <code class="text-sm bg-[#f5f5f4] dark:bg-[#1a1a19] px-3 py-2 rounded block">{{ url('/api') }}</code>
    </div>

    {{-- Authentication --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] mb-8">
        <h2 class="font-semibold mb-4">Authentication</h2>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
            Protected endpoints require a Bearer token in the Authorization header.
        </p>
        <div class="bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code>Authorization: Bearer {your-api-token}</code></pre>
        </div>
    </div>

    {{-- Rate Limiting --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] mb-8">
        <h2 class="font-semibold mb-4">Rate Limiting</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-[#f5f5f4] dark:bg-[#1a1a19] rounded-lg">
                <h3 class="font-medium text-sm mb-1">Default</h3>
                <p class="text-2xl font-bold text-[#f53003]">60</p>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">requests/minute</p>
            </div>
            <div class="p-4 bg-[#f5f5f4] dark:bg-[#1a1a19] rounded-lg">
                <h3 class="font-medium text-sm mb-1">Strict</h3>
                <p class="text-2xl font-bold text-[#f53003]">10</p>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">requests/minute</p>
            </div>
            <div class="p-4 bg-[#f5f5f4] dark:bg-[#1a1a19] rounded-lg">
                <h3 class="font-medium text-sm mb-1">High Volume</h3>
                <p class="text-2xl font-bold text-[#f53003]">120</p>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">requests/minute</p>
            </div>
        </div>
    </div>

    {{-- Posts Endpoints --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Posts</h2>
        <div class="space-y-4">
            {{-- GET /posts --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                    <code class="text-sm">/api/posts</code>
                    <span class="ml-auto text-xs text-[#706f6c] dark:text-[#A1A09A]">Public</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">List all published posts with pagination.</p>
                    <h4 class="text-xs font-semibold uppercase text-[#706f6c] dark:text-[#A1A09A] mb-2">Query Parameters</h4>
                    <div class="text-sm space-y-1">
                        <code class="block text-xs">?page=1</code>
                        <code class="block text-xs">?per_page=15</code>
                        <code class="block text-xs">?recent=7 <span class="text-[#706f6c]">(days)</span></code>
                    </div>
                </div>
            </div>

            {{-- GET /posts/{id} --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                    <code class="text-sm">/api/posts/{id}</code>
                    <span class="ml-auto text-xs text-[#706f6c] dark:text-[#A1A09A]">Public</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Get a single post with user, comments, and tags.</p>
                </div>
            </div>

            {{-- POST /posts --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded">POST</span>
                    <code class="text-sm">/api/posts</code>
                    <span class="ml-auto text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">Auth Required</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Create a new post.</p>
                    <h4 class="text-xs font-semibold uppercase text-[#706f6c] dark:text-[#A1A09A] mb-2">Request Body</h4>
                    <div class="bg-[#1a1a19] rounded-lg p-3 overflow-x-auto">
                        <pre class="text-xs text-[#EDEDEC]"><code>{
  "title": "string|required|max:255",
  "body": "string|required|min:10",
  "published_at": "date|nullable",
  "tags": "array|nullable"
}</code></pre>
                    </div>
                </div>
            </div>

            {{-- PUT /posts/{id} --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">PUT</span>
                    <code class="text-sm">/api/posts/{id}</code>
                    <span class="ml-auto text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">Auth Required</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Update an existing post.</p>
                </div>
            </div>

            {{-- DELETE /posts/{id} --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded">DELETE</span>
                    <code class="text-sm">/api/posts/{id}</code>
                    <span class="ml-auto text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">Auth Required</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Soft delete a post.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Endpoints --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Products</h2>
        <div class="space-y-4">
            {{-- GET /products --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                    <code class="text-sm">/api/products</code>
                    <span class="ml-auto text-xs text-[#706f6c] dark:text-[#A1A09A]">Public</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">List all active products with pagination.</p>
                    <h4 class="text-xs font-semibold uppercase text-[#706f6c] dark:text-[#A1A09A] mb-2">Query Parameters</h4>
                    <div class="text-sm space-y-1">
                        <code class="block text-xs">?in_stock=true</code>
                        <code class="block text-xs">?on_sale=true</code>
                        <code class="block text-xs">?sort=price <span class="text-[#706f6c]">(asc/desc)</span></code>
                    </div>
                </div>
            </div>

            {{-- GET /products/{id} --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                    <code class="text-sm">/api/products/{id}</code>
                    <span class="ml-auto text-xs text-[#706f6c] dark:text-[#A1A09A]">Public</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Get a single product with categories and images.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Endpoints --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Users</h2>
        <div class="space-y-4">
            {{-- GET /users --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                    <code class="text-sm">/api/users</code>
                    <span class="ml-auto text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">Auth Required</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">List all active users with pagination.</p>
                    <h4 class="text-xs font-semibold uppercase text-[#706f6c] dark:text-[#A1A09A] mb-2">Query Parameters</h4>
                    <div class="text-sm space-y-1">
                        <code class="block text-xs">?premium=true</code>
                        <code class="block text-xs">?verified=true</code>
                    </div>
                </div>
            </div>

            {{-- GET /users/{id} --}}
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                    <code class="text-sm">/api/users/{id}</code>
                    <span class="ml-auto text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded">Auth Required</span>
                </div>
                <div class="p-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Get a single user with posts, roles, and phone.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Endpoint --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Search</h2>
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
            <div class="flex items-center gap-3 p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <span class="px-2 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">GET</span>
                <code class="text-sm">/api/search</code>
                <span class="ml-auto text-xs text-[#706f6c] dark:text-[#A1A09A]">Public • High Rate Limit</span>
            </div>
            <div class="p-4">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Search across posts and products.</p>
                <h4 class="text-xs font-semibold uppercase text-[#706f6c] dark:text-[#A1A09A] mb-2">Query Parameters</h4>
                <div class="text-sm">
                    <code class="block text-xs">?q=search+term <span class="text-[#706f6c]">(required)</span></code>
                </div>
            </div>
        </div>
    </div>

    {{-- Response Format --}}
    <div class="bg-white dark:bg-[#161615] rounded-lg p-6 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
        <h2 class="font-semibold mb-4">Response Format</h2>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
            All responses use Eloquent API Resources with conditional attributes.
        </p>
        <div class="bg-[#1a1a19] rounded-lg p-4 overflow-x-auto">
            <pre class="text-sm text-[#EDEDEC]"><code>{
  "data": {
    "id": 1,
    "title": "Post Title",
    "excerpt": "First 100 characters...",
    "body": "Full content...",
    "published_at": "2026-01-10T00:00:00Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "comments_count": 5,
    "tags": ["laravel", "php"]
  },
  "links": { ... },
  "meta": {
    "current_page": 1,
    "total": 100
  }
}</code></pre>
        </div>
    </div>
</x-app-layout>
