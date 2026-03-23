<?php
session_start();
require_once 'dbh.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(empty($_POST['review']) || empty($_POST['rating'])){
    header("Location: ../about_us.php#service-reviews");
    exit();
}

$user_id = $_SESSION['user_id'];
$review = mysqli_real_escape_string($conn, $_POST['review']);
$rating = intval($_POST['rating']);

if($rating < 1 || $rating > 5){
    header("Location: ../about_us.php#service-reviews");
    exit();
}

$sql = "INSERT INTO service_reviews (user_id, review, rating, review_date)
        VALUES ('$user_id', '$review', '$rating', NOW())";

if(!mysqli_query($conn, $sql)){
    die("Error: " . mysqli_error($conn));
}

header("Location: ../about_us.php#service-reviews");
exit();
?>