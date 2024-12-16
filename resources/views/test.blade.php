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
    </style>
</head>
<body>
<h2>Thanh toán bằng thẻ VISA</h2>
<h5>
    <a target="_blank" href="https://docs.stripe.com/testing?testing-method=card-numbers">Stripe Test Account</a>
</h5>

<form id="payment-form">
    <label for="card-holder-name">Tên trên thẻ</label>
    <input type="text" id="card-holder-name" placeholder="Nhập tên trên thẻ" required>
    <br>
    <label for="booking_id">Booking_id</label>
    <input type="text" id="booking_id" placeholder="booking_id" required>

    <div id="card-element"></div>
    <div id="card-errors" role="alert"></div>

    <button id="submit-button" type="button">Thanh toán</button>
</form>

<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();

    // Tạo đối tượng card element mà không yêu cầu mã ZIP
    const cardElement = elements.create('card', {
        hidePostalCode: true  // Ẩn trường mã ZIP
    });

    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardHolderName = document.getElementById('card-holder-name');
    const submitButton = document.getElementById('submit-button');

    submitButton.addEventListener('click', async () => {
        const { token, error } = await stripe.createToken(cardElement, {
            name: cardHolderName.value,
        });

        let booking_id = document.getElementById('booking_id').value;

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            fetch('/process-stripe', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    stripeToken: token.id,
                    booking_id: booking_id,
                    description: 'Thanh toán sản phẩm',
                }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.type == 'success') {
                        alert('Thanh toán thành công!');
                    } else {
                        alert('Thanh toán thất bại: ' + data.message);
                    }
                });
        }
    });
</script>

</body>
</html>
