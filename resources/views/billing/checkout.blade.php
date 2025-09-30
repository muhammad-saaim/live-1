<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl p-8">

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Checkout</h1>
                <p class="text-sm text-gray-500">Pay for Invoice <span class="font-semibold">#{{ $invoice->invoice_number }}</span></p>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Order Summary</h3>
                <div class="space-y-2">
                    @foreach($invoice->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->service_name }}</span>
                            <span class="text-gray-900 font-medium">${{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between text-lg font-bold">
                    <span class="text-gray-800">Total:</span>
                    <span class="text-blue-600">${{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Payment Form -->
            <form id="payment-form" class="space-y-6">
                @csrf
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Credit Card Details</h3>
                    <div id="card-element" class="p-3 border border-gray-300 rounded-md"></div>
                    <p id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('billing.invoice', $invoice) }}"
                       class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                        ← Back
                    </a>

                    <button type="submit" id="submit-payment"
                            class="px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg flex items-center gap-2 disabled:opacity-50 transition">
                        <span id="button-text">Pay ${{ number_format($invoice->total_amount, 2) }}</span>
                        <div id="spinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </form>

            @if(session('error'))
                <div class="mt-4 bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Stripe JavaScript -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#374151',
                '::placeholder': { color: '#9CA3AF' },
            },
            invalid: { color: '#DC2626' },
        },
    });

    cardElement.mount('#card-element');

    cardElement.on('change', ({error}) => {
        document.getElementById('card-errors').textContent = error ? error.message : '';
    });

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');

        const {error, paymentIntent} = await stripe.confirmCardPayment(
            '{{ $paymentIntent->client_secret }}',
            {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ $invoice->user->name }}',
                        email: '{{ $invoice->user->email }}',
                    },
                }
            }
        );

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        } else {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("billing.process-payment", $invoice) }}';

            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="payment_intent_id" value="${paymentIntent.id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
</x-app-layout>
