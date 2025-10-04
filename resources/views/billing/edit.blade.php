<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-lg p-8">
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

            <form action="{{ route('billing.update', $invoice) }}" method="POST" class="space-y-8" id="edit-invoice-form">
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

                <!-- Report Services -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reports</h2>
                    <div class="space-y-6">
                        @foreach($reportServices as $rIndex => $service)
                            @php
                                $existingItem = $invoice->items->firstWhere('service_id', $service->id);
                                $selectedChildren = $existingItem->item_details['children'] ?? [];
                            @endphp
                            <div class="p-5 border rounded-lg hover:shadow-md transition bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <label class="flex-1 flex items-start cursor-pointer">
                                        <input type="checkbox"
                                               name="services[{{ $rIndex }}][service_id]"
                                               value="{{ $service->id }}"
                                               class="service-checkbox mt-1 rounded border-gray-300 text-blue-600 mr-3"
                                               data-price="{{ $service->price }}"
                                               data-category="{{ $service->category }}"
                                               data-name="{{ $service->name }}"
                                               {{ $existingItem ? 'checked' : '' }}>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $service->name }}</h3>
                                            @if($service->description)
                                                <p class="text-gray-600 text-sm">{{ $service->description }}</p>
                                            @endif
                                        </div>
                                    </label>
                                    <span class="text-xl font-bold text-gray-900">
                                        ${{ number_format($service->price, 2) }}
                                    </span>
                                </div>

                                <!-- Child Selection -->
                                <div class="mt-4 ml-7 child-selection {{ $existingItem ? '' : 'hidden' }} bg-blue-50 border border-blue-300 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-3 font-medium">
                                        Select children for reports ({{ $children->count() }} available):
                                    </p>
                                    <div class="space-y-2">
                                        @foreach($children as $child)
                                            <label class="flex items-center justify-between p-2 bg-white border rounded-lg hover:bg-gray-50">
                                                <div class="flex items-center">
                                                    <input type="checkbox"
                                                           name="services[{{ $rIndex }}][item_details][children][]"
                                                           value="{{ $child->id }}"
                                                           class="child-checkbox rounded border-gray-300 text-blue-600 mr-2"
                                                           data-child-name="{{ $child->name }}"
                                                           data-price="{{ $service->price }}"
                                                           {{ in_array($child->id, $selectedChildren) ? 'checked' : '' }}>
                                                    <span class="text-sm text-gray-700">{{ $child->name }} Report</span>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900">
                                                    ${{ number_format($service->price, 2) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <input type="hidden"
                                       name="services[{{ $rIndex }}][quantity]"
                                       value="{{ $existingItem->quantity ?? 1 }}"
                                       class="quantity-input" {{ $existingItem ? '' : 'disabled' }}>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mentoring Services -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Mentoring</h2>
                    <div class="space-y-6">
                        @foreach($mentoringServices as $mIndex => $service)
                            @php
                                $existingItem = $invoice->items->firstWhere('service_id', $service->id);
                            @endphp
                            <div class="p-5 border rounded-lg hover:shadow-md transition bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <label class="flex-1 flex items-start cursor-pointer">
                                        <input type="checkbox"
                                               name="services[{{ $mIndex + $reportServices->count() }}][service_id]"
                                               value="{{ $service->id }}"
                                               class="service-checkbox mt-1 rounded border-gray-300 text-blue-600 mr-3"
                                               data-price="{{ $service->price }}"
                                               data-category="{{ $service->category }}"
                                               data-name="{{ $service->name }}"
                                               {{ $existingItem ? 'checked' : '' }}>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $service->name }}</h3>
                                            @if($service->description)
                                                <p class="text-gray-600 text-sm">{{ $service->description }}</p>
                                            @endif
                                        </div>
                                    </label>
                                    <span class="text-xl font-bold text-gray-900">
                                        ${{ number_format($service->price, 2) }}
                                    </span>
                                </div>

                                <input type="hidden"
                                       name="services[{{ $mIndex + $reportServices->count() }}][quantity]"
                                       value="{{ $existingItem->quantity ?? 1 }}"
                                       class="quantity-input" {{ $existingItem ? '' : 'disabled' }}>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total and Actions -->
                <div class="bg-gray-100 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-semibold text-gray-800">Total Fee:</span>
                        <span id="total-amount" class="text-2xl font-bold text-blue-600">$0.00</span>
                    </div>

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
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const totalAmountElement = document.getElementById('total-amount');

    function updateTotal() {
        let total = 0;

        serviceCheckboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                const price = parseFloat(checkbox.dataset.price);
                const childCheckboxes = checkbox.closest('.border')?.querySelectorAll('.child-checkbox') || [];
                if (childCheckboxes.length) {
                    const checkedChildren = Array.from(childCheckboxes).filter(c => c.checked);
                    const quantity = Math.max(1, checkedChildren.length);
                    total += price * quantity;

                    const quantityInput = checkbox.closest('.border').querySelector('.quantity-input');
                    if (quantityInput) quantityInput.value = quantity;
                } else {
                    total += price;
                }
            }
        });

        totalAmountElement.textContent = '$' + total.toFixed(2);
    }

    serviceCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const childSelection = this.closest('.border')?.querySelector('.child-selection');
            const quantityInput = this.closest('.border')?.querySelector('.quantity-input');

            if (quantityInput) quantityInput.disabled = !this.checked;

            if (childSelection) {
                if (this.checked) {
                    childSelection.classList.remove('hidden');
                    childSelection.querySelectorAll('.child-checkbox').forEach(cb => cb.checked = true);
                } else {
                    childSelection.querySelectorAll('.child-checkbox').forEach(cb => cb.checked = false);
                }
            }

            updateTotal();
        });
    });

    document.querySelectorAll('.child-checkbox').forEach(cb =>
        cb.addEventListener('change', updateTotal)
    );

    // Initialize
    serviceCheckboxes.forEach(cb => {
        const quantityInput = cb.closest('.border')?.querySelector('.quantity-input');
        if (quantityInput) quantityInput.disabled = !cb.checked;
    });

    updateTotal();
});
</script>

</x-app-layout>
