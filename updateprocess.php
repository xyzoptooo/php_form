<?php
require_once("dbs.php");

function cleanInput($value) {
    return htmlspecialchars(trim($value));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int)$_POST["id"];

    // Sanitize inputs
    $fullName = cleanInput($_POST["full_name"] ?? '');
    $emailRaw = trim($_POST['email'] ?? '');
    $phone = preg_replace('/[^0-9+]/', '', $_POST["phone"] ?? '');
    $category = cleanInput($_POST["category"] ?? '');
    $tickets = (int)$_POST["tickets"] ?? '';
    $delivery = cleanInput($_POST["delivery_method"] ?? '');
    $payment = cleanInput($_POST["payment_method"] ?? '');
    $promo = strtolower(trim(preg_replace("/[^a-zA-Z0-9]/", "", $_POST["promo_code"] ?? '')));

    // Validate email
   $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "<p style='color:red;'> Invalid email address provided!</p>";
    }

    // Define ticket prices
    $unitPrices = [
        "standard" => 2500,
        "vip" => 7500,
        "balcony" => 3500,
        "student" => 1800,
        "child" => 1000,
    ];

    // Determine category key
    $categoryKey = null;
    $categoryLower = strtolower($category);

    foreach ($unitPrices as $key => $price) {
        if (strpos($categoryLower, $key) !== false) {
            $categoryKey = $key;
            break;
        }
    }

    // Compute total
    $unitPrice = ($categoryKey && isset($unitPrices[$categoryKey])) ? $unitPrices[$categoryKey] : 0;
    $total = $unitPrice * $tickets;

    // Apply promo
    if ($promo === "dannygram") {
        $total *= 0.90;
    }

    // Update the database
    $stmt = $myconn->prepare(
        "UPDATE ticket_orders SET full_name=?, email=?, phone=?, category=?, tickets=?, delivery_method=?, payment_method=?, promo_code=?, total_cost=? WHERE id=?"
    );
    $stmt->bind_param(
        "ssssisssdi", 
        $fullName, $email, $phone, $category, $tickets, $delivery, $payment, $promo, $total, $id
    );

    if ($stmt->execute()) {
        echo "<p style='color:green;'> Ticket Updated Successfully</p>";
    } else {
        echo "<p style='color:red;'> Failed to update ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "Invalid Request.";
}
?>
<a href="view_tickets.php"> Back to Tickets</a>
