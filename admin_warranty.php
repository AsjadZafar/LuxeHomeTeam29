<?php
session_start();
require_once 'php_functions/dbh.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $claim_id = intval($_POST['claim_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $update = "UPDATE warranty SET status = '$new_status' WHERE warrant_id = $claim_id";
    mysqli_query($conn, $update);
    header('Location: admin_warranty.php');
    exit();
}

// Fetch all warranty claims
$query = "
    SELECT 
        w.warrant_id,
        w.warranty_reason,
        w.warranty_date,
        w.status,
        w.img,
        u.username,
        u.email,
        u.user_id,
        p.name as product_name,
        p.product_id
    FROM warranty w
    JOIN users u ON w.user_id = u.user_id
    JOIN products p ON w.product_id = p.product_id
    ORDER BY w.warranty_date DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Claims | Admin</title>
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/adminstyle.css">
    <style>
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .warranty-img-thumb {
            max-width: 60px;
            max-height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #ddd;
        }
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
                <li><a href="admin_warranty.php" class="active"><i class="fas fa-clipboard-list"></i> <span>Warranty</span></a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Warranty Claims</h1>
                <a href="index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="admin-table-container">
                <h2 class="section-title">All Warranty Claims</h2>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['warrant_id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['username']); ?></strong><br>
                                <small><?php echo htmlspecialchars($row['email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['warranty_reason'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['warranty_date'])); ?></td>
                            <td>
                                <?php
                                $status_class = '';
                                if ($row['status'] == 'Pending') $status_class = 'status-pending';
                                elseif ($row['status'] == 'Approved') $status_class = 'status-approved';
                                elseif ($row['status'] == 'Rejected') $status_class = 'status-rejected';
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span>
                            </td>
                            <td>
                                <?php if (!empty($row['img'])): ?>
                                    <a href="warranty_image/<?php echo urlencode($row['img']); ?>" target="_blank">
                                        <img src="warranty_image/<?php echo htmlspecialchars($row['img']); ?>" alt="Damage proof" class="warranty-img-thumb">
                                    </a>
                                <?php else: ?>
                                    <span>No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="claim_id" value="<?php echo $row['warrant_id']; ?>">
                                    <select name="status" class="form-control" style="width:100px; display:inline-block;">
                                        <option value="Approved">Approve</option>
                                        <option value="Rejected">Reject</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-edit">Update</button>
                                </form>
                                <?php else: ?>
                                    <span class="text-gray-500">Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No warranty claims found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>