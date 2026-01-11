{{--
    Invoice History View

    Demonstrates Laravel Cashier features:
    - Listing all user invoices from Stripe
    - Invoice PDF download
    - Upcoming invoice preview
    - Invoice status display

    Variables passed from BillingController@invoices:
    - $invoices: Collection of user's invoices from Stripe
    - $upcomingInvoice: Preview of next invoice (if subscribed)
--}}
<x-app-layout title="Invoice History">
    <div class="space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Invoice History</h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A] mt-1">View and download your billing invoices</p>
            </div>
            <a href="{{ route('billing.index') }}" class="inline-flex items-center gap-2 text-[#f53003] hover:underline">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Billing
            </a>
        </div>

        {{-- Upcoming Invoice Preview --}}
        @if ($upcomingInvoice)
            <section class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Upcoming Invoice</h2>
                        <p class="text-blue-700 dark:text-blue-300 text-sm mt-1">
                            Your next invoice will be for <strong>{{ $upcomingInvoice->total() }}</strong>
                            on <strong>{{ $upcomingInvoice->date()?->format('M d, Y') ?? 'your next billing date' }}</strong>.
                        </p>
                    </div>
                </div>
            </section>
        @endif

        {{-- Invoice List --}}
        <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl overflow-hidden">
            @if ($invoices->count() > 0)
                {{-- Table Header --}}
                <div class="hidden sm:grid sm:grid-cols-5 gap-4 px-6 py-3 bg-[#FDFDFC] dark:bg-[#0a0a0a] border-b border-[#e3e3e0] dark:border-[#3E3E3A] text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">
                    <div>Invoice</div>
                    <div>Date</div>
                    <div>Amount</div>
                    <div>Status</div>
                    <div class="text-right">Actions</div>
                </div>

                {{-- Invoice Rows --}}
                <div class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @foreach ($invoices as $invoice)
                        <div class="grid sm:grid-cols-5 gap-4 px-6 py-4 items-center hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                            {{-- Invoice Number --}}
                            <div>
                                <span class="font-medium">{{ $invoice->number ?? 'N/A' }}</span>
                                <span class="sm:hidden text-[#706f6c] dark:text-[#A1A09A] text-sm block">
                                    {{ $invoice->date()->format('M d, Y') }}
                                </span>
                            </div>

                            {{-- Date --}}
                            <div class="hidden sm:block text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $invoice->date()->format('M d, Y') }}
                            </div>

                            {{-- Amount --}}
                            <div class="font-semibold">
                                {{ $invoice->total() }}
                            </div>

                            {{-- Status --}}
                            <div>
                                @if ($invoice->paid)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-3 sm:justify-end">
                                <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-white transition-colors">
                                    View
                                </a>
                                <a href="{{ route('billing.invoices.download', $invoice->id) }}" class="text-sm text-[#f53003] hover:underline">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-[#706f6c] dark:text-[#A1A09A] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium mb-2">No Invoices Yet</h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">
                        Your invoices will appear here once you subscribe to a plan.
                    </p>
                    <a href="{{ route('billing.plans') }}" class="inline-flex px-4 py-2 bg-[#f53003] text-white rounded-lg hover:bg-[#d42a03] transition-colors">
                        View Plans
                    </a>
                </div>
            @endif
        </section>

        {{-- Invoice Information --}}
        <section class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">About Your Invoices</h2>
            <div class="grid md:grid-cols-2 gap-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                <div>
                    <h3 class="font-medium text-[#1b1b18] dark:text-white mb-2">Invoice Generation</h3>
                    <p>
                        Invoices are automatically generated by Stripe at the end of each billing period.
                        You'll receive an email notification when a new invoice is available.
                    </p>
                </div>
                <div>
                    <h3 class="font-medium text-[#1b1b18] dark:text-white mb-2">PDF Downloads</h3>
                    <p>
                        Download PDF invoices for your records or accounting purposes.
                        PDFs include all transaction details and can be used for tax documentation.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
