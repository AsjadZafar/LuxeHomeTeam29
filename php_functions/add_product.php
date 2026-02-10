<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if(isset($_POST["add_product"])) {
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_quantity = $_POST['quantity'];
    $product_install = $_POST['installation_available'];
    $product_img = $_FILES['img']['name'];

    $tmp = explode(".",$product_img);

    $newfilename = round(microtime(true)). '.' .end($tmp);

    $uploadpath = "/product_image/". $newfilename;

    move_uploaded_file($_FILES['img']['tmp_name'], $uploadpath);

    require_once 'dbh.php';

    $product_name = mysqli_real_escape_string($conn, $product_name);
    $product_description = mysqli_real_escape_string($conn, $product_description);
    $product_price = mysqli_real_escape_string($conn, $product_price);
    $product_quantity = mysqli_real_escape_string($conn, $product_quantity);
    $product_install = mysqli_real_escape_string($conn, $product_install);
    $newfilename = mysqli_real_escape_string($conn, $newfilename);

    $sql = "INSERT into products(name,description,price,quantity,installation_available,img) 
    Values('$product_name','$product_description','$product_price','$product_quantity','$product_install','$newfilename')";

    $data = mysqli_query($conn,$sql);

    if($data) {
        header('location: https://cs2team29.cs2410-web01pvm.aston.ac.uk/php_functions/add_product.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product - Admin Dashboard</title>
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
                    <a href="add_product.php" class="active">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Products</span>
                    </a>
                </li>
                <li> 
                    <a href="view_product.php">
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
                <h1>Add New Product</h1>
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

            <!-- Form Container -->
            <div class="admin-form-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 class="section-title">Add New Product to Store</h2>
                    <a href="/admin_dash.php" class="btn-edit" style="padding: 0.75rem 1.5rem;">
                        <i class="fas fa-tachometer-alt"></i>
                        View Dashboard
                    </a>
                </div>
                
                <p class="welcome-text" style="margin-bottom: 2rem;">Fill in the details below to add a new product to the LuxeHome store.</p>
                
                <?php if(isset($data) && $data): ?>
                <div class="welcome-message" style="margin-bottom: 2rem; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                    <h2 class="welcome-title" style="color: #065f46;">
                        <i class="fas fa-check-circle"></i>
                        Product Added Successfully!
                    </h2>
                    <p class="welcome-text" style="color: #047857;">
                        The product has been added to the database and is now available in the store.
                    </p>
                    <div style="margin-top: 1rem;">
                        <a href="view_product.php" class="btn-edit" style="margin-right: 0.5rem;">
                            <i class="fas fa-eye"></i>
                            View All Products
                        </a>
                        <a href="/admin_dash.php" class="btn-submit" style="padding: 0.75rem 1.5rem;">
                            <i class="fas fa-tachometer-alt"></i>
                            Go to Dashboard
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (£) *</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="installation_available">Installation Available *</label>
                        <select id="installation_available" name="installation_available" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="img">Product Image *</label>
                        <input type="file" id="img" name="img" class="form-control" accept="image/*" required>
                        <small style="color: #6b7280; font-size: 0.875rem; display: block; margin-top: 0.25rem;">
                            Upload a product image (JPG, PNG, etc.)
                        </small>
                    </div>
                    
                    <div class="form-group" style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" name="add_product" class="btn-submit">
                            <i class="fas fa-save"></i>
                            Add Product
                        </button>
                        <a href="/admin_dash.php" class="btn-edit" style="padding: 1rem 2rem; text-decoration: none;">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
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
            
            // Add form validation styling
            const form = document.querySelector('.admin-form');
            if (form) {
                const inputs = form.querySelectorAll('input, textarea, select');
                
                inputs.forEach(input => {
                    input.addEventListener('invalid', function(e) {
                        e.preventDefault();
                        this.style.borderColor = '#ef4444';
                        this.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                    });
                    
                    input.addEventListener('input', function() {
                        if (this.checkValidity()) {
                            this.style.borderColor = '#059669';
                            this.style.boxShadow = '0 0 0 3px rgba(5, 150, 105, 0.1)';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>