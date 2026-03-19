<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    // redirect back if not logged in
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Check if review_id is provided
if(!isset($_POST['review_id'])){
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$review_id = intval($_POST['review_id']);
$user_id = intval($_SESSION['user_id']);

// Include database connection
require_once 'dbh.php';

try {
    // Check if user already voted
    $check_sql = "SELECT id FROM service_review_helpful WHERE review_id = $review_id AND user_id = $user_id";
    $result = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($result) == 0){
        // Insert helpful vote
        $insert_sql = "INSERT INTO service_review_helpful (review_id, user_id) VALUES ($review_id, $user_id)";
        mysqli_query($conn, $insert_sql);

        // Update helpful_count
        $update_sql = "UPDATE service_reviews SET helpful_count = helpful_count + 1 WHERE review_id = $review_id";
        mysqli_query($conn, $update_sql);
    }

    // Redirect back to the page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

} catch(Exception $e){
    // just redirect back on error
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>