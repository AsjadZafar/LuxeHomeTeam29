<?php
session_start();
require_once 'php_functions/dbh.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $check = mysqli_query($conn, "SELECT order_id FROM orders WHERE user_id = $user_id LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $error = "Cannot delete user with existing orders.";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id");
        header('Location: admin_users.php');
        exit();
    }
}

// Fetch all users
$users = mysqli_query($conn, "SELECT user_id, username, email FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Admin</title>
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/adminstyle.css">
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin_dash.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="php_functions/add_product.php"><i class="fas fa-plus-circle"></i> <span>Add Products</span></a></li>
                <li><a href="php_functions/view_product.php"><i class="fas fa-eye"></i> <span>View Products</span></a></li>
                <li><a href="admin_users.php" class="active"><i class="fas fa-users"></i> <span>Users</span></a></li>
                <li><a href="admin_warranty.php"><i class="fas fa-clipboard-list"></i> <span>Warranty</span></a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>User Management</h1>
                <a href="index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="admin-table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h2 class="section-title">All Users</h2>
                    <a href="admin_user_edit.php" class="btn-submit">+ Add New User</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error; ?></div>
                <?php endif; ?>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td>#<?php echo $row['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="admin_user_edit.php?id=<?php echo $row['user_id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="admin_users.php?delete=<?php echo $row['user_id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this user?')" 
                                       class="btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>