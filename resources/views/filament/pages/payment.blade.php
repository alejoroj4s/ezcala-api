<x-filament::page>
    <form id="payment-form" method="POST" action="{{ route('filament.pages.payment.createSubscription') }}">
        @csrf
        <div id="card-element" class="my-4"></div>
        <input type="hidden" name="payment_method" id="payment-method">
        <input type="hidden" name="organization_id" value="{{ auth()->user()->organizations->first()->id }}">
        <button id="submit" type="submit" class="btn btn-primary">Subscribe</button>
        <div id="card-errors" role="alert"></div>
    </form>

    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement);

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                document.getElementById('payment-method').value = paymentMethod.id;
                form.submit();
            }
        });
    </script>
</x-filament::page>