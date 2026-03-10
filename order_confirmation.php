<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | LuxeHome</title>
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accessibility.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center space-x-3">
                <img src="images/image.png" alt="LuxeHome logo" class="logo-img" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">LuxeHome</h1>
                    <p class="text-xs text-gray-500">Smart Living Elevated</p>
                </div>
            </a>
            <nav class="hidden md:flex space-x-8">
                <a href="index.php" class="nav-link">Home</a>
                <a href="products.php" class="nav-link">Shop</a>
                <a href="about_us.php" class="nav-link">About us</a>
                <a href="contact.php" class="nav-link">Contact</a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-16">
        <div class="bg-white rounded-xl shadow-md p-8 md:p-12 text-center">
            <i class="fas fa-check-circle text-6xl text-emerald-600 mb-4"></i>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Your order #<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?> has been placed successfully.<br>
                You will receive a confirmation email shortly.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php" class="btn-primary">Continue Shopping</a>
                <a href="customer_dash.php" class="btn-outline">View Your Orders</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
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
                        <li><a href="index.php" class="footer-link">Home</a></li>
                        <li><a href="products.php" class="footer-link">Shop</a></li>
                        <li><a href="about_us.php" class="footer-link">About us</a></li>
                        <li><a href="contact.php" class="footer-link">Contact</a></li>
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

    <script src="js/script.js"></script>
    <script src="js/accessibility.js"></script>
</body>
</html>