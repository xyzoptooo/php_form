<?php
require_once('dbs.php');

// Run query
$result = mysqli_query($myconn, "SELECT * FROM ticket_orders");

// Start HTML output
echo "<html><head><title>All Ticket Orders</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f5f5f5;
        padding: 20px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #0077cc;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    h1 {
        color: #2c3e50;
        text-align: center;
    }
</style>
</head>
<body>";

// Page title
echo "<h1>All Ticket Orders</h1>";

// Start table
echo "<table>";
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
        <th>Delete</th>
      </tr>";

// Loop through each row of data
while ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
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
    echo "<td>KSh " . number_format($row['total_cost'], 2) . "</td>";
    echo "<td><a href='deleterecord.php?id=$id' style='color: red;'>Delete</a></td>";
    echo "<td><a href='updaterecord.php?id=$id' style='blue: red;'>Update</a></td>";

    echo "</tr>";
}

// End table and HTML
echo "</table></body></html>";
?>
