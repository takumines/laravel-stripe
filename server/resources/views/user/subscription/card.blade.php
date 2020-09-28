<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>サブスクリプション決済フォーム</title>
    <style>
        /**
        * The CSS shown here will not be introduced in the Quickstart guide, but shows
        * how you can use CSS to style your Element's container.
        */
        .StripeElement {
            background-color: white;
            height: 40px;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

    </style>
</head>
<body>
<script src="https://js.stripe.com/v3/"></script>
<form action="{{ route("stripeCreate") }}" method="post" id="payment-form">
    @csrf
    <div class="form-row">
        <div>
            <label for="plan">
                プラン
                <select name="plan" id="plan-element">
                    @foreach( config('services.stripe.plans') as $key => $value )
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <label for="card-element">
            カード決済フォーム
        </label>
        <div class="form-group">
            <input type="text" class="form-control" id="card-holder-name" placeholder="名義人（半角ローマ字）">
        </div>
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
        <div>
            <input type="checkbox" name="trial"  value="1">7日間の使用期間を利用する
        </div>
    </div>

    <button id="card-button" data-secret="{{ $intent->client_secret }}">
        Submit Payment
    </button>
</form>
<script type="text/javascript">
    // Create a Stripe client.
    var stripe = Stripe('{{ env("STRIPE_KEY") }}');

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var cardElement = elements.create('card', {
        style: style,
        hidePostalCode: true
    });

    // Add an instance of the card Element into the `card-element` <div>.
    cardElement.mount('#card-element');
    // Handle real-time validation errors from the card Element.
    cardElement.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    var cardHolderName = document.getElementById('card-holder-name');
    var cardButton = document.getElementById('card-button');
    var clientSecret = cardButton.dataset.secret;
    cardButton.addEventListener('click', async (e) => {
        const {setupIntent, error} = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName.value }
                }
            }
        );
            if (error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                var form = document.getElementById('payment-form');
                form.submit();
            }
        });

</script>

</body>
</html>
