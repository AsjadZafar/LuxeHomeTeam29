<?php
session_start();
require_once 'php_functions/dashboard_functions.php';

if (isset($_SESSION['user_id'])) {
    echo getWishlistCount($_SESSION['user_id']);
} else {
    echo '0';
}
?>