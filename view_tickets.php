<?php
require_once('dbs.php');

// Query the database
$result = mysqli_query($myconn, "SELECT * FROM ticket_orders");

// Check if query ran successfully
if (!$result) {
    die("Query failed: " . mysqli_error($myconn));
}

// Start HTML table
echo "<h1> All Ticket Orders</h1>";
echo "<table border='2' cellpadding='10' cellspacing='0'>";
echo "<tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Category</th>
        <th>Tickets</th>
        <th>Delivery</th>
        <th>Payment</th>
        <th>Promo Code</th>
        <th>Total Cost (KSh)</th>
        <th>Delete Record</th>

      </tr>";

// Loop through the results using mysqli_fetch_array()
while ($row = mysqli_fetch_array($result)) {
    $id=$row['id'];
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
    echo "<td>" . $row['tickets'] . "</td>";
    echo "<td>" . htmlspecialchars($row['delivery_method']) . "</td>";
    echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
    echo "<td>" . htmlspecialchars($row['promo_code']) . "</td>";
    echo "<td>" . number_format($row['total_cost'], 2) . "</td>";
    echo "<td><a href='deleterecord.php?id=$id'>Delete Record<a></td>"; //link to delete
    echo "</tr>";
}

// End table
echo "</table>";
?>
