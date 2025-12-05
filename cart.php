<?php
session_start();
require_once 'php_functions/dbh.php';

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// REMOVE ONE item
if (isset($_POST['remove_one'])) {
    $remove_id = intval($_POST['remove_one']);
    if ($_SESSION['cart'][$remove_id] > 1) {
        $_SESSION['cart'][$remove_id]--;
    } else {
        unset($_SESSION['cart'][$remove_id]);
    }
}

// REMOVE ALL of this item
if (isset($_POST['remove_all'])) {
    $remove_id = intval($_POST['remove_all']);
    unset($_SESSION['cart'][$remove_id]);
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | LuxeHome</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏠</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/cart-style.css">
      <style>
    .logo-img{ width:48px; height:48px; border-radius:50%; object-fit:cover }
  </style>
</head>

<body class="bg-gray-50">

    <!-- Header -->
   <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center py-4">
      <a href="index.html" class="flex items-center space-x-3">
      <img src="images/image.png" alt="LuxeHome logo" class="logo-img">
      <div>
        <h1 class="text-xl font-bold text-gray-900">LuxeHome</h1>
        <p class="text-xs text-gray-500">Smart Living Elevated</p>
      </div>
      </a>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="products.php" class="nav-link">Shop</a>
                    <a href="#" class="nav-link">Collections</a>
                    <a href="about_us.php" class="nav-link">Inspiration</a>
                    <a href="contact.php" class="nav-link active text-emerald-600 font-semibold">Contact</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <button class="action-btn"><i class="fas fa-search"></i></button>
                    <button class="action-btn relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge">3</span>
                    </button>
                    <button class="action-btn"><i class="fas fa-user"></i></button>
                </div>
            </div>
        </div>
    </header>

    <!-- Customer's Basket. KEEP IN MIND THIS WILL BE CONVERTED TO PHP SOON! EVERYTHING IS HARD CODED. A SEPERATE CSS FILE IS UPLOADED TO AVOID ANY POTENTIAL CONFLICTS. TY -->
<div class="small-container cart-page">
    <table class="w-full border-collapse" id="cartTable">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php
        $subtotal = 0;

        if (!empty($_SESSION['cart'])) {

            foreach ($_SESSION['cart'] as $product_id => $qty) {

                $product_id = intval($product_id);
                $sql = "SELECT name, price, img FROM products WHERE product_id = $product_id";
                $result = mysqli_query($conn, $sql);

                if ($row = mysqli_fetch_assoc($result)) {
                    
                    $lineTotal = $row['price'] * $qty;
                    $subtotal += $lineTotal;

                    echo "<tr>
                        <td>
                            <div class='cart-info'>
                                <img src='product_image/{$row['img']}' width='80'>
                                <div>
                                    <p>{$row['name']}</p>
                                    <small>Price: £" . number_format($row['price'],2) . "</small><br>
                                    
                                    <form method='POST'>
                                        <button type='submit' name='remove_all' value='$product_id' class='text-red-600 text-sm'>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form method='POST' style='display:inline-block'>
                                <button type='submit' name='remove_one' value='$product_id' class='px-2'>-</button>
                            </form>
                            
                            <span class='px-2'>$qty</span>
                            
                        </td>
                        <td>£" . number_format($lineTotal,2) . "</td>
                    </tr>";
                }
            }
        } else {
            echo "<tr><td colspan='3'>Your cart is empty.</td></tr>";
        }
        ?>
    </table>

    <div class="total-price mt-4">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>£<?= number_format($subtotal,2) ?></td>
            </tr>
            <tr>
                <td>Tax (13%):</td>
                <td>£<?= number_format($subtotal * 0.13,2) ?></td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>£<?= number_format($subtotal * 1.13,2) ?></td>
            </tr>
        </table>
    </div>
    <div class="text-right mt-6">
    <form action="check out page.php" method="POST">
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-md">
            Proceed to Checkout
        </button>
    </form>
</div>

</div>

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
                        Experience the pinnacle of intelligent living with our curated collection of premium smart home
                        technology designed for modern lifestyles.
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
                        <li><a href="#" class="footer-link">Shop</a></li>
                        <li><a href="#" class="footer-link">Collections</a></li>
                        <li><a href="#" class="footer-link">Inspiration</a></li>
                        <li><a href="contacttt.php" class="footer-link">Contact</a></li>
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




</body>
 



</html>