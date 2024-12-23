<!DOCTYPE html>
<html>
<head>
    <title>Thanh toán bằng thẻ VISA</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        #card-element {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        #card-errors {
            color: red;
            margin-top: 10px;
        }

        #token_value {
            color: green;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<h2>Thanh toán bằng thẻ VISA</h2>
<h5>
    <a target="_blank" href="https://docs.stripe.com/testing?testing-method=card-numbers">Stripe Test Account</a>
</h5>

<form id="payment-form">
    <div id="card-element"></div>
    <div id="card-errors" role="alert"></div>
    <div class="" style="margin-top: 10px">Token Generated:</div>
    <div id="token_value" role="alert"></div>

    <button id="submit-button" type="button">Thanh toán</button>
</form>

<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        hidePostalCode: true
    });

    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardHolderName = document.getElementById('card-holder-name');
    const submitButton = document.getElementById('submit-button');

    submitButton.addEventListener('click', async () => {
        const {token, error} = await stripe.createToken(cardElement, {
            name: 'test',
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            console.log(token)

            document.getElementById('token_value').textContent = token.id;
        }
    });
</script>

</body>
</html>
