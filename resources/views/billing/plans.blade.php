{{--
    Subscription Plans View

    Demonstrates Laravel Cashier features:
    - Displaying subscription plans with pricing
    - Monthly vs yearly billing toggle
    - Stripe Checkout integration for subscriptions
    - Featured plan highlighting

    Variables passed from BillingController@plans:
    - $monthlyPlans: Collection of monthly billing plans
    - $yearlyPlans: Collection of yearly billing plans
--}}
<x-app-layout title="Subscription Plans">
    <div class="space-y-8">
        {{-- Page Header --}}
        <div class="text-center max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold">Choose Your Plan</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A] mt-2">
                Select the perfect plan for your needs. All plans include a 14-day free trial.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if (session('info'))
            <div class="max-w-4xl mx-auto p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-blue-700 dark:text-blue-400">
                {{ session('info') }}
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-4xl mx-auto p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        {{-- Billing Toggle --}}
        <div class="flex justify-center" x-data="{ billing: 'monthly' }">
            <div class="inline-flex items-center gap-4 p-1 bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-lg">
                <button
                    @click="billing = 'monthly'"
                    :class="billing === 'monthly' ? 'bg-white dark:bg-[#161615] shadow-sm' : 'hover:bg-white/50 dark:hover:bg-[#161615]/50'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all"
                >
                    Monthly
                </button>
                <button
                    @click="billing = 'yearly'"
                    :class="billing === 'yearly' ? 'bg-white dark:bg-[#161615] shadow-sm' : 'hover:bg-white/50 dark:hover:bg-[#161615]/50'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all"
                >
                    Yearly
                    <span class="ml-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded text-xs">
                        Save 17%
                    </span>
                </button>
            </div>

            {{-- Monthly Plans --}}
            <div x-show="billing === 'monthly'" x-transition class="mt-8 grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                @foreach ($monthlyPlans as $plan)
                    <div class="relative bg-white dark:bg-[#161615] border {{ $plan->is_featured ? 'border-[#f53003] ring-2 ring-[#f53003]' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} rounded-xl p-6 flex flex-col">
                        {{-- Featured Badge --}}
                        @if ($plan->is_featured)
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                <span class="px-3 py-1 bg-[#f53003] text-white text-xs font-medium rounded-full">
                                    Most Popular
                                </span>
                            </div>
                        @endif

                        {{-- Plan Header --}}
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-semibold">{{ $plan->name }}</h3>
                            <div class="mt-4">
                                <span class="text-4xl font-bold">{{ $plan->formatted_price }}</span>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $plan->interval_label }}</span>
                            </div>
                            <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $plan->description }}</p>
                        </div>

                        {{-- Features List --}}
                        <ul class="space-y-3 mb-6 flex-grow">
                            @foreach ($plan->features ?? [] as $feature)
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Subscribe Button --}}
                        @auth
                            @if (auth()->user()->subscribedToPrice($plan->stripe_price_id, 'default'))
                                <button disabled class="w-full py-3 px-4 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-lg cursor-not-allowed">
                                    Current Plan
                                </button>
                            @elseif (auth()->user()->subscribed('default'))
                                <form action="{{ route('billing.swap') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $plan->id }}">
                                    <button type="submit" class="w-full py-3 px-4 {{ $plan->is_featured ? 'bg-[#f53003] hover:bg-[#d42a03]' : 'bg-[#1b1b18] dark:bg-white hover:opacity-90' }} text-white {{ $plan->is_featured ? '' : 'dark:text-[#1b1b18]' }} rounded-lg transition-colors">
                                        Switch to {{ $plan->name }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('billing.checkout', $plan) }}" class="block w-full py-3 px-4 text-center {{ $plan->is_featured ? 'bg-[#f53003] hover:bg-[#d42a03]' : 'bg-[#1b1b18] dark:bg-white hover:opacity-90' }} text-white {{ $plan->is_featured ? '' : 'dark:text-[#1b1b18]' }} rounded-lg transition-colors">
                                    Get Started
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 text-center {{ $plan->is_featured ? 'bg-[#f53003] hover:bg-[#d42a03]' : 'bg-[#1b1b18] dark:bg-white hover:opacity-90' }} text-white {{ $plan->is_featured ? '' : 'dark:text-[#1b1b18]' }} rounded-lg transition-colors">
                                Get Started
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>

            {{-- Yearly Plans --}}
            <div x-show="billing === 'yearly'" x-transition class="mt-8 grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                @foreach ($yearlyPlans as $plan)
                    <div class="relative bg-white dark:bg-[#161615] border {{ $plan->is_featured ? 'border-[#f53003] ring-2 ring-[#f53003]' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} rounded-xl p-6 flex flex-col">
                        {{-- Featured Badge --}}
                        @if ($plan->is_featured)
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                <span class="px-3 py-1 bg-[#f53003] text-white text-xs font-medium rounded-full">
                                    Most Popular
                                </span>
                            </div>
                        @endif

                        {{-- Plan Header --}}
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-semibold">{{ $plan->name }}</h3>
                            <div class="mt-4">
                                <span class="text-4xl font-bold">{{ $plan->formatted_price }}</span>
                                <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $plan->interval_label }}</span>
                            </div>
                            <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $plan->description }}</p>
                        </div>

                        {{-- Features List --}}
                        <ul class="space-y-3 mb-6 flex-grow">
                            @foreach ($plan->features ?? [] as $feature)
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Subscribe Button --}}
                        @auth
                            @if (auth()->user()->subscribedToPrice($plan->stripe_price_id, 'default'))
                                <button disabled class="w-full py-3 px-4 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-lg cursor-not-allowed">
                                    Current Plan
                                </button>
                            @elseif (auth()->user()->subscribed('default'))
                                <form action="{{ route('billing.swap') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $plan->id }}">
                                    <button type="submit" class="w-full py-3 px-4 {{ $plan->is_featured ? 'bg-[#f53003] hover:bg-[#d42a03]' : 'bg-[#1b1b18] dark:bg-white hover:opacity-90' }} text-white {{ $plan->is_featured ? '' : 'dark:text-[#1b1b18]' }} rounded-lg transition-colors">
                                        Switch to {{ $plan->name }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('billing.checkout', $plan) }}" class="block w-full py-3 px-4 text-center {{ $plan->is_featured ? 'bg-[#f53003] hover:bg-[#d42a03]' : 'bg-[#1b1b18] dark:bg-white hover:opacity-90' }} text-white {{ $plan->is_featured ? '' : 'dark:text-[#1b1b18]' }} rounded-lg transition-colors">
                                    Get Started
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 text-center {{ $plan->is_featured ? 'bg-[#f53003] hover:bg-[#d42a03]' : 'bg-[#1b1b18] dark:bg-white hover:opacity-90' }} text-white {{ $plan->is_featured ? '' : 'dark:text-[#1b1b18]' }} rounded-lg transition-colors">
                                Get Started
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ Section --}}
        <div class="max-w-3xl mx-auto mt-16">
            <h2 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <details class="group bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <summary class="flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-medium">Can I cancel my subscription anytime?</span>
                        <svg class="w-5 h-5 text-[#706f6c] group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="px-4 pb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Yes! You can cancel your subscription at any time. You'll continue to have access until the end of your current billing period.
                    </p>
                </details>

                <details class="group bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <summary class="flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-medium">What payment methods do you accept?</span>
                        <svg class="w-5 h-5 text-[#706f6c] group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="px-4 pb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        We accept all major credit cards (Visa, Mastercard, American Express) through our secure payment processor, Stripe.
                    </p>
                </details>

                <details class="group bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <summary class="flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-medium">Can I switch plans later?</span>
                        <svg class="w-5 h-5 text-[#706f6c] group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="px-4 pb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Absolutely! You can upgrade or downgrade your plan at any time. When you switch plans, we'll automatically prorate the charges.
                    </p>
                </details>

                <details class="group bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                    <summary class="flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-medium">Do you offer refunds?</span>
                        <svg class="w-5 h-5 text-[#706f6c] group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="px-4 pb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        We offer a 14-day free trial so you can try before you buy. If you're not satisfied within the first 30 days of a paid subscription, contact us for a full refund.
                    </p>
                </details>
            </div>
        </div>

        {{-- Back to Billing Link --}}
        @auth
            <div class="text-center">
                <a href="{{ route('billing.index') }}" class="text-[#f53003] hover:underline">
                    ← Back to Billing Dashboard
                </a>
            </div>
        @endauth
    </div>
</x-app-layout>
