<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$logged_in = false;
$username = "";

if (isset($_SESSION['username'])) {
  $logged_in = true;
  $username = $_SESSION['username'];
}

require_once 'php_functions/dbh.php';

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// REMOVE ONE item
if (isset($_POST['remove_one'])) {
    $remove_id = intval($_POST['remove_one']);
    if ($_SESSION['cart'][$remove_id] > 1) {
        $_SESSION['cart'][$remove_id]--;
    } else {
        unset($_SESSION['cart'][$remove_id]);
    }
    $cart_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}

// REMOVE ALL of this item
if (isset($_POST['remove_all'])) {
    $remove_id = intval($_POST['remove_all']);
    unset($_SESSION['cart'][$remove_id]);
    $cart_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | LuxeHome</title>
    <link rel="icon" type="image/png" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cartstyle.css">  <!-- New cart CSS -->
    <link rel="stylesheet" href="css/accessibility.css">
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
    <!-- Skip Link for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Accessibility Panel (unchanged) -->
    <div id="accessibilityPanel" class="accessibility-panel">
        <div class="accessibility-header">
            <h2 class="accessibility-title">Accessibility Settings</h2>
            <p class="accessibility-subtitle">Customize your browsing experience</p>
            <button id="closePanel" class="accessibility-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="accessibility-content">
            <div class="accessibility-section">
                <h3 class="accessibility-section-title">
                    <i class="fas fa-eye"></i>
                    Visual Preferences
                </h3>
                <div class="accessibility-options">
                    <div class="accessibility-option">
                        <input type="checkbox" id="darkMode">
                        <label for="darkMode">Dark Mode</label>
                    </div>
                    <div class="accessibility-option">
                        <input type="checkbox" id="highContrast">
                        <label for="highContrast">High Contrast</label>
                    </div>
                    <div class="accessibility-option">
                        <label for="fontSize">Font Size</label>
                        <input type="range" id="fontSize" min="0" max="3" value="1">
                        <div class="font-size-display" id="fontSizeDisplay">Normal</div>
                    </div>
                </div>
            </div>

            <div class="accessibility-section">
                <h3 class="accessibility-section-title">
                    <i class="fas fa-text-height"></i>
                    Text & Reading
                </h3>
                <div class="accessibility-options">
                    <div class="accessibility-option">
                        <input type="checkbox" id="dyslexiaFont">
                        <label for="dyslexiaFont">Dyslexia-Friendly Font</label>
                    </div>
                    <div class="accessibility-option">
                        <input type="checkbox" id="lineSpacing">
                        <label for="lineSpacing">Increased Line Spacing</label>
                    </div>
                </div>
            </div>

            <div class="accessibility-section">
                <h3 class="accessibility-section-title">
                    <i class="fas fa-mouse-pointer"></i>
                    Navigation
                </h3>
                <div class="accessibility-options">
                    <div class="accessibility-option">
                        <input type="checkbox" id="focusIndicator">
                        <label for="focusIndicator">Enhanced Focus Indicators</label>
                    </div>
                    <div class="accessibility-option">
                        <input type="checkbox" id="skipLinks">
                        <label for="skipLinks">Enable Skip Links</label>
                    </div>
                </div>
            </div>

            <button id="resetSettings" class="accessibility-reset">
                <i class="fas fa-undo"></i> Reset All Settings
            </button>
        </div>
    </div>

    <div id="panelOverlay" class="panel-overlay"></div>
    <button id="togglePanel" class="accessibility-toggle">
        <i class="fas fa-universal-access"></i>
    </button>

    <!-- Header (same as index.php) -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="flex items-center space-x-3">
                    <img src="images/image.png" alt="LuxeHome logo" class="logo-img">
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

                <div class="flex items-center space-x-4">
                    <button class="action-btn">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    <?php if ($logged_in): ?>
                        <a href="cart.php" class="action-btn relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge"><?php echo $cart_count; ?></span>
                        </a>
                        <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
                    <?php else: ?>
                        <a href="cart.php" class="action-btn relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge"><?php echo $cart_count; ?></span>
                        </a>
                        <a href="login.php" class="action-btn">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($logged_in): ?>
                    <div class="flex items-center space-x-2 ml-4">
                        <form method="POST" action="php_functions/logout.php">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">Log out</button>
                        </form>
                        <form method="POST" action="admin_dash.php">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Admin Dash</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Cart Content -->
    <main id="main-content" class="cart-page">
        <h1>Your Shopping Cart</h1>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subtotal = 0;
                    foreach ($_SESSION['cart'] as $product_id => $qty):
                        $product_id = intval($product_id);
                        $sql = "SELECT name, price, img FROM products WHERE product_id = $product_id";
                        $result = mysqli_query($conn, $sql);
                        if ($row = mysqli_fetch_assoc($result)):
                            $lineTotal = $row['price'] * $qty;
                            $subtotal += $lineTotal;
                    ?>
                    <tr>
                        <td>
                            <div class="cart-info">
                                <img src="product_image/<?= htmlspecialchars($row['img']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" onerror="this.style.display='none'">
                                <div>
                                    <p><?= htmlspecialchars($row['name']) ?></p>
                                    <small>Price: &pound;<?= number_format($row['price'], 2) ?></small>
                                    <form method="POST">
                                        <button type="submit" name="remove_all" value="<?= $product_id ?>" class="remove-link">
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="quantity-control">
                                <form method="POST" style="display: inline;">
                                    <button type="submit" name="remove_one" value="<?= $product_id ?>" class="quantity-btn">−</button>
                                </form>
                                <span class="quantity-number"><?= $qty ?></span>
                            </div>
                        </td>
                        <td>&pound;<?= number_format($lineTotal, 2) ?></td>
                    </tr>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>

            <div class="total-price">
                <div class="total-card">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>&pound;<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Tax (13%)</span>
                        <span>&pound;<?= number_format($subtotal * 0.13, 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span>&pound;<?= number_format($subtotal * 1.13, 2) ?></span>
                    </div>
                    <form action="check_out_page.php" method="POST">
                        <button type="submit" class="checkout-btn">
                            <i class="fas fa-lock mr-2"></i> Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Your cart is empty.</p>
                <a href="products.php">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer (same as index.php) -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand-logo">
                        <div class="brand-text">
                            <h3 class="brand-name text-white">LuxeHome</h3>
                            <p class="brand-tagline text-gray-400">Smart Living Elevated</p>
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