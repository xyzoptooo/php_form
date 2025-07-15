<?php
require_once("dbs.php"); // connect to DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receive and sanitize values
    $id = (int)$_POST['id'];
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $category = trim($_POST['category']);
    $tickets = (int)$_POST['tickets'];
    $delivery = trim($_POST['delivery_method']);
    $payment = trim($_POST['payment_method']);
    $promo = trim($_POST['promo_code']);

    // Prepare update statement with placeholders
    $stmt = $myconn->prepare(
        "UPDATE ticket_orders 
         SET full_name = ?, email = ?, phone = ?, category = ?, tickets = ?, 
             delivery_method = ?, payment_method = ?, promo_code = ?
         WHERE id = ?"
    );

    // Bind values: s = string, i = integer
    $stmt->bind_param(
        "ssssisssi", 
        $fullName,
        $email,
        $phone,
        $category,
        $tickets,
        $delivery,
        $payment,
        $promo,
        $id
    );

    // Execute and confirm update
    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Ticket updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to update ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>
