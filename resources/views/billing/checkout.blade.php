<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Checkout</h1>
                <p class="text-gray-600">Complete your payment for Invoice #{{ $invoice->invoice_number }}</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                @foreach($invoice->items as $item)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">{{ $item->service_name }}</span>
                        <span class="text-gray-900 font-medium">${{ number_format($item->total_price, 2) }}</span>
                    </div>
                @endforeach
                <div class="border-t border-gray-300 mt-4 pt-4">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span class="text-gray-800">Total:</span>
                        <span class="text-blue-600">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form id="payment-form">
                @csrf
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Enter Credit Card Information</h3>
                
                <div class="space-y-4">
                    <!-- Stripe Elements will be inserted here -->
                    <div id="card-element" class="p-3 border border-gray-300 rounded-md">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    
                    <!-- Used to display form errors -->
                    <div id="card-errors" role="alert" class="text-red-500 text-sm"></div>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('billing.invoice', $invoice) }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-md">
                        Back to Invoice
                    </a>
                    <button type="submit" id="submit-payment" 
                            class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-md disabled:opacity-50">
                        <span id="button-text">Pay ${{ number_format($invoice->total_amount, 2) }}</span>
                        <div id="spinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </div>
                    </button>
                </div>
            </form>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Stripe JavaScript -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();

    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    // Mount card element
    cardElement.mount('#card-element');

    // Handle real-time validation errors from the card Element
    cardElement.on('change', ({error}) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Disable submit button and show spinner
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
            // Show error to customer
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;

            // Re-enable submit button
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        } else {
            // Payment succeeded, redirect to success page
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("billing.process-payment", $invoice) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const paymentIntentId = document.createElement('input');
            paymentIntentId.type = 'hidden';
            paymentIntentId.name = 'payment_intent_id';
            paymentIntentId.value = paymentIntent.id;
            form.appendChild(paymentIntentId);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
</x-app-layout>
