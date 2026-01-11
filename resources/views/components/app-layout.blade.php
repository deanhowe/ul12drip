@props(['title' => 'Laravel Demo App'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen" x-data="{ mobileMenuOpen: false }">
        {{-- Navigation --}}
        <nav class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        {{-- Logo --}}
                        <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg">
                            <svg class="w-8 h-8 text-[#f53003]" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6## 29.2132 61.5765 29.3392 61.4553 29.4246L49.9952 36.0351V49.1661C49.9952 49.5765 49.7226 49.9509 49.3188 50.0712L25.665 57.1069C25.5765 57.1338 25.4838 57.1427 25.3916 57.1427C25.2994 57.1427 25.2066 57.1338 25.1182 57.1069L1.46443 50.0712C1.26094 50.0109 1.08467 49.8833 0.962825 49.7094C0.840982 49.5356 0.780261 49.3258 0.78125 49.1108V14.8858C0.78125 14.7978 0.793028 14.7102 0.816028 14.6253C0.826028 14.5765 0.846028 14.5308 0.862028 14.4851C0.886028 14.4165 0.913028 14.3509 0.948028 14.2882C0.971028 14.2427 1.00003 14.2 1.02903 14.1573C1.06803 14.0993 1.11203 14.0453 1.16003 13.9955C1.19703 13.9567 1.23803 13.9222 1.28003 13.8877C1.33403 13.8404 1.39103 13.7973 1.45203 13.7595L13.7234 6.15936C13.8758 6.06753 14.0482 6.01904 14.2238 6.01904C14.3994 6.01904 14.5718 6.06753 14.7242 6.15936L27.0001 13.7595C27.0611 13.7973 27.1181 13.8404 27.1721 13.8877C27.2141 13.9222 27.2551 13.9567 27.2921 13.9955C27.3401 14.0453 27.3841 14.0993 27.4231 14.1573C27.4521 14.2 27.4811 14.2427 27.5041 14.2882C27.5391 14.3509 27.5661 14.4165 27.5901 14.4851C27.6061 14.5308 27.6261 14.5765 27.6361 14.6253C27.6591 14.7102 27.6708 14.7978 27.671 14.8858V27.6327L37.7801 21.8797V8.63085C37.7801 8.54285 37.7919 8.45525 37.8149 8.37035C37.8249 8.32155 37.8449 8.27585 37.8609 8.23015C37.8849 8.16155 37.9119 8.09595 37.9469 8.03325C37.9699 7.98765 37.9989 7.94495 38.0279 7.90235C38.0669 7.84435 38.1109 7.79035 38.1589 7.74055C38.1959 7.70175 38.2369 7.66725 38.2789 7.63275C38.3329 7.58545 38.3899 7.54235 38.4509 7.50455L50.7268 -0.0955566C50.8793 -0.187389 51.0516 -0.235878 51.2272 -0.235878C51.4028 -0.235878 51.5752 -0.187389 51.7276 -0.0955566L64.0035 7.50455C64.0645 7.54235 64.1215 7.58545 64.1755 7.63275C64.2175 7.66725 64.2585 7.70175 64.2955 7.74055C64.3435 7.79035 64.3875 7.84435 64.4265 7.90235C64.4555 7.94495 64.4845 7.98765 64.5075 8.03325C64.5425 8.09595 64.5695 8.16155 64.5935 8.23015C64.6095 8.27585 64.6295 8.32155 64.6395 8.37035C64.6625 8.45525 64.6742 8.54285 64.6744 8.63085V35.4897L74.7835 29.7367V14.8858C74.7835 14.7978 74.7953 14.7102 74.8183 14.6253C74.8283 14.5765 74.8483 14.5308 74.8643 14.4851C74.8883 14.4165 74.9153 14.3509 74.9503 14.2882C74.9733 14.2427 75.0023 14.2 75.0313 14.1573C75.0703 14.0993 75.1143 14.0453 75.1623 13.9955C75.1993 13.9567 75.2403 13.9222 75.2823 13.8877C75.3363 13.8404 75.3933 13.7973 75.4543 13.7595L87.7302 6.15936C87.8826 6.06753 88.055 6.01904 88.2306 6.01904C88.4062 6.01904 88.5786 6.06753 88.731 6.15936L101.007 13.7595C101.068 13.7973 101.125 13.8404 101.179 13.8877C101.221 13.9222 101.262 13.9567 101.299 13.9955C101.347 14.0453 101.391 14.0993 101.43 14.1573C101.459 14.2 101.488 14.2427 101.511 14.2882C101.546 14.3509 101.573 14.4165 101.597 14.4851C101.613 14.5308 101.633 14.5765 101.643 14.6253C101.666 14.7102 101.678 14.7978 101.678 14.8858V49.1108C101.678 49.3258 101.617 49.5356 101.495 49.7094C101.373 49.8833 101.197 50.0109 100.994 50.0712L77.3397 57.1069C77.2512 57.1338 77.1585 57.1427 77.0663 57.1427C76.9741 57.1427 76.8814 57.1338 76.7929 57.1069L53.1391 50.0712C52.9356 50.0109 52.7593 49.8833 52.6375 49.7094C52.5156 49.5356 52.4549 49.3258 52.4559 49.1108V36.0351L40.9958 29.4246C40.8746 29.3392 40.7756 29.2132 40.6876 29.0614C40.5996 28.9095 40.5532 28.737 40.5533 28.5615V14.8858C40.5533 14.7978 40.5651 14.7102 40.5881 14.6253C40.5981 14.5765 40.6181 14.5308 40.6341 14.4851C40.6581 14.4165 40.6851 14.3509 40.7201 14.2882C40.7431 14.2427 40.7721 14.2 40.8011 14.1573C40.8401 14.0993 40.8841 14.0453 40.9321 13.9955C40.9691 13.9567 41.0101 13.9222 41.0521 13.8877C41.1061 13.8404 41.1631 13.7973 41.2241 13.7595L53.5 6.15936C53.6524 6.06753 53.8248 6.01904 54.0004 6.01904C54.176 6.01904 54.3484 6.06753 54.5008 6.15936L66.7767 13.7595C66.8377 13.7973 66.8947 13.8404 66.9487 13.8877C66.9907 13.9222 67.0317 13.9567 67.0687 13.9955C67.1167 14.0453 67.1607 14.0993 67.1997 14.1573C67.2287 14.2 67.2577 14.2427 67.2807 14.2882C67.3157 14.3509 67.3427 14.4165 67.3667 14.4851C67.3827 14.5308 67.4027 14.5765 67.4127 14.6253Z" fill="currentColor" transform="scale(0.6)"/>
                            </svg>
                            <span>Laravel Demo</span>
                        </a>

                        {{-- Navigation Links --}}
                        <div class="hidden sm:flex items-center gap-6 text-sm">
                            @foreach(config('navigation') as $item)
                                <x-nav-link :href="route($item['route'])" :active="request()->routeIs($item['route'])">
                                    {{ $item['label'] }}
                                </x-nav-link>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right side --}}
                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-sm hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-[#f53003] text-white rounded-md hover:bg-[#d42a03] transition-colors">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>

                        {{-- Mobile menu button --}}
                        <div class="flex items-center sm:hidden">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-[#1C1C1A] focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f53003]">
                                <span class="sr-only">Open main menu</span>
                                <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div class="sm:hidden" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false">
                <div class="pt-2 pb-3 space-y-1 px-4">
                    @foreach(config('navigation') as $item)
                        <a href="{{ route($item['route']) }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs($item['route']) ? 'text-[#f53003] bg-gray-50 dark:bg-[#1C1C1A]' : 'text-gray-600 dark:text-gray-400 hover:text-[#f53003] hover:bg-gray-50 dark:hover:bg-[#1C1C1A]' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <hr class="my-2 border-[#e3e3e0] dark:border-[#3E3E3A]">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 dark:text-gray-400 hover:text-[#f53003]">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 dark:text-gray-400 hover:text-[#f53003]">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#f53003]">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    <p>Laravel Documentation Demo App</p>
                    <div class="flex items-center gap-4">
                        <a href="https://laravel.com/docs" target="_blank" class="hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                            Laravel Docs
                        </a>
                        <a href="https://github.com/laravel/laravel" target="_blank" class="hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                            GitHub
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
