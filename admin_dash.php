<?php
session_start();
require_once 'php_functions/dbh.php'; // connect to database cs2team29_db

// --- Fetch real-time statistics ---
// Total products
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'] ?? 0;

// Total users
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'] ?? 0;

// Total orders
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'] ?? 0;

// Total revenue (sum of all order totals)
$revenue_result = mysqli_query($conn, "SELECT SUM(total) as total FROM orders");
$total_revenue = mysqli_fetch_assoc($revenue_result)['total'] ?? 0;

// Recent products (last 5 added - using product_id as proxy for creation order)
$recent_products = mysqli_query($conn, "SELECT name FROM products ORDER BY product_id DESC LIMIT 5");

// Recent orders (last 5 orders with order_id, total, and order_date)
$recent_orders = mysqli_query($conn, "SELECT order_id, total, order_date FROM orders ORDER BY order_id DESC LIMIT 5");

// Recent user registrations
$recent_users = mysqli_query($conn, "SELECT username FROM users ORDER BY user_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - LuxeHome</title>
    <link rel="icon" href="/images/image.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/adminstyle.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="/admin_dash.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="php_functions/add_product.php"><i class="fas fa-plus-circle"></i> <span>Add Products</span></a></li>
                <li><a href="php_functions/view_product.php"><i class="fas fa-eye"></i> <span>View Products</span></a></li>
                <li><a href="#"><i class="fas fa-users"></i> <span>Users</span></a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>Dashboard Overview</h1>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="color: #4b5563; font-weight: 500;">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                    </span>
                    <a href="/index.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="welcome-message">
                <h2 class="welcome-title">Welcome Back, Admin! 👋</h2>
                <p class="welcome-text">
                    Here's what's happening with your store today. All numbers are live from the database.
                </p>
            </div>

            <!-- Dashboard Stats Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Total Products</h3>
                        <div class="card-icon"><i class="fas fa-box"></i></div>
                    </div>
                    <div class="card-stat"><?php echo $product_count; ?></div>
                    <div class="card-label">Premium smart home items</div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Active Users</h3>
                        <div class="card-icon"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="card-stat"><?php echo $user_count; ?></div>
                    <div class="card-label">Registered customers</div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Total Orders</h3>
                        <div class="card-icon"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                    <div class="card-stat"><?php echo $order_count; ?></div>
                    <div class="card-label">All time</div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Revenue</h3>
                        <div class="card-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                    <div class="card-stat">£<?php echo number_format($total_revenue, 2); ?></div>
                    <div class="card-label">Total sales revenue</div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="activity-section">
                <h3 class="section-title">Recent Activity</h3>
                
                <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                    <!-- Recent Products -->
                    <div style="flex: 1; min-width: 250px;">
                        <h4 style="color: #111827; margin-bottom: 1rem; font-size: 1rem;">
                            <i class="fas fa-box" style="color: #059669; margin-right: 0.5rem;"></i> Recently Added Products
                        </h4>
                        <ul class="activity-list">
                            <?php if (mysqli_num_rows($recent_products) > 0): ?>
                                <?php while ($p = mysqli_fetch_assoc($recent_products)): ?>
                                <li class="activity-item" style="border-bottom: none; padding: 0.5rem 0;">
                                    <div class="activity-icon" style="width: 2rem; height: 2rem; font-size: 0.875rem;">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><?php echo htmlspecialchars($p['name']); ?></div>
                                    </div>
                                </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">No products yet</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Recent Orders -->
                    <div style="flex: 1; min-width: 250px;">
                        <h4 style="color: #111827; margin-bottom: 1rem; font-size: 1rem;">
                            <i class="fas fa-shopping-cart" style="color: #059669; margin-right: 0.5rem;"></i> Recent Orders
                        </h4>
                        <ul class="activity-list">
                            <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                                <?php while ($o = mysqli_fetch_assoc($recent_orders)): ?>
                                <li class="activity-item" style="border-bottom: none; padding: 0.5rem 0;">
                                    <div class="activity-icon" style="width: 2rem; height: 2rem; font-size: 0.875rem; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Order #<?php echo $o['order_id']; ?> - £<?php echo number_format($o['total'], 2); ?></div>
                                        <div class="activity-time"><?php echo date('M j, Y', strtotime($o['order_date'])); ?></div>
                                    </div>
                                </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">No orders yet</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Recent Users -->
                    <div style="flex: 1; min-width: 250px;">
                        <h4 style="color: #111827; margin-bottom: 1rem; font-size: 1rem;">
                            <i class="fas fa-user-plus" style="color: #059669; margin-right: 0.5rem;"></i> New Users
                        </h4>
                        <ul class="activity-list">
                            <?php if (mysqli_num_rows($recent_users) > 0): ?>
                                <?php while ($u = mysqli_fetch_assoc($recent_users)): ?>
                                <li class="activity-item" style="border-bottom: none; padding: 0.5rem 0;">
                                    <div class="activity-icon" style="width: 2rem; height: 2rem; font-size: 0.875rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><?php echo htmlspecialchars($u['username']); ?></div>
                                    </div>
                                </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">No users yet</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Highlight active link in sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.admin-sidebar a');
            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href').split('/').pop();
                if (currentPage === linkPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>