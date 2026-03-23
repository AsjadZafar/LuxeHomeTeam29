<?php
// 1. Connect to the database
require_once 'dbh.php';

// 2. Make sure the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password_hash'];
    $confirm_password = $_POST['confirm_password'];

    // 4. Check passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // 5. Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 6. Insert into users table
    $sql = "INSERT INTO users (username, email, password_hash) 
            VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

