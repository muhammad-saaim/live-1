<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Select Services for Invoice</h1>



        <form id="billing-form" method="POST" action="{{ route('billing.create-invoice') }}">
            @csrf

            <!-- Report Services -->
            <div class="invoice shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reports</h2>

                @foreach($reportServices as $service)
                    <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="services[{{ $loop->index }}][service_id]"
                                           value="{{ $service->id }}"
                                           class="service-checkbox rounded border-gray-300 text-blue-600 mr-3"
                                           data-price="{{ $service->price }}"
                                           data-category="{{ $service->category }}"
                                           data-name="{{ $service->name }}">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $service->name }}</h3>
                                        @if($service->description)
                                            <p class="text-gray-600">{{ $service->description }}</p>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-gray-900">${{ number_format($service->price, 2) }}</span>
                            </div>
                        </div>

                        <!-- Child selection for child reports -->
                        {{-- @if(str_contains($service->name, 'Child') && $children->count() > 0) --}}
                            <div class="mt-4 ml-8 child-selection" id="child-selection-{{ $loop->index }}" style="display: block; background: #f0f9ff; padding: 10px; border: 1px solid #0ea5e9;">
                                <p class="text-sm text-gray-600 mb-2">Select children for reports ({{ $children->count() }} available):</p>
                               @foreach($children as $child)
    <label class="flex items-center justify-between mb-2 border p-2 rounded-lg bg-white">
        <div class="flex items-center">
            <input type="checkbox"
                   name="services[{{ $loop->parent->index }}][item_details][children][]"
                   value="{{ $child->id }}"
                   class="child-checkbox rounded border-gray-300 text-blue-600 mr-2"
                   data-child-name="{{ $child->name }}"
                   data-price="{{ $service->price }}">
            <span class="text-sm text-gray-700">
                {{ $child->name }} Report
            </span>
        </div>
        <span class="text-sm font-semibold text-gray-900">${{ number_format($service->price, 2) }}</span>
    </label>
@endforeach
                            </div>
                        {{-- @endif --}}



                        <input type="hidden" name="services[{{ $loop->index }}][quantity]" value="1" class="quantity-input" disabled>
                    </div>
                @endforeach
            </div>

            <!-- Mentoring Services -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Mentoring</h2>

                @foreach($mentoringServices as $service)
                    <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="services[{{ $loop->index + $reportServices->count() }}][service_id]"
                                           value="{{ $service->id }}"
                                           class="service-checkbox rounded border-gray-300 text-blue-600 mr-3"
                                           data-price="{{ $service->price }}"
                                           data-category="{{ $service->category }}"
                                           data-name="{{ $service->name }}">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $service->name }}</h3>
                                        @if($service->description)
                                            <p class="text-gray-600">{{ $service->description }}</p>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-gray-900">${{ number_format($service->price, 2) }}</span>
                            </div>
                        </div>
                        <input type="hidden" name="services[{{ $loop->index + $reportServices->count() }}][quantity]" value="1" class="quantity-input" disabled>
                    </div>
                @endforeach
            </div>

            <!-- Total and Submit -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xl font-semibold text-gray-800">Total Fee:</span>
                    <span id="total-amount" class="text-2xl font-bold text-blue-600">$0.00</span>
                </div>

                <button type="submit" id="create-invoice-btn"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const totalAmountElement = document.getElementById('total-amount');
    const createInvoiceBtn = document.getElementById('create-invoice-btn');

    function updateTotal() {
        let total = 0;
        let hasSelection = false;

        serviceCheckboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                hasSelection = true;
                const price = parseFloat(checkbox.dataset.price);

                const childCheckboxes = checkbox.closest('.border-b').querySelectorAll('.child-checkbox');
                if (childCheckboxes.length > 0) {
                    // Count selected children
                    const checkedChildren = Array.from(childCheckboxes).filter(c => c.checked);
                    const quantity = Math.max(1, checkedChildren.length);
                    total += price * quantity;

                    // Update quantity input
                    const quantityInput = checkbox.closest('.border-b').querySelector('.quantity-input');
                    if (quantityInput) quantityInput.value = quantity;
                } else {
                    total += price;
                }
            }
        });

        totalAmountElement.textContent = '$' + total.toFixed(2);
        createInvoiceBtn.disabled = !hasSelection;
    }

    serviceCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const childSelection = this.closest('.border-b').querySelector('.child-selection');
            const quantityInput = this.closest('.border-b').querySelector('.quantity-input');

            if (quantityInput) quantityInput.disabled = !this.checked;

            if (childSelection) {
                if (this.checked) {
                    childSelection.style.display = 'block';
                    // ✅ Check all child checkboxes automatically
                    childSelection.querySelectorAll('.child-checkbox').forEach(function(childCheckbox) {
                        childCheckbox.checked = true;
                    });
                } else {
                    childSelection.style.display = 'none';
                    childSelection.querySelectorAll('.child-checkbox').forEach(function(childCheckbox) {
                        childCheckbox.checked = false;
                    });
                }
            }
            updateTotal();
        });
    });

    // Handle child checkbox changes to update total only
    document.querySelectorAll('.child-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotal);
    });

    // Initialize disabled state for quantity inputs on load
    serviceCheckboxes.forEach(function(checkbox) {
        const quantityInput = checkbox.closest('.border-b').querySelector('.quantity-input');
        if (quantityInput) quantityInput.disabled = !checkbox.checked;
    });

    updateTotal();
});
</script>
</x-app-layout>
