{{--
    Billing Dashboard View

    Demonstrates Laravel Cashier features:
    - Current subscription status display
    - Payment method management with Stripe Elements
    - Recent invoices list
    - Subscription actions (cancel, resume, swap)
    - Stripe Customer Portal link

    Variables passed from BillingController@index:
    - $user: Current authenticated user
    - $subscription: User's current subscription (or null)
    - $plans: Available subscription plans
    - $invoices: User's recent invoices
    - $defaultPaymentMethod: User's default payment method
    - $paymentMethods: All user's payment methods
    - $intent: Stripe SetupIntent for adding payment methods
--}}
<x-app-layout title="Billing">
    <div class="space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Billing</h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">Manage your subscription and payment methods</p>
            </div>
            <a href="{{ route('billing.portal') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Stripe Portal
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-blue-700 dark:text-blue-400">
                {{ session('info') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content (2 columns) --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Current Subscription --}}
                <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Current Subscription</h2>

                    @if ($subscription)
                        <div class="space-y-4">
                            {{-- Subscription Status Badge --}}
                            <div class="flex items-center gap-3">
                                @if ($subscription->onGracePeriod())
                                    <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-sm font-medium">
                                        Cancelling
                                    </span>
                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Access until {{ $subscription->ends_at->format('M d, Y') }}
                                    </span>
                                @elseif ($subscription->onTrial())
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-medium">
                                        Trial
                                    </span>
                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Ends {{ $subscription->trial_ends_at->format('M d, Y') }}
                                    </span>
                                @elseif ($subscription->active())
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-medium">
                                        Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400 rounded-full text-sm font-medium">
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            {{-- Current Plan Details --}}
                            @php
                                $currentPlan = $plans->firstWhere('stripe_price_id', $subscription->stripe_price);
                            @endphp
                            @if ($currentPlan)
                                <div class="p-4 bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-lg">{{ $currentPlan->name }}</h3>
                                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ $currentPlan->description }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold">{{ $currentPlan->formatted_price }}</span>
                                            <span class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ $currentPlan->interval_label }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Subscription Actions --}}
                            <div class="flex flex-wrap gap-3 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                                @if ($subscription->onGracePeriod())
                                    <form action="{{ route('billing.resume') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            Resume Subscription
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('billing.plans') }}" class="px-4 py-2 bg-[#f53003] text-white rounded-lg hover:bg-[#d42a03] transition-colors">
                                        Change Plan
                                    </a>
                                    <form action="{{ route('billing.cancel') }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your subscription?')">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            Cancel Subscription
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- No Subscription --}}
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-[#706f6c] dark:text-[#A1A09A] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="text-lg font-medium mb-2">No Active Subscription</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">Choose a plan to get started with premium features.</p>
                            <a href="{{ route('billing.plans') }}" class="inline-flex px-6 py-3 bg-[#f53003] text-white rounded-lg hover:bg-[#d42a03] transition-colors">
                                View Plans
                            </a>
                        </div>
                    @endif
                </section>

                {{-- Payment Methods --}}
                <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Payment Methods</h2>

                    @if ($paymentMethods->count() > 0)
                        <div class="space-y-3 mb-6">
                            @foreach ($paymentMethods as $method)
                                <div class="flex items-center justify-between p-4 bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-lg">
                                    <div class="flex items-center gap-3">
                                        {{-- Card Brand Icon --}}
                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-xs font-bold uppercase">
                                            {{ $method->card->brand }}
                                        </div>
                                        <div>
                                            <span class="font-medium">•••• {{ $method->card->last4 }}</span>
                                            <span class="text-[#706f6c] dark:text-[#A1A09A] text-sm ml-2">
                                                Expires {{ $method->card->exp_month }}/{{ $method->card->exp_year }}
                                            </span>
                                        </div>
                                        @if ($defaultPaymentMethod && $defaultPaymentMethod->id === $method->id)
                                            <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded text-xs font-medium">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                    <form action="{{ route('billing.payment-method.remove', $method->id) }}" method="POST" onsubmit="return confirm('Remove this payment method?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">No payment methods on file.</p>
                    @endif

                    {{-- Add Payment Method Form --}}
                    <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-6">
                        <h3 class="font-medium mb-4">Add Payment Method</h3>
                        <form id="payment-form" action="{{ route('billing.payment-method.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" id="payment-method-input">

                            <div class="mb-4">
                                <label for="card-holder-name" class="block text-sm font-medium mb-2">Cardholder Name</label>
                                <input type="text" id="card-holder-name" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] focus:ring-2 focus:ring-[#f53003] focus:border-transparent" placeholder="Name on card">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Card Details</label>
                                <div id="card-element" class="p-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a]">
                                    <!-- Stripe Elements will be inserted here -->
                                </div>
                                <div id="card-errors" class="text-red-600 text-sm mt-2" role="alert"></div>
                            </div>

                            <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}" class="px-4 py-2 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition-opacity">
                                Add Payment Method
                            </button>
                        </form>
                    </div>
                </section>

                {{-- Apply Coupon --}}
                @if ($subscription && $subscription->active())
                    <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4">Promotion Code</h2>
                        <form action="{{ route('billing.coupon.apply') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="text" name="coupon" placeholder="Enter coupon code" class="flex-1 px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] focus:ring-2 focus:ring-[#f53003] focus:border-transparent">
                            <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition-opacity">
                                Apply
                            </button>
                        </form>
                    </section>
                @endif
            </div>

            {{-- Sidebar (1 column) --}}
            <div class="space-y-8">
                {{-- Recent Invoices --}}
                <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Recent Invoices</h2>
                        <a href="{{ route('billing.invoices') }}" class="text-sm text-[#f53003] hover:underline">View All</a>
                    </div>

                    @if ($invoices->count() > 0)
                        <div class="space-y-3">
                            @foreach ($invoices->take(5) as $invoice)
                                <div class="flex items-center justify-between py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] last:border-0">
                                    <div>
                                        <span class="font-medium">{{ $invoice->total() }}</span>
                                        <span class="text-[#706f6c] dark:text-[#A1A09A] text-sm block">
                                            {{ $invoice->date()->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('billing.invoices.download', $invoice->id) }}" class="text-sm text-[#f53003] hover:underline">
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">No invoices yet.</p>
                    @endif
                </section>

                {{-- Quick Links --}}
                <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Quick Links</h2>
                    <div class="space-y-2">
                        <a href="{{ route('billing.plans') }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                            <svg class="w-5 h-5 text-[#706f6c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            View All Plans
                        </a>
                        <a href="{{ route('billing.invoices') }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                            <svg class="w-5 h-5 text-[#706f6c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Invoice History
                        </a>
                        <a href="{{ route('billing.portal') }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                            <svg class="w-5 h-5 text-[#706f6c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Stripe Portal
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{-- Stripe.js for Payment Method Collection --}}
    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe with your publishable key
        const stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();

        // Create card element with styling
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#1b1b18',
                    '::placeholder': { color: '#706f6c' },
                },
            },
        });
        cardElement.mount('#card-element');

        // Handle card errors
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = event.error ? event.error.message : '';
        });

        // Handle form submission
        const form = document.getElementById('payment-form');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            cardButton.disabled = true;
            cardButton.textContent = 'Processing...';

            const cardHolderName = document.getElementById('card-holder-name').value;

            const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName }
                }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                cardButton.disabled = false;
                cardButton.textContent = 'Add Payment Method';
            } else {
                document.getElementById('payment-method-input').value = setupIntent.payment_method;
                form.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>
