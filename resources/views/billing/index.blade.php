<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <!-- Header -->
            <div class="mb-8 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800">Select Services</h1>
                <p class="text-gray-600 mt-1 text-sm">
                    Choose the services you want to include in this invoice
                </p>
            </div>

            <form id="billing-form" method="POST" action="{{ route('billing.create-invoice') }}" class="space-y-10">
                @csrf

                <!-- Report Services -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reports</h2>
                    <div class="space-y-6">
                        @foreach($reportServices as $service)
                            <div class="p-5 border rounded-lg hover:shadow-md transition bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <label class="flex-1 flex items-start cursor-pointer">
                                        <input type="checkbox"
                                               name="services[{{ $loop->index }}][service_id]"
                                               value="{{ $service->id }}"
                                               class="service-checkbox mt-1 rounded border-gray-300 text-blue-600 mr-3"
                                               data-price="{{ $service->price }}"
                                               data-category="{{ $service->category }}"
                                               data-name="{{ $service->name }}">
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
                                <div class="mt-4 ml-7 hidden child-selection bg-blue-50 border border-blue-300 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-3 font-medium">
                                        Select children for reports ({{ $children->count() }} available):
                                    </p>
                                    <div class="space-y-2">
                                        @foreach($children as $child)
                                            <label class="flex items-center justify-between p-2 bg-white border rounded-lg hover:bg-gray-50">
                                                <div class="flex items-center">
                                                    <input type="checkbox"
                                                           name="services[{{ $loop->parent->index }}][item_details][children][]"
                                                           value="{{ $child->id }}"
                                                           class="child-checkbox rounded border-gray-300 text-blue-600 mr-2"
                                                           data-child-name="{{ $child->name }}"
                                                           data-price="{{ $service->price }}">
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
                                       name="services[{{ $loop->index }}][quantity]"
                                       value="1"
                                       class="quantity-input" disabled>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mentoring Services -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Mentoring</h2>
                    <div class="space-y-6">
                        @foreach($mentoringServices as $service)
                            <div class="p-5 border rounded-lg hover:shadow-md transition bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <label class="flex-1 flex items-start cursor-pointer">
                                        <input type="checkbox"
                                               name="services[{{ $loop->index + $reportServices->count() }}][service_id]"
                                               value="{{ $service->id }}"
                                               class="service-checkbox mt-1 rounded border-gray-300 text-blue-600 mr-3"
                                               data-price="{{ $service->price }}"
                                               data-category="{{ $service->category }}"
                                               data-name="{{ $service->name }}">
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
                                       name="services[{{ $loop->index + $reportServices->count() }}][quantity]"
                                       value="1"
                                       class="quantity-input" disabled>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total and Submit -->
                <div class="bg-gray-100 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-semibold text-gray-800">Total Fee:</span>
                        <span id="total-amount" class="text-2xl font-bold text-blue-600">$0.00</span>
                    </div>
                    <button type="submit"
                            id="create-invoice-btn"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
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

                const childCheckboxes = checkbox.closest('.border').querySelectorAll('.child-checkbox');
                if (childCheckboxes.length > 0) {
                    const checkedChildren = Array.from(childCheckboxes).filter(c => c.checked);
                    const quantity = Math.max(1, checkedChildren.length);
                    total += price * quantity;

                    const quantityInput = checkbox.closest('.border').querySelector('.quantity-input');
                    if (quantityInput) quantityInput.value = quantity;
                } else {
                    total += price;
                }
            } else {

                const childCheckboxes = checkbox.closest('.border').querySelectorAll('.child-checkbox');
                childCheckboxes.forEach(c => {
                    if (c.checked) {
                        hasSelection = true;
                        const childPrice = parseFloat(c.dataset.price) || 0;
                        total += childPrice;
                    }
                });
            }
        });

        totalAmountElement.textContent = '$' + total.toFixed(2);
        createInvoiceBtn.disabled = !hasSelection;
    }

    serviceCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const childSelection = this.closest('.border').querySelector('.child-selection');
            const quantityInput = this.closest('.border').querySelector('.quantity-input');

            if (quantityInput) quantityInput.disabled = !this.checked;

            if (childSelection) {
                if (this.checked) {

                    childSelection.classList.remove('hidden');
                    childSelection.querySelectorAll('.child-checkbox').forEach(cb => cb.checked = true);
                } else {

                    childSelection.classList.remove('hidden');
                    childSelection.querySelectorAll('.child-checkbox').forEach(cb => cb.checked = false);
                }
            }

            updateTotal();
        });
    });

    document.querySelectorAll('.child-checkbox').forEach(cb =>
        cb.addEventListener('change', updateTotal)
    );

    serviceCheckboxes.forEach(cb => {
        const quantityInput = cb.closest('.border').querySelector('.quantity-input');
        if (quantityInput) quantityInput.disabled = !cb.checked;
    });

    updateTotal();
});
</script>


</x-app-layout>
