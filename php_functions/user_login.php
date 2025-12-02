<?php
session_start(); // start session to keep user logged in
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'dbh.php';

// 2. Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 3. Fetch user from DB
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // 4. Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Password correct, set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to customer home
            header("Location: /index.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with that email!";
    }
}

mysqli_close($conn);
?>
