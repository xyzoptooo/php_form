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
    $tickets = (int)($_POST["tickets"] ?? 0);
    $delivery = cleanInput($_POST["delivery_method"] ?? '');
    $payment = cleanInput($_POST["payment_method"] ?? '');
    $promo = strtolower(trim(preg_replace("/[^a-zA-Z0-9]/", "", $_POST["promo_code"] ?? '')));

    // Validate email
    $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "<p style='color:red;'>❌ Invalid email address provided!</p>";
        exit;
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

    // Compute total cost
    $unitPrice = ($categoryKey && isset($unitPrices[$categoryKey])) ? $unitPrices[$categoryKey] : 0;
    $total = $unitPrice * $tickets;

    // Apply promo discount
    if ($promo === "dannygram") {
        $total *= 0.90; // 10% discount
    }

    // Update the database using prepared statement
    $stmt = $myconn->prepare(
        "UPDATE ticket_orders 
         SET full_name=?, email=?, phone=?, category=?, tickets=?, delivery_method=?, payment_method=?, promo_code=?, total_cost=? 
         WHERE id=?"
    );
    $stmt->bind_param(
        "ssssisssdi", 
        $fullName, $email, $phone, $category, $tickets, $delivery, $payment, $promo, $total, $id
    );

    if ($stmt->execute()) {
        echo "<h2 style='color:green;'>✅ Ticket Updated Successfully</h2>";

        // Fetch and show updated ticket
        $select = $myconn->prepare("SELECT * FROM ticket_orders WHERE id = ?");
        $select->bind_param("i", $id);
        $select->execute();
        $result = $select->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            echo "<p><strong>Full Name:</strong> " . htmlspecialchars($row['full_name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
            echo "<p><strong>Tickets:</strong> " . $row['tickets'] . "</p>";
            echo "<p><strong>Delivery Method:</strong> " . htmlspecialchars($row['delivery_method']) . "</p>";
            echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($row['payment_method']) . "</p>";
            echo "<p><strong>Promo Code:</strong> " . ($row['promo_code'] ?: "None") . "</p>";
            echo "<h3>Total Cost: <span style='color:blue;'>KSh " . number_format($row['total_cost'], 2) . "</span></h3>";
        }

        $select->close();
    } else {
        echo "<p style='color:red;'>❌ Failed to update ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color:red;'>❌ Invalid request. No ticket ID found.</p>";
}
?>

<br><br>
<a href="view_tickets.php">🔙 Back to Ticket List</a>
