<?php
require_once("dbs.php");

// Helper to sanitize inputs
function cleanInput($value) {
    return htmlspecialchars(trim($value));
}

// Only process POST requests with valid ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int)$_POST["id"];

    // Sanitize form inputs
    $fullName = cleanInput($_POST["full_name"] ?? '');
    $emailRaw = trim($_POST['email'] ?? '');
    $phone = preg_replace('/[^0-9+]/', '', $_POST["phone"] ?? '');
    $category = cleanInput($_POST["category"] ?? '');
    $tickets = (int)($_POST["tickets"] ?? 0);
    $delivery = cleanInput($_POST["delivery_method"] ?? '');
    $payment = cleanInput($_POST["payment_method"] ?? '');
    $promo = strtolower(trim(preg_replace("/[^a-zA-Z0-9]/", "", $_POST["promo_code"] ?? '')));
    $acceptTerms = isset($_POST["acceptTerms"]) ? "Yes" : "No";

    // Validate email
    $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "<p style='color:red;'>❌ Invalid email address!</p>";
        exit;
    }

    // Ticket prices
    $unitPrices = [
        "standard" => 2500,
        "vip" => 7500,
        "balcony" => 3500,
        "student" => 1800,
        "child" => 1000,
    ];

    // Detect price key
    $categoryKey = null;
    $categoryLower = strtolower($category);
    foreach ($unitPrices as $key => $price) {
        if (strpos($categoryLower, $key) !== false) {
            $categoryKey = $key;
            break;
        }
    }

    // Calculate total
    $unitPrice = isset($unitPrices[$categoryKey]) ? $unitPrices[$categoryKey] : 0;
    $total = $unitPrice * $tickets;

    // Apply promo
    $discountApplied = false;
    if ($promo === "dannygram") {
        $total *= 0.90;
        $discountApplied = true;
    }

    // Update the DB
    $stmt = $myconn->prepare("UPDATE ticket_orders 
        SET full_name=?, email=?, phone=?, category=?, tickets=?, delivery_method=?, payment_method=?, promo_code=?, total_cost=? 
        WHERE id=?");

    $stmt->bind_param("ssssisssdi", 
        $fullName, $email, $phone, $category, $tickets, $delivery, $payment, $promo, $total, $id
    );

    if ($stmt->execute()) {
        echo "<h2 style='color:green;'>Ticket Updated Successfully!</h2>";

        // Display updated details
        echo "<p><strong>Full Name:</strong> " . htmlspecialchars($fullName) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($phone) . "</p>";
        echo "<p><strong>Ticket Category:</strong> " . htmlspecialchars($category) . "</p>";
        echo "<p><strong>Number of Tickets:</strong> " . $tickets . "</p>";
        echo "<p><strong>Delivery Method:</strong> " . htmlspecialchars($delivery) . "</p>";
        echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($payment) . "</p>";
        echo "<p><strong>Promo Code:</strong> " . htmlspecialchars($promo ?: "None") . "</p>";
        echo "<p><strong>Accepted Terms:</strong> " . $acceptTerms . "</p>";

        echo "<h3>Total Cost: <span style='color:blue;'>KSh " . number_format($total, 2) . "</span></h3>";

        if ($discountApplied) {
            echo "<p style='color:green;'>10% promo code discount applied!</p>";
        }
    } else {
        echo "<p style='color:red;'> Update Failed: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color:red;'>Invalid Request.</p>";
}
?>

<br><a href="view_tickets.php">Back to Ticket List</a>
