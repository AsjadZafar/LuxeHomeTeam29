<?php
global $conn;
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT pr.return_id, pr.product_id, pr.order_id, pr.return_date, status, p.name AS product_name
    FROM product_return pr
    JOIN orders o ON pr.order_id = o.order_id
    JOIN products p ON pr.product_id = p.product_id
    WHERE o.user_id = ?
    ORDER BY pr.return_date DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="admin-table-container">
    <h3 class="section-title">My Returns</h3>
    <?php if (mysqli_num_rows($result) > 0): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Return ID</th>
                <th>Product ID</th>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>#<?php echo $row['return_id']; ?></td>
                <td>#<?php echo $row['product_id']; ?></td>
                <td>#<?php echo $row['order_id']; ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['return_date'])); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No returns have been made.</p>
    <?php endif; ?>
</div>