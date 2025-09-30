<x-app-layout>
<style>
    .invoice {
        background: #ffffff !important;
        border: 1px solid #F37028 !important;
        border-left: 6px solid #F37028 !important;
    }
    .invoice h1 {
        color: #F37028 !important;
    }
    .invoice h2, .invoice h3, .invoice h4 {
        color: #F37028 !important;
    }
</style>

<div class="container mx-auto px-4 py-10">
    <div class="max-w-4xl mx-auto">
        <div class="invoice shadow-lg rounded-xl p-8">

            <!-- Header -->
            <div class="border-b border-gray-200 pb-6 mb-6 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">Invoice</h1>
                    <p class="text-gray-600 mt-2">Invoice #{{ $invoice->invoice_number }}</p>
                    <p class="text-gray-600">Date: {{ $invoice->issued_at->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                           ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
            </div>

            <!-- Customer -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold mb-2">Dear {{ $invoice->user->name }}</h2>
                <p class="text-gray-600">Please find your invoice details below:</p>
            </div>

            <!-- Services -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold mb-6">Services</h3>

                @php
                    $reportItems = $invoice->items->filter(fn($item) => optional($item->service)->category === 'report');
                    $mentoringItems = $invoice->items->filter(fn($item) => optional($item->service)->category === 'mentoring');
                @endphp

                <!-- Reports -->
                <div class="mb-8">
                    <h4 class="text-md font-medium mb-4">Report</h4>
                    @if($reportItems->count())
                        @foreach($reportItems as $item)
                            <div class="mb-3 rounded-md border border-gray-200 bg-gray-50 p-3">
                                @php
                                    $hasChildren = $item->item_details
                                        && isset($item->item_details['children'])
                                        && is_array($item->item_details['children'])
                                        && count($item->item_details['children']) > 0;
                                @endphp

                                @if($hasChildren)
                                    <!-- Parent -->
                                    <div class="text-[11px] text-gray-500 uppercase tracking-wide mb-2">Parent</div>
                                    <div class="flex items-center text-sm">
                                        <span class="w-2/3 text-gray-700">{{ $invoice->user->name }}</span>
                                        <span class="w-1/3 text-right font-medium tabular-nums">${{ number_format($item->unit_price, 2) }}</span>
                                    </div>
                                    <!-- Children -->
                                    <div class="mt-3 ml-3 text-[11px] text-gray-500 uppercase tracking-wide">Children</div>
                                    @foreach($item->item_details['children'] as $childId)
                                        @php $child = \App\Models\User::find($childId); @endphp
                                        @if($child)
                                            <div class="ml-3 flex justify-between text-sm text-gray-700">
                                                <span>{{ $child->name }}</span>
                                                <span class="font-medium tabular-nums">${{ number_format($item->unit_price, 2) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="flex items-center text-sm">
                                        <span class="w-2/3 text-gray-700">{{ $item->service_name }} @if($item->quantity > 1) x{{ $item->quantity }} @endif</span>
                                        <span class="w-1/3 text-right font-medium tabular-nums">${{ number_format($item->total_price, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-500 text-sm">No report items.</div>
                    @endif
                </div>

                <!-- Mentoring -->
                <div>
                    <h4 class="text-md font-medium mb-4">Mentoring</h4>
                    @if($mentoringItems->count())
                        @foreach($mentoringItems as $item)
                            <div class="mb-3 rounded-md border border-gray-200 bg-gray-50 p-3">
                                <div class="flex items-center text-sm">
                                    <span class="w-2/3 text-gray-700">{{ $item->service_name }} @if($item->quantity > 1) x{{ $item->quantity }} @endif</span>
                                    <span class="w-1/3 text-right font-medium tabular-nums">${{ number_format($item->total_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-500 text-sm">No mentoring items.</div>
                    @endif
                </div>
            </div>

            <!-- Total -->
            <div class="border-t border-gray-200 pt-6 mb-8">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span>Total Fee:</span>
                    <span class="text-blue-600">${{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center gap-4">
                @if($invoice->status === 'pending')
                    <a href="{{ route('billing.checkout', $invoice) }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg">
                        Checkout
                    </a>
                @elseif($invoice->status === 'paid')
                    <div class="text-center">
                        <p class="text-green-600 font-semibold">Payment Completed</p>
                        <p class="text-gray-600 text-sm">Paid on {{ $invoice->paid_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                @endif
                <a href="{{ route('billing.history') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg">
                    View All Invoices
                </a>
            </div>
        </div>

        <!-- Error -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
