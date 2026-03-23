<?php
session_start();
require_once 'php_functions/dbh.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php?redirect=checkout.php');
    exit();
}

// Get user ID (store user_id in session on login for efficiency)
$username = $_SESSION['username'];
$user_query = mysqli_query($conn, "SELECT user_id FROM users WHERE username = '$username'");
$user_row = mysqli_fetch_assoc($user_query);
$user_id = $user_row['user_id'];

// If cart empty, redirect
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Fetch user's default address (first address in addresses table)
$address_query = mysqli_query($conn, "SELECT * FROM addresses WHERE user_id = $user_id LIMIT 1");
$address = mysqli_fetch_assoc($address_query);

// Fallback if no address – we'll use a placeholder but will redirect on order placement
if (!$address) {
    $address = [
        'address_line1' => 'No address on file',
        'city'         => '',
        'postcode'     => '',
        'country'      => ''
    ];
}

// Calculate cart totals
$cart_items = [];
$subtotal = 0;
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $product_id = intval($product_id);
    $result = mysqli_query($conn, "SELECT name, price, img FROM products WHERE product_id = $product_id");
    if ($row = mysqli_fetch_assoc($result)) {
        $line_total = $row['price'] * $qty;
        $subtotal += $line_total;
        $cart_items[] = [
            'id'    => $product_id,
            'name'  => $row['name'],
            'price' => $row['price'],
            'qty'   => $qty,
            'img'   => $row['img'],
            'line'  => $line_total
        ];
    }
}

$tax_rate = 0.13; // 13%
$tax = $subtotal * $tax_rate;
$shipping = 0;    // free shipping
$total = $subtotal + $tax + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | LuxeHome</title>
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accessibility.css">
    <link rel="stylesheet" href="css/checkout.css">
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

    <!-- Accessibility Panel -->
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
                <h3 class="accessibility-section-title"><i class="fas fa-eye"></i> Visual Preferences</h3>
                <div class="accessibility-options">
                    <div class="accessibility-option">
                        <input type="checkbox" id="darkMode"><label for="darkMode">Dark Mode</label>
                    </div>
                    <div class="accessibility-option">
                        <input type="checkbox" id="highContrast"><label for="highContrast">High Contrast</label>
                    </div>
                    <div class="accessibility-option">
                        <label for="fontSize">Font Size</label>
                        <input type="range" id="fontSize" min="0" max="3" value="1">
                        <div class="font-size-display" id="fontSizeDisplay">Normal</div>
                    </div>
                </div>
            </div>

            <div class="accessibility-section">
                <h3 class="accessibility-section-title"><i class="fas fa-text-height"></i> Text & Reading</h3>
                <div class="accessibility-options">
                    <div class="accessibility-option">
                        <input type="checkbox" id="dyslexiaFont"><label for="dyslexiaFont">Dyslexia-Friendly Font</label>
                    </div>
                    <div class="accessibility-option">
                        <input type="checkbox" id="lineSpacing"><label for="lineSpacing">Increased Line Spacing</label>
                    </div>
                </div>
            </div>

            <div class="accessibility-section">
                <h3 class="accessibility-section-title"><i class="fas fa-mouse-pointer"></i> Navigation</h3>
                <div class="accessibility-options">
                    <div class="accessibility-option">
                        <input type="checkbox" id="focusIndicator"><label for="focusIndicator">Enhanced Focus Indicators</label>
                    </div>
                    <div class="accessibility-option">
                        <input type="checkbox" id="skipLinks"><label for="skipLinks">Enable Skip Links</label>
                    </div>
                </div>
            </div>

            <button id="resetSettings" class="accessibility-reset"><i class="fas fa-undo"></i> Reset All Settings</button>
        </div>
    </div>
    <div id="panelOverlay" class="panel-overlay"></div>
    <button id="togglePanel" class="accessibility-toggle"><i class="fas fa-universal-access"></i></button>

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
                    <button class="action-btn"><i class="fas fa-search"></i></button>
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="cart.php" class="action-btn relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge"><?php echo array_sum($_SESSION['cart'] ?? []); ?></span>
                        </a>
                        <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <?php else: ?>
                        <a href="cart.php" class="action-btn relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">0</span>
                        </a>
                        <a href="login.php" class="action-btn"><i class="fas fa-user"></i></a>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['username'])): ?>
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

    <!-- Main Content -->
    <main id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Delivery Address -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 bg-gradient-to-br from-emerald-200 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold">1</span>
                        <h2 class="text-xl font-semibold text-gray-900 flex-1">Delivery Address</h2>
                        <a href="customer_dash.php" class="text-emerald-600 hover:underline text-sm font-medium">Change</a>
                    </div>
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border-l-4 border-emerald-600">
                        <p class="font-medium text-gray-900"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <p class="text-gray-700"><?php echo htmlspecialchars($address['address_line1']); ?></p>
                        <?php if (!empty($address['address_line2'])): ?>
                            <p class="text-gray-700"><?php echo htmlspecialchars($address['address_line2']); ?></p>
                        <?php endif; ?>
                        <p class="text-gray-700"><?php echo htmlspecialchars($address['city'] . ', ' . $address['postcode']); ?></p>
                        <p class="text-gray-700"><?php echo htmlspecialchars($address['country']); ?></p>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 bg-gradient-to-br from-emerald-200 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold">2</span>
                        <h2 class="text-xl font-semibold text-gray-900 flex-1">Payment Method</h2>
                        <a href="customer_dash.php" class="text-emerald-600 hover:underline text-sm font-medium">Change</a>
                    </div>
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200 flex items-center gap-3">
                        <i class="fab fa-cc-visa text-2xl text-emerald-600"></i>
                        <span>Visa ending in 1234</span>
                    </div>
                    <p class="mt-3 text-sm text-gray-600"><strong>Billing address:</strong> Same as delivery address</p>
                </div>

                <!-- Review Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 bg-gradient-to-br from-emerald-200 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold">3</span>
                        <h2 class="text-xl font-semibold text-gray-900">Review Items</h2>
                    </div>
                    <div class="mt-4 divide-y divide-gray-100">
                        <?php foreach ($cart_items as $item): ?>
                        <div class="flex gap-4 py-4">
                            <img src="product_image/<?php echo htmlspecialchars($item['img']); ?>" alt="" class="w-20 h-20 object-cover rounded-lg border border-gray-200" onerror="this.style.display='none'">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p class="text-sm text-gray-600">£<?php echo number_format($item['price'],2); ?> each</p>
                                <p class="text-sm text-gray-600">Quantity: <?php echo $item['qty']; ?></p>
                            </div>
                            <div class="font-semibold text-gray-900">£<?php echo number_format($item['line'],2); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Delivery options -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-3">Choose delivery option:</h4>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg mb-2 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="delivery" class="accent-emerald-600" checked>
                            <span><strong>FREE One‑Day Delivery</strong> – get it tomorrow</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="delivery" class="accent-emerald-600">
                            <span><strong>FREE Standard Delivery</strong> – get it in 3‑5 days</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column (Order Summary) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 sticky top-24">
                    <h3 class="text-xl font-semibold text-gray-900 pb-4 border-b border-gray-100">Order Summary</h3>
                    <div class="space-y-3 mt-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Items:</span>
                            <span>£<?php echo number_format($subtotal,2); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Tax (13%):</span>
                            <span>£<?php echo number_format($tax,2); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Shipping:</span>
                            <span>£<?php echo number_format($shipping,2); ?></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-gray-900 pt-3 border-t border-gray-200">
                            <span>Total:</span>
                            <span>£<?php echo number_format($total,2); ?></span>
                        </div>
                    </div>

                    <form action="php_functions/place_order.php" method="POST" class="mt-6">
                        <input type="hidden" name="address_id" value="<?php echo $address['address_id'] ?? 0; ?>">
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i> Place Your Order
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        By placing your order, you agree to our 
                        <a href="#" class="text-emerald-600 hover:underline">Terms</a> and 
                        <a href="#" class="text-emerald-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer (same as index.php) -->
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