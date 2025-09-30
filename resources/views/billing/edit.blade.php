<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Invoice #{{ $invoice->invoice_number }}</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('billing.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Invoice Details</h2>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Billing Address</label>
                    <input type="text" name="billing_address"
                           value="{{ old('billing_address', $invoice->billing_details['billing_address'] ?? '') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">Services</h3>
                <div class="space-y-4">
                    @foreach($invoice->items as $index => $item)
                        <div class="flex items-center justify-between p-4 border rounded-lg bg-gray-50">
                            <div>
                                <label class="block text-gray-700 mb-1">Service Name</label>
                                <input type="text" name="services[{{ $index }}][service_name]"
                                       value="{{ old("services.$index.service_name", $item->service_name) }}"
                                       class="border-gray-300 rounded-md w-full" required>
                                <input type="hidden" name="services[{{ $index }}][service_id]" value="{{ $item->service_id }}">
                            </div>
                            <div class="ml-4">
                                <label class="block text-gray-700 mb-1">Quantity</label>
                                <input type="number" name="services[{{ $index }}][quantity]"
                                       value="{{ old("services.$index.quantity", $item->quantity) }}"
                                       class="border-gray-300 rounded-md w-24" min="1" required>
                            </div>
                            <div class="ml-4">
                                <label class="block text-gray-700 mb-1">Unit Price</label>
                                <input type="number" name="services[{{ $index }}][unit_price]"
                                       value="{{ old("services.$index.unit_price", $item->unit_price) }}"
                                       class="border-gray-300 rounded-md w-24" min="0" step="0.01" required>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('billing.history') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Update Invoice</button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
