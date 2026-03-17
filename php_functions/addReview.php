<?php
session_start();
require_once 'dbh.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$rating = intval($_POST['rating']);
$review = mysqli_real_escape_string($conn, $_POST['review']);

$sql = "INSERT INTO reviews (user_id, product_id, review, rating, review_date)
        VALUES ('$user_id', '$product_id', '$review', '$rating', NOW())";

mysqli_query($conn,$sql);

header("Location: ../productDetails.php?id=".$product_id);
exit();
?>