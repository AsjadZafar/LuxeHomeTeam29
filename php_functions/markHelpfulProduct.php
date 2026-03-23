<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

if(!isset($_POST['review_id'])){
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$review_id = intval($_POST['review_id']);
$user_id = intval($_SESSION['user_id']);

require_once 'dbh.php';

// This prevents duplicate votes
$sql = "SELECT * FROM review_helpful 
        WHERE review_id = $review_id AND user_id = $user_id";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){

    mysqli_query($conn, "INSERT INTO review_helpful (review_id, user_id) 
                         VALUES ($review_id, $user_id)");

    mysqli_query($conn, "UPDATE reviews 
                         SET helpful_count = helpful_count + 1 
                         WHERE review_id = $review_id");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>