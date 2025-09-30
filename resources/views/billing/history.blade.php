<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Invoice History</h1>
            <a href="{{ route('billing.index') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Create New Invoice
            </a>
        </div>

        @if($invoices->count() > 0)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Services</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $invoice->issued_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @foreach($invoice->items as $item)
                                            <div>{{ $item->service_name }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($invoice->total_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                                           ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                    <a href="{{ route('billing.invoice', $invoice) }}"
                                       class="text-indigo-600 hover:text-indigo-900">View</a>

                                    @if($invoice->status === 'pending')
                                        <a href="{{ route('billing.checkout', $invoice) }}"
                                           class="text-green-600 hover:text-green-900">Pay Now</a>
                                    @endif

                                 
                                    <a href="{{ route('billing.edit', $invoice) }}"
                                       class="text-yellow-600 hover:text-yellow-900">Edit</a>

                                    <form action="{{ route('billing.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg p-8 text-center">
                <div class="text-gray-500 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                <p class="text-gray-600 mb-4">You haven't created any invoices yet.</p>
                <a href="{{ route('billing.index') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Create Your First Invoice
                </a>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
