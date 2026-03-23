<?php
session_start();
require_once 'dbh.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Return JSON response for AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Please login to add items to your wishlist', 'redirect' => 'login.php']);
        exit();
    } else {
        // Regular form submission
        header('Location: ../login.php');
        exit();
    }
}

// Get product ID from POST
if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Invalid product']);
        exit();
    } else {
        header('Location: ../products.php');
        exit();
    }
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

// Include dashboard functions to use wishlist functions
require_once 'dashboard_functions.php';

// Try to add to wishlist
$result = addToWishlist($user_id, $product_id);

// Return response based on request type
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // AJAX request
    if ($result) {
        // Get updated wishlist count
        $count = getWishlistCount($user_id);
        echo json_encode([
            'success' => true, 
            'message' => 'Added to wishlist!',
            'wishlist_count' => $count,
            'action' => 'added'
        ]);
    } else {
        // Check if already in wishlist
        global $conn;
        $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $check->bind_param("ii", $user_id, $product_id);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Product already in your wishlist',
                'action' => 'exists'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to add to wishlist'
            ]);
        }
    }
} else {
    // Regular form submission
    if ($result) {
        $_SESSION['success_message'] = 'Product added to wishlist!';
    } else {
        $_SESSION['error_message'] = 'Failed to add to wishlist or product already exists.';
    }
    header('Location: ../productDetails.php?id=' . $product_id);
    exit();
}
?>