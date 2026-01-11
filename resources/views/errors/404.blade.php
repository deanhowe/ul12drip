<x-app-layout title="Page Not Found">
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <h1 class="text-9xl font-bold text-[#f53003]">404</h1>
        <h2 class="text-2xl font-semibold mt-4 mb-2">Page Not Found</h2>
        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-md">
            Sorry, the page you are looking for doesn't exist or has been moved.
        </p>
        <a href="{{ route('home') }}" class="px-6 py-3 bg-[#f53003] text-white rounded-md hover:bg-[#d42a03] transition-colors">
            Go Back Home
        </a>
    </div>
</x-app-layout>
