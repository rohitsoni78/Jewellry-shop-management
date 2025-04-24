<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 100px;
        }
        
        header {
            background-color: #333;
            padding: 10px;
            color: #fff;
            text-align: center;
        }

        .payment-container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(36, 6, 145, 0.1);
            margin: auto;
        }
        h1 {
            font-size: 24px;
            color: #390ccc;
        }
        input, button, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #b42e2e;
            border-radius: 5px;
        }
        button {
            background: #030115;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #4609bf;
        }
        .hidden {
            display: none;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: left;
            margin-bottom: 5px;
        }
        .qr-code {
            margin-top: 15px;
            width: 100%;
            max-width: 250px;
        }
    </style>
</head>
<body>
 <header>
    <h1>Payment</h1>
    <nav>
        <a href="index.php" style="color: #fff; margin: 0 15px; text-decoration: none;">Home</a>
    </nav>
</header>

    <div class="payment-container">
        <h1>Secure Payment</h1>
        <h2>Choose Payment Method</h2>
        <select id="payment-method">
            <option value="card">Credit/Debit Card</option>
            <option value="paypal">PayPal</option>
            <option value="upi">UPI</option>
            <option value="qr">Scan QR Code</option>
        </select>

        <!-- Card Payment -->
        <div id="card-section">
            <input type="text" id="card holder-name" placeholder="Card holder Name" oninput="this.value = this.value.replace(/[^A-Za-z]/g, '')">
            <span class="error" id="cardholder-error"></span>

            <input type="text" id="card-number" placeholder="Card Number" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <span class="error" id="card-number-error"></span>

            <!-- <input type="text" id="expiry-date" placeholder="Expiry Date (MM/YY)" maxlength="5"> -->
            <input type="month" id="expiry-date" placeholder="Expiry Date (MM/DD/YY)" required>

            <span class="error" id="expiry-error"></span>

            <input type="text" id="cvv" placeholder="CVV" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <span class="error" id="cvv-error" ></span>

            <button onclick="validatePayment('Card')">Pay Now</button>
        </div>

        <!-- PayPal Payment -->
        <div id="paypal-section" class="hidden">
            <p>Click below to pay via PayPal</p>
            <button onclick="processPayment('PayPal')">Pay with PayPal</button>
        </div>

        <!-- UPI Payment -->
        <div id="upi-section" class="hidden">
            <input type="text" id="upi-id" placeholder="Enter UPI ID (e.g., user@upi)">
            <span class="error" id="upi-error"></span>
            <button onclick="validatePayment('UPI')">Pay with UPI</button>
        </div>

        <!-- QR Code Payment -->
        <div id="qr-section" class="hidden">
            <p>Scan the QR Code to Pay</p>
            <img class="qr-code" src="Qrr.jpeg" alt="QR Code">
        </div>

    </div>

    <script>
        const paymentMethod = document.getElementById("payment-method");
        const sections = {
            card: document.getElementById("card-section"),
            paypal: document.getElementById("paypal-section"),
            upi: document.getElementById("upi-section"),
            qr: document.getElementById("qr-section")
        };

        paymentMethod.addEventListener("change", function() {
            Object.values(sections).forEach(section => section.classList.add("hidden"));
            sections[this.value].classList.remove("hidden");
        });

        function validatePayment(method) {
            let valid = true;

            if (method === 'Card') {
                const cardholder = document.getElementById('cardholder-name').value.trim();
                const cardNumber = document.getElementById('card-number').value.trim();
                const expiryDate = document.getElementById('expiry-date').value.trim();
                const cvv = document.getElementById('cvv').value.trim();

                const cardNumberPattern = /^\d{16}$/;
                const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
                const cvvPattern = /^\d{3}$/;

                document.getElementById("cardholder-error").innerText = cardholder ? "" : "Cardholder name cannot be empty.";
                document.getElementById("card-number-error").innerText = cardNumberPattern.test(cardNumber) ? "" : "Enter a valid 16-digit card number.";
                document.getElementById("expiry-error").innerText = expiryPattern.test(expiryDate) ? "" : "Enter a valid expiry date (MM/YY).";
                document.getElementById("cvv-error").innerText = cvvPattern.test(cvv) ? "" : "Enter a valid 3-digit CVV.";

                valid = cardholder && cardNumberPattern.test(cardNumber) && expiryPattern.test(expiryDate) && cvvPattern.test(cvv);
            }

            if (method === 'UPI') {
                const upiId = document.getElementById('upi-id').value.trim();
                const upiPattern = /^[a-zA-Z0-9.\-_]+@[a-zA-Z]+$/;

                document.getElementById("upi-error").innerText = upiPattern.test(upiId) ? "" : "Enter a valid UPI ID (e.g., user@upi).";
                valid = upiPattern.test(upiId);
            }

            if (valid) {
                processPayment(method);
            }
        }

        function processPayment(method) {
            alert(`Payment via ${method} is being processed!`);
        }
    </script>

</body>
</html>
