<?php
// 1. Connect to the database
$conn = mysqli_connect("localhost", "root", "", "ecommerce");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Make sure the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 4. Check passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // 5. Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 6. Insert into users table
    $sql = "INSERT INTO users (username, email, password) 
            VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

