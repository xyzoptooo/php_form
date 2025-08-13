<?php
require_once ("dbs.php");

                                           // Function to sanitize input
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

                                              // Sanitize and validate inputs
$fullnames = clean_input($_POST['fullnames']);

$username  = clean_input($_POST['username']);

$password  = $_POST['password']; 

$email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);




// Validate fields
$errors = [];

if (empty($fullnames) || strlen($fullnames) < 3) {
    $errors[] = "Full name must be at least 3 characters.";
}

if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = "Username must only contain letters, numbers, or underscores.";
}

if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

if (!empty($errors)) {
    echo "<h1>Validation Error</h1><ul>";
    foreach ($errors as $e) {
        echo "<li>$e</li>";
        header("refresh: 3; URL=login.html");
    }
    echo "</ul><a href='index.html'>Go back to register</a>";
    header("refresh: 3; URL=login.html");
    exit;
}

// Hash password 
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check for existing user in the database

$stmt = $myconn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h1>Error</h1><p>Username already exists. <a href='index.html'>Try another</a></p>";
    header("refresh: 3; URL=login.html");

} else {
    // Insert new user into the db
    $stmt = $myconn->prepare(
        "INSERT INTO users (fullnames, username, password, email) 
        VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param(
    "ssss", 
    $fullnames, $username, $hashedPassword, $email
);


    
    if ($stmt->execute()) { //rediect 
        echo "<h1>Success</h1><p>Your account was created.</p>";
        echo "<p>Redirecting to login page. </p>";
        header("refresh: 3; URL=login.html");
     


    } else {
        echo "<h1>Error</h1><p>Registration failed. Please try again.</p>";
        header("refresh: 3; URL=login.html");
    }
}
?>
