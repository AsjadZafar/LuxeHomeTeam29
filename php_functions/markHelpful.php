<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

// Check if review_id is provided
if(!isset($_POST['review_id'])){
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$review_id = intval($_POST['review_id']);
$user_id = intval($_SESSION['user_id']);

// Connect to DB
require_once 'dbh.php';

// Check if user already voted to prevent duplicate voting
$sql = "SELECT * FROM service_review_helpful 
        WHERE review_id = $review_id AND user_id = $user_id";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    // Insert vote
    $insert = "INSERT INTO service_review_helpful (review_id, user_id) 
               VALUES ($review_id, $user_id)";
    mysqli_query($conn, $insert);

    // Update count
    $update = "UPDATE service_reviews 
               SET helpful_count = helpful_count + 1 
               WHERE review_id = $review_id";
    mysqli_query($conn, $update);
}

// Go back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>