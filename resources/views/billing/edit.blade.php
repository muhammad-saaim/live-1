<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <!-- Header -->
            <div class="mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800">
                    Edit Invoice #{{ $invoice->invoice_number }}
                </h1>
                <p class="text-gray-600 text-sm mt-1">
                    Update invoice details and services
                </p>
            </div>

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('billing.update', $invoice) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Invoice Details -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Invoice Details</h2>
                    <label class="block text-gray-700 mb-2">Billing Address</label>
                    <input type="text"
                           name="billing_address"
                           value="{{ old('billing_address', $invoice->billing_details['billing_address'] ?? '') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Services</h3>
                    <div class="space-y-4">
                        @foreach($invoice->items as $index => $item)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg bg-gray-50">
                                <div>
                                    <label class="block text-gray-700 text-sm mb-1">Service Name</label>
                                    <input type="text"
                                           name="services[{{ $index }}][service_name]"
                                           value="{{ old("services.$index.service_name", $item->service_name) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <input type="hidden"
                                           name="services[{{ $index }}][service_id]"
                                           value="{{ $item->service_id }}">
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm mb-1">Quantity</label>
                                    <input type="number"
                                           name="services[{{ $index }}][quantity]"
                                           value="{{ old("services.$index.quantity", $item->quantity) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           min="1" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm mb-1">Unit Price</label>
                                    <input type="number"
                                           name="services[{{ $index }}][unit_price]"
                                           value="{{ old("services.$index.unit_price", $item->unit_price) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           min="0" step="0.01" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('billing.history') }}"
                       class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition">
                        Update Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
