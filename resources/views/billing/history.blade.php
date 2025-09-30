<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Invoice History</h1>

            @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('billing.index') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">
                ➕ Create New Invoice
            </a>
            @endif
        </div>

      
        @if($invoices->count() > 0)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Services</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $invoice->issued_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @foreach($invoice->items as $item)
                                                <div>• {{ $item->service_name }}</div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                                               ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $invoice->status === 'paid' ? '✅ Paid' : ($invoice->status === 'pending' ? '⏳ Pending' : '❌ Failed') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-wrap gap-2">
                                            <!-- View -->
                                            <a href="{{ route('billing.invoice', $invoice) }}"
                                               class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium hover:bg-indigo-200">
                                                👁️ View
                                            </a>

                                            <!-- Pay (only users + pending) -->
                                            @if(Auth::user()->hasRole('user') && $invoice->status === 'pending')
                                                <a href="{{ route('billing.checkout', $invoice) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium hover:bg-green-200">
                                                    💳 Pay Now
                                                </a>
                                            @endif

                                            <!-- Admin actions -->
                                            @if(Auth::user()->hasRole('admin'))
                                                <a href="{{ route('billing.edit', $invoice) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium hover:bg-yellow-200">
                                                    ✏️ Edit
                                                </a>

                                                <form action="{{ route('billing.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium hover:bg-red-200">
                                                        🗑️ Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white shadow-md rounded-lg p-8 text-center">
                <div class="text-gray-500 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                <p class="text-gray-600 mb-4">You haven't created any invoices yet.</p>
                @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('billing.index') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">
                    ➕ Create Your First Invoice
                </a>
                @endif
            </div>
        @endif
    </div>
</div>
</x-app-layout>
