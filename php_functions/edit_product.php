<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit();
}

require_once 'dbh.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: view_product.php');
    exit();
}
$product_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_name = trim($_POST['name']);
    $product_description = trim($_POST['description']);
    $product_price = floatval($_POST['price']);
    $product_quantity = intval($_POST['quantity']);
    $product_install = intval($_POST['installation_available']);
    $product_category = trim($_POST['product_category']);

    $img_filename = $product['img'];
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['img']['tmp_name'];
        $original_name = $_FILES['img']['name'];
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $new_filename = round(microtime(true)) . '.' . $ext;
        $upload_dir = "../product_image/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $upload_path = $upload_dir . $new_filename;
        if (move_uploaded_file($tmp_name, $upload_path)) {
            $old_path = $upload_dir . $product['img'];
            if (file_exists($old_path) && $product['img'] !== $new_filename) unlink($old_path);
            $img_filename = $new_filename;
        }
    }

    $update_sql = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, installation_available = ?, img = ?, category = ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdiissi", $product_name, $product_description, $product_price, $product_quantity, $product_install, $img_filename, $product_category, $product_id);
    if ($stmt->execute()) {
        header('Location: view_product.php?updated=1');
        exit();
    } else {
        $error = "Failed to update product: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product - Admin Dashboard</title>
    <link rel="icon" href="/images/image.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/adminstyle.css">
    <style>
        body { margin: 0; padding: 0; }
        .admin-wrapper { display: flex; width: 100%; min-height: 100vh; }
        .admin-sidebar { width: 280px; flex-shrink: 0; }
        .admin-main { flex: 1; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <h2>Admin Dashboard</h2>
            <ul> 
                <li><a href="/admin_dash.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="add_product.php"><i class="fas fa-plus-circle"></i><span>Add Products</span></a></li>
                <li><a href="view_product.php"><i class="fas fa-eye"></i><span>View Products</span></a></li>
                <li><a href="/admin_users.php"><i class="fas fa-users"></i><span>Users</span></a></li>
                <li><a href="/admin_warranty.php"><i class="fas fa-clipboard-list"></i><span>Warranty</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <div class="admin-header">
                <h1>Edit Product</h1>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="color: #4b5563; font-weight: 500;">Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></span>
                    <a href="/admin_dash.php" class="btn-edit" style="padding: 0.5rem 1rem;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                    <a href="/index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <div class="admin-form-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 class="section-title">Edit Product: <?php echo htmlspecialchars($product['name']); ?></h2>
                    <a href="view_product.php" class="btn-edit" style="padding: 0.75rem 1.5rem;"><i class="fas fa-eye"></i> View All Products</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group"><label for="name">Product Name *</label><input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required></div>
                    <div class="form-group"><label for="description">Description *</label><textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea></div>
                    <div class="form-group"><label for="price">Price (£) *</label><input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo $product['price']; ?>" required></div>
                    <div class="form-group"><label for="quantity">Quantity *</label><input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo $product['quantity']; ?>" required></div>
                    <div class="form-group"><label for="product_category">Category *</label><select id="product_category" name="product_category" class="form-control" required>
                        <option value="Bedroom" <?php echo ($product['category'] == 'Bedroom') ? 'selected' : ''; ?>>Bedroom</option>
                        <option value="Bathroom" <?php echo ($product['category'] == 'Bathroom') ? 'selected' : ''; ?>>Bathroom</option>
                        <option value="Kitchen" <?php echo ($product['category'] == 'Kitchen') ? 'selected' : ''; ?>>Kitchen</option>
                        <option value="Living Room" <?php echo ($product['category'] == 'Living Room') ? 'selected' : ''; ?>>Living Room</option>
                        <option value="Outdoor" <?php echo ($product['category'] == 'Outdoor') ? 'selected' : ''; ?>>Outdoor</option>
                    </select></div>
                    <div class="form-group"><label for="installation_available">Installation Available *</label><select id="installation_available" name="installation_available" class="form-control" required>
                        <option value="1" <?php echo ($product['installation_available'] == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($product['installation_available'] == 0) ? 'selected' : ''; ?>>No</option>
                    </select></div>
                    <div class="form-group">
                        <label for="img">Product Image</label>
                        <?php if ($product['img']): ?>
                            <div style="margin-bottom: 0.5rem;"><img src="/product_image/<?php echo htmlspecialchars($product['img']); ?>" alt="Current image" style="max-width: 150px; border-radius: 0.5rem;"></div>
                        <?php endif; ?>
                        <input type="file" id="img" name="img" class="form-control" accept="image/*">
                        <small style="color: #6b7280; font-size: 0.875rem; display: block; margin-top: 0.25rem;">Leave blank to keep current image. Upload new image to replace.</small>
                    </div>
                    <div class="form-group" style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" name="update_product" class="btn-submit"><i class="fas fa-save"></i> Update Product</button>
                        <a href="view_product.php" class="btn-edit" style="padding: 1rem 2rem; text-decoration: none;"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.admin-sidebar a');
            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href').split('/').pop();
                if (currentPage === linkPage) link.classList.add('active');
            });
        });
    </script>
</body>
</html>