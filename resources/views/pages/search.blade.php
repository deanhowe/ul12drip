<?php
use function Laravel\Folio\name;
use App\Models\Post;
use Illuminate\Http\Request;

name('search');

$query = request('q');
$posts = $query ? Post::search($query)->query(fn ($query) => $query->with('user'))->paginate(10) : collect();
?>

<x-app-layout title="Search Showcase">
    <div class="space-y-8">
        <header>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Laravel Scout Search Showcase</h1>
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                This page demonstrates full-text search using Laravel Scout with the Database driver.
            </p>
        </header>

        <section class="max-w-2xl">
            <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search posts..." class="block w-full pl-10 pr-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md leading-5 bg-white dark:bg-[#161615] placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#f53003] focus:border-[#f53003] sm:text-sm">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#f53003] hover:bg-[#d42a03] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#f53003]">
                    Search
                </button>
            </form>
        </section>

        @if($query)
            <section class="space-y-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Search Results for "{{ $query }}" ({{ $posts->total() }})
                </h2>

                @if($posts->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No results found.</p>
                @else
                    <div class="grid gap-6">
                        @foreach($posts as $post)
                            <div class="p-6 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg shadow-sm">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 line-clamp-3 mb-4">{{ $post->body }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>By {{ $post->user->name }}</span>
                                    <span class="mx-2">&bull;</span>
                                    <span>{{ $post->published_at?->diffForHumans() ?? 'Draft' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $posts->appends(['q' => $query])->links() }}
                    </div>
                @endif
            </section>
        @else
            <section class="p-8 border-2 border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No query entered</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter a keyword above to test Laravel Scout's full-text search capabilities.</p>
            </section>
        @endif
    </div>
</x-app-layout>
