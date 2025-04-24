<?php
session_start();

// Simulating a successful payment response (in a real-world scenario, get this from the payment gateway)
$payment_status = "success"; // This should come dynamically from the gateway response
$transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;

if ($payment_status === "success" && $transaction_id) {
    echo "<h2>Payment Successful</h2>";
    echo "<p>Transaction ID: " . htmlspecialchars($transaction_id) . "</p>";
    echo "<p>Thank you for your payment!</p>";
} else {
    echo "<h2>Payment Failed</h2>";
    echo "<p>Something went wrong. Please try again.</p>";
}
?>
