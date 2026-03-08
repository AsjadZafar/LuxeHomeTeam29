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

function getUserOrders($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT o.*, a.address_line1, a.city, a.postcode
        FROM orders o 
        LEFT JOIN addresses a ON o.address_id = a.address_id 
        WHERE o.user_id = ? 
        ORDER BY o.order_date DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getOrderItems($order_id) {
    global $conn;
   
    $stmt = $conn->prepare("
        SELECT o.*, p.name, p.description, p.img, p.price as product_price
        FROM orders o 
        JOIN products p ON o.product_id = p.product_id 
        WHERE o.orders_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}

function addProductReview($user_id, $product_id, $rating, $review) {
    global $conn;
    $review_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, review, rating, review_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $user_id, $product_id, $review, $rating, $review_date);
    return $stmt->execute();
}

function getProductReviews($product_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT r.*, u.username 
        FROM reviews r 
        JOIN users u ON r.user_id = u.user_id 
        WHERE r.product_id = ? 
        ORDER BY r.review_date DESC
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Check if user has reviewed a specific product
function hasUserReviewedProduct($user_id, $product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Get user's review for a specific product
function getUserProductReview($user_id, $product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function addToWishlist($user_id, $product_id) {
    global $conn;
    // Check if already in wishlist
    $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows == 0) {
        
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }
    return false; // Already in wishlist
}

function removeFromWishlist($user_id, $product_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    return $stmt->execute();
}

function getWishlistCount($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}


?>