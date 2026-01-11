<x-app-layout title="Login">
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-[#161615] rounded-lg p-8 shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-4 py-2 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-[#f53003] focus:border-transparent"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-2 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-[#f53003] focus:border-transparent"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-[#e3e3e0] dark:border-[#3E3E3A] text-[#f53003] focus:ring-[#f53003]"
                            >
                            <span class="ml-2 text-sm">Remember me</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full py-2 px-4 bg-[#f53003] text-white rounded-lg hover:bg-[#d42a03] transition-colors font-medium"
                    >
                        Login
                    </button>
                </form>

                {{-- Links --}}
                <div class="mt-6 text-center text-sm space-y-2">
                    @if (Route::has('password.request'))
                        <p>
                            <a href="{{ route('password.request') }}" class="text-[#f53003] dark:text-[#FF4433] hover:underline">
                                Forgot your password?
                            </a>
                        </p>
                    @endif
                    @if (Route::has('register'))
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-[#f53003] dark:text-[#FF4433] hover:underline">
                                Register
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
