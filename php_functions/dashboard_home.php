<?php
$user = getUserDetails($_SESSION['user_id']);
$recent_orders = getUserOrders($_SESSION['user_id']);

$wishlist_count = getWishlistCount($_SESSION['user_id']);
$address_count = getUserAddresses($_SESSION['user_id'])->num_rows;
?>

<!-- Welcome Message -->
<div class="welcome-message">
    <h2 class="welcome-title">Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p class="welcome-text">Manage your account, track orders, and update your preferences from your personal dashboard.</p>
</div>

<!-- Dashboard Stats Cards -->
<div class="dashboard-cards">
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Total Orders</h3>
            <div class="card-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <div class="card-stat"><?php echo $recent_orders->num_rows; ?></div>
        <div class="card-label">Orders placed to date</div>
    </div>
    
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Saved Addresses</h3>
            <div class="card-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
        </div>
        <div class="card-stat"><?php echo $address_count; ?></div>
        <div class="card-label">Delivery addresses</div>
    </div>
</div>

<!-- Recent Orders Section -->
<?php if ($recent_orders->num_rows > 0): ?>
<div class="admin-table-container">
    <h3 class="section-title">
        <i class="fas fa-history"></i>
        Recent Orders
    </h3>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            while($order = $recent_orders->fetch_assoc()): 
                if($count >= 5) break;
                $count++;
            ?>
            <tr>
                <td>#<?php echo $order['order_id']; ?></td>
                <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                <td><?php echo isset($order['item_count']) ? $order['item_count'] : 0; ?></td>
                <td>&pound;<?php echo number_format((float)($order['total'] ?? $order['price'] ?? 0), 2); ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="customer_dash.php?your_orders&view=<?php echo $order['order_id']; ?>" class="btn-view btn-sm">
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="admin-table-container">
    <div style="text-align: center; padding: 3rem;">
        <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.25rem; color: #4b5563; margin-bottom: 1rem;">No orders yet</h3>
        <p style="color: #6b7280; margin-bottom: 1.5rem;">Start shopping to see your orders here!</p>
        <a href="products.php" class="btn-submit">
            <i class="fas fa-store"></i>
            Browse Products
        </a>
    </div>
</div>
<?php endif; ?>