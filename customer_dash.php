<?php
include('php_functions/dashboard_functions.php');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$logged_in = false;
$username = "";

// Check if user is logged in
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    $logged_in = true;
    $username = $_SESSION['username'];
} else {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Determine active tab
$active_tab = 'home';
if (isset($_GET['edit_account'])) {
    $active_tab = 'edit_account';
} elseif (isset($_GET['your_orders'])) {
    $active_tab = 'your_orders';
} elseif (isset($_GET['wishlist'])) {
    $active_tab = 'wishlist';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Dashboard | LuxeHome</title>
    <meta name="description" content="Manage your account, orders, and wishlist" />
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accessibility.css">
    <link rel="stylesheet" href="css/userdashboard.css">
    <style>
        .logo-img{ 
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

    <!-- Header (kept from original) -->
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

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="products.php" class="nav-link">Shop</a>
                    <a href="about_us.php" class="nav-link">About us</a>
                    <a href="contact.php" class="nav-link">Contact</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <a href="cart.php" class="action-btn relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge"><?php echo getCartCount(); ?></span>
                    </a>
                    <span class="text-gray-900 font-semibold">Hi, <?php echo htmlspecialchars($username) ?>!</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Dashboard Area -->
    <main id="main-content">
        <div class="admin-wrapper">
            <!-- Sidebar -->
            <div class="admin-sidebar">
                <h2>
                    <i class="fas fa-user-circle"></i>
                    My Account
                </h2>
                <ul>
                    <li>
                        <a href="customer_dash.php" class="tab <?php echo $active_tab == 'home' ? 'active' : ''; ?>">
                            <i class="fa fa-home fa-fw"></i> 
                            <span>Dashboard Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="customer_dash.php?edit_account" class="tab <?php echo $active_tab == 'edit_account' ? 'active' : ''; ?>">
                            <i class="fa fa-cog fa-fw"></i> 
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="customer_dash.php?your_orders" class="tab <?php echo $active_tab == 'your_orders' ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-cart"></i> 
                            <span>My Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="customer_dash.php?wishlist" class="tab <?php echo $active_tab == 'wishlist' ? 'active' : ''; ?>">
                            <i class="fa fa-heart"></i> 
                            <span>My Wishlist</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content Area -->
            <div class="admin-main">
                <!-- Header with Welcome and Logout -->
                <div class="admin-header">
                    <h1>
                        <i class="fas fa-tachometer-alt"></i>
                        <?php
                        switch($active_tab) {
                            case 'edit_account':
                                echo 'Account Settings';
                                break;
                            case 'your_orders':
                                echo 'My Orders';
                                break;
                            case 'wishlist':
                                echo 'My Wishlist';
                                break;
                            default:
                                echo 'Dashboard';
                        }
                        ?>
                    </h1>
                    <form method="POST" action="php_functions/logout.php" style="display: inline;">
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>

                <!-- Dynamic Content -->
                <?php get_order_details(); ?>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
    <script src="js/accessibility.js"></script>
</body>
</html>