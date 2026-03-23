<?php
session_start();
require_once 'dbh.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(!isset($_POST['review_id'])){
    header("Location: ../about_us.php#service-reviews");
    exit();
}

$review_id = intval($_POST['review_id']);
$user_id = $_SESSION['user_id'];

if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
    $sql = "DELETE FROM service_reviews WHERE review_id = $review_id";
} else {
    $sql = "DELETE FROM service_reviews WHERE review_id = $review_id AND user_id = $user_id";
}

mysqli_query($conn, $sql);

header("Location: ../about_us.php#service-reviews");
exit();
?>