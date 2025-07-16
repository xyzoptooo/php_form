<?php
require_once("dbs.php");

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    $id = (int)$_REQUEST['id'];

    // Fetch ticket record by ID
    $result = mysqli_query($myconn, "SELECT * FROM ticket_orders WHERE id='$id'");
    $row = mysqli_fetch_array($result);

    if ($row) {
        $fullName = $row['full_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $category = $row['category'];
        $tickets = $row['tickets'];
        $delivery = $row['delivery_method'];
        $payment = $row['payment_method'];
        $promo = $row['promo_code'];
    } else {
        echo "<p style='color:red;'>Record not found.</p>";
        exit;
    }
} else {
    echo "<p style='color:red;'>Invalid ticket ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Ticket Order</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #f9f9f9;
        }
        h2 {
            color: #444;
        }
        label {
            font-weight: bold;
        }
        input, select {
            margin: 8px 0;
            padding: 8px;
            width: 300px;
        }
        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>✏️ Edit Ticket Order</h2>
<form action="updateprocess.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id ?>">

    <label>Full Name:</label><br>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($fullName) ?>" readonly><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email) ?>" required><br>

    <label>Phone:</label><br>
    <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone) ?>" required><br>

    <label>Category:</label><br>
    <select name="category" required>
        <option <?php if ($category == 'Standard Admission - KSh 2,500') echo 'selected'; ?>>Standard Admission - KSh 2,500</option>
        <option <?php if ($category == 'VIP Access - KSh 7,500') echo 'selected'; ?>>VIP Access - KSh 7,500</option>
        <option <?php if ($category == 'Balcony Seating - KSh 3,500') echo 'selected'; ?>>Balcony Seating - KSh 3,500</option>
        <option <?php if ($category == 'Student Discount (requires ID) - KSh 1,800') echo 'selected'; ?>>Student Discount (requires ID) - KSh 1,800</option>
        <option <?php if ($category == 'Child (Under 12) - KSh 1,000') echo 'selected'; ?>>Child (Under 12) - KSh 1,000</option>
    </select><br>

    <label>Number of Tickets:</label><br>
    <input type="number" name="tickets" value="<?php echo $tickets ?>" min="1" required><br>

    <label>Delivery Method:</label><br>
    <input type="text" name="delivery_method" value="<?php echo htmlspecialchars($delivery) ?>" required><br>

    <label>Payment Method:</label><br>
    <input type="text" name="payment_method" value="<?php echo htmlspecialchars($payment) ?>" required><br>

    <label>Promo Code:</label><br>
    <input type="text" name="promo_code" value="<?php echo htmlspecialchars($promo) ?>"><br>

    <br>
    <button type="submit"> Update Ticket</button>
</form>

</body>
</html>
