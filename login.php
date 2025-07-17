<?php
session_start();
require_once("dbs.php");

$username = $_POST['username'];
$password = md5($_POST['password']);

$result = mysqli_query($myconn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
if (mysqli_num_rows($result) == 1) {
    $_SESSION['username'] = $username;
    echo "<h1>Welcome, $username</h1>";
    echo "<a href='tickbook1.php'>Place Order</a> | <a href='view_tickets.php'>View Tickets</a>";
} else {
    echo "<h1>Error</h1><p>Invalid credentials. <a href='login.html'>Try again</a></p>";
}
?>
