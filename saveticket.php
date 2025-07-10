<?php
require_once('dbs.php'); // Database connection

//sanitize and validate form data

function cleanInput($value) {
    return  htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}
 
$category = cleanInput($_POST['category'] ?? '');
$numberOfTickets = isset($_POST['numberOfTickets']) ? (int)$_POST['numberOfTickets'] : 0;
$fullName = cleanInput($_POST['fullName'] ?? '');
$emailRaw = trim($_POST['email'] ?? '');
$phone = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');     //The ^ in this context is used to exclude certain characters. It tells preg_replace() to remove everything from the phone number that is not a digit or a plus sign.

$deliveryMethod = cleanInput($_POST['paymentMethod'] ?? '');
$paymentMethod = cleanInput($_POST['paymentMethod'] ?? '');
$acceptTerms = isset($_POST['acceptTerms']) ? "Yes" : "No" ;
$promoCode = strtolower(trim(preg_replace("/[^a-zA-Z0-9]/", "", $_POST['promoCode'] ?? '')));


//email vadlidate
$email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
 if (!$email) {
    echo "<p style='color:red;'> Invalid email address provided!</p>";

 }


// Receive and sanitize form data
// $category = $_POST['category'];
// $numberOfTickets = isset($_POST['numberOfTickets']) ? (int)$_POST['numberOfTickets'] : 0;
// $fullName = $_POST['fullName'];
// $email = $_POST['email'];
// $phone = $_POST['phone'];
// $deliveryMethod = $_POST['deliveryMethod'];
// $paymentMethod = $_POST['paymentMethod'];
// $acceptTerms = isset($_POST['acceptTerms']) ? "Yes" : "No";
// $promoCode = trim(strtolower($_POST['promoCode'] ?? ''));

// Define ticket prices
$unitPrices = [
    "standard" => 2500,
    "vip" => 7500,
    "balcony" => 3500,
    "student" => 1800,
    "child" => 1000,
];

// Match category string to key
$categoryKey = null;
$categoryLower = strtolower($category);

if (strpos($categoryLower, 'standard') !== false) {
    $categoryKey = 'standard';
} elseif (strpos($categoryLower, 'vip') !== false) {
    $categoryKey = 'vip';
} elseif (strpos($categoryLower, 'balcony') !== false) {
    $categoryKey = 'balcony';
} elseif (strpos($categoryLower, 'student') !== false) {
    $categoryKey = 'student';
} elseif (strpos($categoryLower, 'child') !== false) {
    $categoryKey = 'child';
}

// Calculate total
$unitPrice = ($categoryKey && isset($unitPrices[$categoryKey])) ? $unitPrices[$categoryKey] : 0;
$total = $unitPrice * $numberOfTickets;

// Apply discount
$discountApplied = false;
if ($promoCode === "dannygram") {
    $total *= 0.90;
    $discountApplied = true;
}

// Save using prepared statement
$insertStatment = $myconn->prepare(
    "INSERT INTO ticket_orders 
    (full_name, email, phone, category, tickets, delivery_method, payment_method, promo_code, total_cost)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if ($insertStatment) {
    $insertStatment->bind_param(
        "ssssisssd",
        $fullName,
        $email,
        $phone,
        $category,
        $numberOfTickets,
        $deliveryMethod,
        $paymentMethod,
        $promoCode,
        $total
    );

    if ($insertStatment->execute()) {
        $saved = true;
    } else {
        $saved = false;
        $errorMsg = $insertStatment->error;
    }

    $insertStatment->close();
} else {
    $saved = false;
    $errorMsg = $myconn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket Summary</title>
</head>
<body>
    <h1> Ticket Checkout Summary</h1>

    <?php
    if ($saved) {
        echo "<p style='color:green;'> Record added successfully!</p>";
    } else {
        echo "<p style='color:red;'> Failed to save record: " . $errorMsg . "</p>";
    }

    echo "<p><strong>Full Name:</strong> " . htmlspecialchars($fullName) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($phone) . "</p>";
    echo "<p><strong>Ticket Category:</strong> " . htmlspecialchars($category) . "</p>";
    echo "<p><strong>Number of Tickets:</strong> " . $numberOfTickets . "</p>";
    echo "<p><strong>Delivery Method:</strong> " . htmlspecialchars($deliveryMethod) . "</p>";
    echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($paymentMethod) . "</p>";
    echo "<p><strong>Promo Code:</strong> " . htmlspecialchars($promoCode ?: "None") . "</p>";
    echo "<p><strong>Accepted Terms:</strong> " . $acceptTerms . "</p>";

    if ($discountApplied) {
        echo "<p style='color:green;'><strong>Promo Code Applied: 10% Discount</strong></p>";
    } elseif (!empty($promoCode)) {
        echo "<p style='color:red;'><strong>Invalid Promo Code</strong></p>";
    }

    echo "<h2>Total Cost: <span style='color:blue;'>KSh " . number_format($total, 2) . "</span></h2>";
    echo "<br><a href='view_tickets.php'>➡ View All Tickets</a>";
    ?>
</body>
</html>
