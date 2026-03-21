<?php
session_start();
require_once 'dbh.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$review_id = $_POST['review_id'];
$product_id = $_POST['product_id'];

/* Ensure user owns the review */
$check = $conn->prepare("SELECT user_id FROM reviews WHERE review_id = ?");
$check->bind_param("i", $review_id);
$check->execute();
$result = $check->get_result();
$review = $result->fetch_assoc();

if($review && $review['user_id'] == $user_id){

    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();

}

header("Location: ../product_details.php?id=".$product_id);
exit();
?>