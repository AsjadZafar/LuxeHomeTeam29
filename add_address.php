<?php
session_start();
require_once 'php_functions/dbh.php';

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit();
}

$username = $_SESSION['username'];
$user_query = mysqli_query($conn, "SELECT user_id FROM users WHERE username = '$username'");
$user_row = mysqli_fetch_assoc($user_query);
$user_id = $user_row['user_id'];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_line1 = mysqli_real_escape_string($conn, $_POST['address_line1']);
    $address_line2 = mysqli_real_escape_string($conn, $_POST['address_line2'] ?? '');
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $postcode = mysqli_real_escape_string($conn, $_POST['postcode']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    if (empty($address_line1) || empty($city) || empty($postcode) || empty($country)) {
        $error = 'All fields except Address Line 2 are required.';
    } else {
        $insert = "INSERT INTO addresses (user_id, address_line1, address_line2, city, postcode, country)
                   VALUES ('$user_id', '$address_line1', '$address_line2', '$city', '$postcode', '$country')";
        if (mysqli_query($conn, $insert)) {
            header('Location: /checkout.php');
            exit();
        } else {
            $error = 'Failed to save address. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address | LuxeHome</title>
    <link rel="icon" href="/images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/accessibility.css">
    <style>
        .logo-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/index.php" class="flex items-center space-x-3">
                <img src="/images/image.png" alt="LuxeHome logo" class="logo-img">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">LuxeHome</h1>
                    <p class="text-xs text-gray-500">Smart Living Elevated</p>
                </div>
            </a>
            <nav class="hidden md:flex space-x-8">
                <a href="/index.php" class="nav-link">Home</a>
                <a href="/products.php" class="nav-link">Shop</a>
                <a href="/about_us.php" class="nav-link">About us</a>
                <a href="/contact.php" class="nav-link">Contact</a>
            </nav>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Add a New Address</h1>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                        <input type="text" name="address_line1" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
                        <input type="text" name="address_line2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                        <input type="text" name="city" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postcode *</label>
                        <input type="text" name="postcode" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                        <input type="text" name="country" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <button type="submit" class="w-full btn-primary mt-4">Save Address</button>
                </div>
            </form>

            <p class="text-center mt-4">
                <a href="/checkout.php" class="text-emerald-600 hover:underline">Cancel and return to checkout</a>
            </p>
        </div>
    </main>

    <!-- Footer (copy from index.php) -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand-logo">
                        <div class="logo-icon"><i class="fas fa-home text-white"></i></div>
                        <div>
                            <h3 class="brand-name">LuxeHome</h3>
                            <p class="brand-tagline">Smart Living Elevated</p>
                        </div>
                    </div>
                    <p class="brand-description">
                        Experience the pinnacle of intelligent living with our curated collection of premium smart home technology designed for modern lifestyles.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-list">
                        <li><a href="/index.php" class="footer-link">Home</a></li>
                        <li><a href="/products.php" class="footer-link">Shop</a></li>
                        <li><a href="/about_us.php" class="footer-link">About us</a></li>
                        <li><a href="/contact.php" class="footer-link">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4 class="footer-heading">Contact</h4>
                    <ul class="footer-list">
                        <li>hello@luxehome.com</li>
                        <li>1-800-LUXE-HOME</li>
                        <li>Mon-Fri: 9am-6pm EST</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copyright">
                    © 2023 LuxeHome. All rights reserved. | 
                    <a href="#" class="footer-legal-link">Privacy Policy</a> | 
                    <a href="#" class="footer-legal-link">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="/js/script.js"></script>
    <script src="/js/accessibility.js"></script>
</body>
</html>