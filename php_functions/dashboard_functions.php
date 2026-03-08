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

function updateUserDetails($user_id, $username, $email) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);
    return $stmt->execute();
}

function changePassword($user_id, $old_password, $new_password) {
    global $conn;
    
    // Verify old password
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($old_password, $user['password_hash'])) {
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $update->bind_param("si", $new_hash, $user_id);
        return $update->execute();
    }
    return false;
}

function getUserAddresses($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY address_id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function addAddress($user_id, $address_line1, $address_line2, $city, $postcode, $country) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO addresses (user_id, address_line1, address_line2, city, postcode, country) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $address_line1, $address_line2, $city, $postcode, $country);
    return $stmt->execute();
}

function updateAddress($address_id, $user_id, $address_line1, $address_line2, $city, $postcode, $country) {
    global $conn;
    $stmt = $conn->prepare("UPDATE addresses SET address_line1 = ?, address_line2 = ?, city = ?, postcode = ?, country = ? WHERE address_id = ? AND user_id = ?");
    $stmt->bind_param("sssssii", $address_line1, $address_line2, $city, $postcode, $country, $address_id, $user_id);
    return $stmt->execute();
}

function deleteAddress($address_id, $user_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM addresses WHERE address_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $address_id, $user_id);
    return $stmt->execute();
}

?>