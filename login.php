<?php
session_start();
require_once ("dbs.php");

// Sanitize inputs
$username = htmlspecialchars(trim($_POST['username']));
$password = $_POST['password'];

// Validate inputs
if (empty($username) || empty($password)) {
    echo "<p>Please enter both username and password. <a href='login.html'>Back</a></p>";
    exit;
}

// Retrieve user record
$stmt = $myconn->prepare(
    "SELECT * FROM users WHERE username = ?"
);

$stmt->bind_param("s", 
                 $username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Use password_verify to verify the hash
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username; //session -t  store user info
        echo "<h1>Welcome, " . htmlspecialchars($username) . "!</h1>";
      
        header("Location: tickbook1.php");
        exit;

        echo "<a href='tickbook1.php'>Place Order</a> | <a href='view_tickets.php'>View Tickets</a> ";
    } else {
        echo "<h1>Login Failed</h1><p>Incorrect password. <a href='login.html'>Try again</a></p>";
    }
} else {
    echo "<h1>Login Failed</h1><p>No such user found. <a href='login.html'>Try again</a></p>";
}
?>
