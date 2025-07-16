<?php
require_once("dbs.php");

function cleanInput($value) {
    return htmlspecialchars(trim($value));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and extract inputs
    $fullName = cleanInput($_POST["full_name"] ?? '');
    $email = cleanInput($_POST["email"] ?? '');
    $phone = cleanInput($_POST["phone"] ?? '');
    $category = cleanInput($_POST["category"] ?? '');
    $numberOfTickets = (int)($_POST["numberOfTickets"] ?? 0);
    $deliveryMethod = cleanInput($_POST["deliveryMethod"] ?? '');
    $paymentMethod = cleanInput($_POST["paymentMethod"] ?? '');
    $promoCode = strtolower(trim($_POST["promoCode"] ?? ''));
    $acceptTerms = isset($_POST["acceptTerms"]) ? "Yes" : "No";

    // Price definitions
    $prices = [
        "standard" => 2500,
        "vip" => 7500,
        "balcony" => 3500,
        "student" => 1800,
        "child" => 1000
    ];

    // Detect category key
    $catKey = null;
    foreach ($prices as $key => $price) {
        if (stripos($category, $key) !== false) {
            $catKey = $key;
            break;
        }
    }

    $unitPrice = $catKey ? $prices[$catKey] : 0;
    $total = $unitPrice * $numberOfTickets;

    // Apply promo
    if ($promoCode === "dannygram") {
        $total *= 0.90;
    }

    $formattedTotal = "KSh " . number_format($total, 2);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket Checkout Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 30px;
        }
        .summary-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .summary-box h2 {
            text-align: center;
            color: #2c3e50;
        }
        .summary-box p {
            font-size: 1rem;
            margin: 8px 0;
        }
        .total {
            font-weight: bold;
            font-size: 1.2rem;
            color: green;
        }
        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="summary-box">
    <p class="success">✅ Record added successfully!</p>
    <h2>Ticket Checkout Summary</h2>

    <?php
    echo "<p><strong>Full Name:</strong> " . htmlspecialchars($fullName) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($phone) . "</p>";
    echo "<p><strong>Ticket Category:</strong> " . htmlspecialchars($category) . "</p>";
    echo "<p><strong>Number of Tickets:</strong> " . $numberOfTickets . "</p>";
    echo "<p><strong>Delivery Method:</strong> " . htmlspecialchars($deliveryMethod) . "</p>";
    echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($paymentMethod) . "</p>";
    echo "<p><strong>Promo Code:</strong> " . htmlspecialchars($promoCode ?: "None") . "</p>";
    echo "<p><strong>Accepted Terms:</strong> " . $acceptTerms . "</p>";
    echo "<p class='total'>Total Cost: {$formattedTotal}</p>";
    ?>

    <a href="view_tickets.php" class="btn">📋 View All Tickets</a>
</div>

</body>
</html>
