<?php
session_start();
require_once 'php_functions/dbh.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$edit_mode = ($user_id > 0);
$user = ['username' => '', 'email' => '', 'password_hash' => ''];

if ($edit_mode) {
    $stmt = $conn->prepare("SELECT username, email, password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        header('Location: admin_users.php');
        exit();
    }
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = trim($_POST['password'] ?? '');
    
    if ($edit_mode) {
        // Update username and email
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $username, $email, $user_id);
        $stmt->execute();
        
        // If a new password was provided, update it
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
        }
    } else {
        // Add new user
        $default_password = 'password123';
        if (!empty($new_password)) {
            $default_password = $new_password;
        }
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();
        $user_id = $stmt->insert_id;
    }
    header('Location: admin_users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_mode ? 'Edit User' : 'Add User'; ?> | Admin</title>
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/adminstyle.css">
    <style>
        body { margin: 0; padding: 0; }
        .admin-wrapper { display: flex; width: 100%; min-height: 100vh; }
        .admin-sidebar { width: 280px; flex-shrink: 0; }
        .admin-main { flex: 1; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin_dash.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="php_functions/add_product.php"><i class="fas fa-plus-circle"></i> <span>Add Products</span></a></li>
                <li><a href="php_functions/view_product.php"><i class="fas fa-eye"></i> <span>View Products</span></a></li>
                <li><a href="admin_users.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
                <li><a href="admin_warranty.php"><i class="fas fa-clipboard-list"></i> <span>Warranty</span></a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1><?php echo $edit_mode ? 'Edit User' : 'Add New User'; ?></h1>
                <a href="admin_users.php" class="logout-btn"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <div class="admin-form-container">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">
                            <?php echo $edit_mode ? 'New Password (leave blank to keep current)' : 'Password (leave blank for default: password123)'; ?>
                        </label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small style="color: #6b7280; font-size: 0.875rem; display: block; margin-top: 0.25rem;">
                            <?php if ($edit_mode): ?>
                                Only enter a password if you want to change it.
                            <?php else: ?>
                                If left blank, the default password "password123" will be used.
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-submit"><?php echo $edit_mode ? 'Update User' : 'Create User'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>