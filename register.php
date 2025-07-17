<?php
require_once('dbs.php');

$fullnames = trim($_POST['fullnames']);
$username = trim($_POST['username']);
$password = md5($_POST['password']);  // For security use password_hash in real apps
$email = trim($_POST['email']);

$check = mysqli_query($myconn, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($check) > 0) {
    echo "<h1>Error</h1><p>Username already exists. Please choose another.</p>";
} else {
    $insert = mysqli_query($myconn, "INSERT INTO users (fullnames, username, password, email)
                VALUES ('$fullnames', '$username', '$password', '$email')");
    if ($insert) {
        echo "<h1>Success</h1><p>Account created. <a href='login.html'>Login here</a></p>";
    } else {
        echo "<h1>Error</h1><p>Registration failed. Try again.</p>";
    }
}
?>
