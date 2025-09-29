<x-app-layout>
<style>
.invoice {
    background:rgb(184, 250, 2) !important;
    border-left: 4px solid #F37028 !important;
    border-top: 1px solid #F37028 !important;
    border-right: 1px solid #F37028 !important;
    border-bottom: 1px solid #F37028 !important;
}
.invoice h1 {
    color: #F37028 !important;
}
.invoice h2, .invoice h3 {
    color: #F37028 !important;
}
</style>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="invoice shadow-lg rounded-lg p-8">
            <!-- Invoice Header -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Invoice</h1>
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
            </div>

            <!-- Customer Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Dear {{ $invoice->user->name }}</h2>
                <p class="text-gray-600">Your invoice</p>
            </div>

            <!-- Services Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Services</h3>

                @php
                    $reportItems = $invoice->items->filter(function ($item) {
                        return optional($item->service)->category === 'report';
                    });
                    $mentoringItems = $invoice->items->filter(function ($item) {
                        return optional($item->service)->category === 'mentoring';
                    });
                @endphp

                <!-- Report Services -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-700 mb-4">Report</h4>

                    @if($reportItems->count() > 0)
                        @foreach($reportItems as $item)
                            <div class="mb-3 rounded-md border border-gray-200 bg-gray-50 p-3">
                                @php $hasChildren = $item->item_details && isset($item->item_details['children']) && is_array($item->item_details['children']) && count($item->item_details['children']) > 0; @endphp
                                @if($hasChildren)
                                    <div class="text-[11px] text-gray-500 uppercase tracking-wide mb-2">Parent</div>
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-700 w-2/3 pr-3">{{ $invoice->user->name }}</span>
                                        <span class="text-gray-900 font-medium w-1/3 text-right tabular-nums">${{ number_format($item->unit_price, 2) }}</span>
                                    </div>
                                    <div class="mt-3 ml-3 text-[11px] text-gray-500 uppercase tracking-wide">Children</div>
                                    @foreach($item->item_details['children'] as $childId)
                                        @php $child = \App\Models\User::find($childId); @endphp
                                        @if($child)
                                            <div class="ml-3 text-sm text-gray-700 flex items-center">
                                                <span class="w-2/3 pr-3">{{ $child->name }}</span>
                                                <span class="text-gray-900 font-medium w-1/3 text-right tabular-nums">${{ number_format($item->unit_price, 2) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-700 w-2/3 pr-3">{{ $item->service_name }} @if($item->quantity > 1) x{{ $item->quantity }} @endif</span>
                                        <span class="text-gray-900 font-medium w-1/3 text-right tabular-nums">${{ number_format($item->total_price, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-500 text-sm">No report items.</div>
                    @endif

                    <!-- Explanatory text -->
                 
                </div>

                <!-- Mentoring Services -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-700 mb-4 ml-8">Mentoring</h4>

                    @if($mentoringItems->count() > 0)
                        <div class="ml-8">
                            @foreach($mentoringItems as $item)
                                <div class="mb-3 rounded-md border border-gray-200 bg-gray-50 p-3">
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-700 w-2/3 pr-3">{{ $item->service_name }} @if($item->quantity > 1) x{{ $item->quantity }} @endif</span>
                                        <span class="text-gray-900 font-medium w-1/3 text-right tabular-nums">${{ number_format($item->total_price, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ml-8 text-gray-500 text-sm">No mentoring items.</div>
                    @endif
                </div>
            </div>

            <!-- Total -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span class="text-gray-800">Total fee:</span>
                    <span class="text-blue-600">${{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-5flex justify-center space-x-4">
                @if($invoice->status === 'pending')
                    <a href="{{ route('billing.checkout', $invoice) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg">
                        Checkout
                    </a>
                @elseif($invoice->status === 'paid')
                    <div class="text-center">
                        <p class="text-green-600 font-semibold mb-2">Payment Completed</p>
                        <p class="text-gray-600 text-sm">Paid on {{ $invoice->paid_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                @endif
                
                <a href="{{ route('billing.history') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg">
                    View All Invoices
                </a>
            </div>
        </div>

        

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
