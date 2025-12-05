<?php
session_start();
require_once 'dbh.php';

$id = intval($_POST['product_id']);

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if(isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]++;
} else {
    $_SESSION['cart'][$id] = 1;
}

header("Location: /cart.php");
exit;
?>
