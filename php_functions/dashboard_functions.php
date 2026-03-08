<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'dbh.php';

// php_functions/dashboard_functions.php

function getUserDetails($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>