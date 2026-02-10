<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - LuxeHome</title>
    <link rel="icon" href="images/image.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/adminstyle.css">
    <style>
        .logo-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <h2>Admin Dashboard</h2>
            <ul> 
                <li> 
                    <a href="php_functions/add_product.php">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Products</span>
                    </a>
                </li>
                <li> 
                    <a href="php_functions/view_product.php">
                        <i class="fas fa-eye"></i>
                        <span>View Products</span>
                    </a>
                </li>
                <li> 
                    <a href="#">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li> 
                    <a href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>Admin Panel</h1>
                <a href="index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Message -->
                <div class="welcome-message">
                    <h2 class="welcome-title">Welcome to LuxeHome Admin Panel</h2>
                    <p class="welcome-text">Manage your premium smart living products, view analytics, and oversee users from this centralized dashboard.</p>
                </div>

                <!-- Stats Cards -->
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Total Products</h3>
                            <div class="card-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="card-stat">156</div>
                        <div class="card-label">Premium smart home items</div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Active Users</h3>
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="card-stat">2,847</div>
                        <div class="card-label">Registered customers</div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Total Orders</h3>
                            <div class="card-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="card-stat">1,023</div>
                        <div class="card-label">This month</div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3>Revenue</h3>
                            <div class="card-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="card-stat">$89,450</div>
                        <div class="card-label">Current month revenue</div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="activity-section">
                    <h3 class="section-title">Recent Activity</h3>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">New product added: "Smart Thermostat Pro"</div>
                                <div class="activity-time">2 hours ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Order #ORD-7842 completed</div>
                                <div class="activity-time">5 hours ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">New user registration: "premium_customer"</div>
                                <div class="activity-time">Yesterday</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Highlight active navigation link based on current page
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