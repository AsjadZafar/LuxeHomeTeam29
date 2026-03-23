<?php
session_start();

// Clear cart
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Redirect to a confirmation page
header("Location: /index.php");
exit;
?>
