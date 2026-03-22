<?php
session_start();
require_once 'dbh.php';
$status = 'Pending';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


$product_id = intval($_POST['product_id']);
$order_id = intval($_POST['order_id']);

//  Prevent duplicate returns
$check = $conn->prepare("SELECT * FROM product_return WHERE product_id = ? AND order_id = ?");
$check->bind_param("ii", $product_id, $order_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Already returned
    header("Location: ../customer_dash.php?your_orders&view=".$order_id);
    exit();
}

//  Insert return into database
$stmt = $conn->prepare("INSERT INTO product_return (product_id, order_id, return_date, status)
                        VALUES (?, ?, NOW(), ?) ");

$stmt->bind_param("iis", $product_id, $order_id, $status);
$stmt->execute();

//  back to order page
header("Location: ../customer_dash.php?your_orders&view=".$order_id);
exit();
?>