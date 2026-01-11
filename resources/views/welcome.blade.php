<x-app-layout title="Welcome">
    <div class="flex flex-col lg:flex-row bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden">
        <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20">
            <h1 class="text-3xl font-bold mb-4">Let's get started</h1>
            <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A] text-lg">Laravel has an incredibly rich ecosystem. <br>This application serves as a comprehensive showcase of Laravel 12 features, Eloquent relationships, Pennant feature flags, and much more.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="p-4 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <h2 class="font-semibold text-lg mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Eloquent Mastery
                    </h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Explore 26+ models demonstrating every relationship type, from simple One-to-One to complex Polymorphic Many-to-Many.</p>
                    <a href="{{ route('models') }}" class="mt-4 inline-block text-[#f53003] hover:underline font-medium">View Models &rarr;</a>
                </div>
                <div class="p-4 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <h2 class="font-semibold text-lg mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#f53003]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Exhaustive Features
                    </h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Check out the Exhaustive page to see every database column type and validation rule in action.</p>
                    <a href="{{ route('exhaustive') }}" class="mt-4 inline-block text-[#f53003] hover:underline font-medium">View Exhaustive &rarr;</a>
                </div>
            </div>

            <ul class="flex flex-col mb-8 lg:mb-10 space-y-4">
                <li class="flex items-center gap-4">
                    <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-sm w-10 h-10 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                        <span class="rounded-full bg-[#f53003] w-2.5 h-2.5"></span>
                    </span>
                    <span class="text-base">
                        Read the <a href="https://laravel.com/docs" target="_blank" class="font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433]">Official Documentation</a>
                    </span>
                </li>
                <li class="flex items-center gap-4">
                    <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-sm w-10 h-10 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                        <span class="rounded-full bg-[#f53003] w-2.5 h-2.5"></span>
                    </span>
                    <span class="text-base">
                        Watch expert tutorials at <a href="https://laracasts.com" target="_blank" class="font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433]">Laracasts</a>
                    </span>
                </li>
            </ul>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('models') }}" class="inline-block px-8 py-4 bg-[#f53003] text-white rounded-md hover:bg-[#d42a03] transition-colors font-semibold text-lg">
                    Get Started
                </a>
                <a href="https://github.com/laravel/laravel" target="_blank" class="inline-block px-8 py-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md hover:bg-gray-50 dark:hover:bg-[#1C1C1A] transition-colors font-semibold text-lg">
                    GitHub Source
                </a>
            </div>
        </div>

        <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative p-12 lg:w-[450px] shrink-0 flex items-center justify-center">
            <div class="relative w-full aspect-square flex items-center justify-center">
                 <svg class="w-full h-full text-[#F53003] dark:text-[#F61500]" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V54.3501C61.8897 54.608 61.7721 54.8519 61.5701 55.0131C61.368 55.1743 61.103 55.2351 60.8504 55.1782L31.3344 48.5414V62.8801C31.3344 63.1462 31.2093 63.3963 30.9959 63.5567C30.7825 63.717 30.5042 63.7705 30.2464 63.7011L0.697412 55.7511C0.460117 55.6873 0.254133 55.5392 0.120614 55.3364C-0.0129047 55.1337 -0.0592429 54.897 0.000104642 54.6732C0.000104642 54.6732 0.000104642 54.6725 0.000104642 54.6718L7.14545 28.0064L0.0880144 26.2335C0.0560205 26.2255 0.0246944 26.2141 0.000104642 26.1996C0.000104642 26.1996 0.000104642 26.1989 0.000104642 26.1982C0.000104642 26.1975 0.000104642 26.1961 0.000104642 26.1954V10.1221C0.000104642 9.87324 0.108744 9.63756 0.297441 9.47721C0.486138 9.31687 0.735073 9.24784 0.978148 9.28892L30.5271 14.2882C30.7849 14.3316 31.011 14.4777 31.1408 14.6853C31.2707 14.893 31.2913 15.1419 31.1967 15.3674L24.6492 30.9575L30.6384 32.3045L37.2458 16.5714C37.345 16.3351 37.3276 16.0665 37.1989 15.8443C37.0701 15.6221 36.8427 15.4674 36.5843 15.4241L7.03536 10.4249V24.5103L12.5511 25.8953C12.8082 25.9598 13.0143 26.1242 13.1206 26.3496C13.2269 26.575 13.2223 26.8375 13.1077 27.0586L6.50162 39.757L1.87959 54.0857L30.0768 61.6644V48.5422C30.0768 48.2902 30.188 48.0519 30.3813 47.8893C30.5746 47.7268 30.8306 47.6565 31.0827 47.6966L60.6317 52.4245V16.3468L38.4843 12.6021C38.2265 12.5587 38.0004 12.4126 37.8705 12.2049C37.7407 11.9972 37.7201 11.7483 37.8147 11.5228L44.3622 -3.34448e-05L50.218 0.989803L44.2985 15.0863L60.9123 17.8967C61.1553 17.9377 61.3734 18.0776 61.5097 18.2804C61.6459 18.4831 61.6865 18.7283 61.621 18.952L54.7431 42.4837L61.3492 43.6017C61.5922 43.6428 61.8103 43.7826 61.9466 43.9854C62.0828 44.1881 62.1235 44.4333 62.0579 44.657L55.0503 68.6143L49.1945 67.6244L55.7337 45.2635L49.1276 44.1455C48.8846 44.1044 48.6666 43.9645 48.5303 43.7618C48.394 43.559 48.3534 43.3138 48.3534 43.0901L55.2968 19.5585L39.4206 16.8741L32.8131 32.6072C32.7139 32.8435 32.7313 33.1121 32.8601 33.3343C32.9888 33.5564 33.2163 33.7112 33.4747 33.7545L61.1923 38.4411C61.4449 38.4838 61.6763 38.6293 61.8103 38.8299C61.9442 39.0304 61.9669 39.2749 61.8706 39.4939L55.4851 53.9926L49.8824 53.0962L55.4055 40.5557L32.1858 36.6291L25.5783 52.3622C25.4791 52.5985 25.4965 52.867 25.6253 53.0892C25.754 53.3114 25.9815 53.4662 26.2399 53.5094L54.195 58.2366L48.2755 72.3331L42.4196 71.3432L48.3392 57.2468L31.3344 54.3712V60.7161L7.73383 54.346L12.3559 40.0173C12.4551 39.781 12.4377 39.5124 12.3089 39.2902C12.1802 39.0681 11.9527 38.9133 11.6943 38.87L2.14828 37.2561L8.75439 24.5577L14.3571 25.4542L7.81788 37.8151L11.5204 38.4411L18.1265 25.7428C18.2257 25.5065 18.2083 25.2379 18.0795 25.0157C17.9508 24.7935 17.7233 24.6387 17.4649 24.5954L1.87959 21.96L1.87959 11.5222L30.2464 16.3182L23.639 32.0512C23.5398 32.2875 23.5571 32.5561 23.6859 32.7783C23.8146 33.0004 24.0421 33.1552 24.3005 33.1985L50.218 37.581L44.3622 51.6775L50.218 52.6674L56.7573 37.3065L61.8548 14.6253Z" fill="currentColor"/></svg>
            </div>
        </div>
    </div>
</x-app-layout>
