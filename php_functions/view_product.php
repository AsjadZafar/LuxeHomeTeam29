<?php
session_start();

require_once 'dbh.php';

$sql = "SELECT * from products"; 
$result = mysqli_query($conn, $sql);

if (isset($_GET['id'])) {
    $p_id = $_GET['id'];
    $del_sql = "DELETE from products where product_id = '$p_id'";
    $data = mysqli_query($conn,$del_sql);

    if($data) {
        header("location: view_product.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Products - Admin Dashboard</title>
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
                <li> 
                    <a href="/admin_dash.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li> 
                    <a href="add_product.php">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Products</span>
                    </a>
                </li>
                <li> 
                    <a href="view_product.php" class="active">
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
                <h1>Product Management</h1>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="color: #4b5563; font-weight: 500;">
                        Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
                    </span>
                    <a href="/admin_dash.php" class="btn-edit" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    <a href="/index.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Products Table -->
            <div class="admin-table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 class="section-title">All Products</h2>
                    <div style="display: flex; gap: 1rem;">
                        <a href="/admin_dash.php" class="btn-edit" style="padding: 0.75rem 1.5rem;">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="add_product.php" class="btn-submit" style="padding: 0.75rem 1.5rem;">
                            <i class="fas fa-plus"></i>
                            Add New Product
                        </a>
                    </div>
                </div>

                <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Installation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <img src="/product_image/<?php echo htmlspecialchars($row['img']); ?>" 
                                         alt="<?php echo htmlspecialchars($row['name']); ?>"
                                         class="product-image">
                                </td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td style="max-width: 300px;"><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>£<?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <span style="color: <?php echo $row['quantity'] < 10 ? '#ef4444' : '#10b981'; ?>; font-weight: 500;">
                                        <?php echo htmlspecialchars($row['quantity']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="background: <?php echo $row['installation_available'] == '1' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)'; ?>; 
                                          color: <?php echo $row['installation_available'] == '1' ? '#047857' : '#dc2626'; ?>; 
                                          padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                                        <?php echo $row['installation_available'] == '1' ? 'Available' : 'Not Available'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <a onclick="return confirm('You are about to delete a product. Are you sure you want to continue?');" 
                                           href="view_product.php?id=<?php echo $row['product_id']; ?>" 
                                           class="btn-delete">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="welcome-message" style="margin-top: 2rem; background: linear-gradient(135deg, #fef3c7 0%, #fef3c7 100%); border-left: 4px solid #f59e0b;">
                    <h2 class="welcome-title" style="color: #92400e;">
                        <i class="fas fa-exclamation-circle"></i>
                        No Products Found
                    </h2>
                    <p class="welcome-text" style="color: #b45309;">
                        There are no products in the database yet. Add your first product to get started.
                    </p>
                    <div style="margin-top: 1rem;">
                        <a href="add_product.php" class="btn-submit" style="padding: 0.75rem 1.5rem;">
                            <i class="fas fa-plus"></i>
                            Add First Product
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <div style="margin-top: 2rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem;">
                    <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                        <i class="fas fa-info-circle" style="color: #059669; margin-right: 0.5rem;"></i>
                        Total Products: <strong><?php echo mysqli_num_rows($result); ?></strong> | 
                        Last Updated: <?php echo date('F j, Y, g:i a'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight the active link in sidebar
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.admin-sidebar a');
            
            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href').split('/').pop();
                if (currentPage === linkPage) {
                    link.classList.add('active');
                }
            });
            
            // Add confirmation for delete with sweet alert style
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('You are about to delete a product. Are you sure you want to continue?')) {
                        e.preventDefault();
                    }
                });
            });
            
            // Add hover effect to table rows
            const tableRows = document.querySelectorAll('.admin-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.05)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
        });
    </script>
</body>
</html>