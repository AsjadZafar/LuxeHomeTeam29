<?php
$user_id = $_SESSION['user_id'];

// Show order details if a specific order is requested
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $order_id = intval($_GET['view']);
    $items = getOrderItems($order_id);
    if ($items->num_rows == 0) {
        echo '<div class="admin-table-container"><p>Order not found or has no items.</p></div>';
        return;
    }
    ?>
    <div class="admin-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 class="section-title">Order #<?php echo $order_id; ?> Details</h3>
            <a href="customer_dash.php?your_orders" class="btn-edit" style="padding: 0.5rem 1rem;">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price Each</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                while ($item = $items->fetch_assoc()): 
                    $subtotal = $item['price_each'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <?php if (!empty($item['img'])): ?>
                                <img src="/product_image/<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 0.5rem;">
                            <?php endif; ?>
                            <div>
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                <?php if (!empty($item['description'])): ?>
                                    <br><small><?php echo substr(htmlspecialchars($item['description']), 0, 50); ?>...</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>&pound;<?php echo number_format((float)$item['price_each'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>&pound;<?php echo number_format((float)$subtotal, 2); ?></td>
                    <td> 

                     <div class="action-buttons">
                 <form action="php_functions/return_function.php" method="POST" style="display:inline;">
            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">

             <button type="submit"
            class="btn-view btn-sm bg-red-600 hover:bg-red-700 text-white border-none">
            <i class="fas fa-undo"></i>
            Return
        </button>
    </form>
</div>   
</td>
                </tr>
                <?php endwhile; ?>
                <tr style="font-weight: bold; background-color: #f9fafb;">
                    <td colspan="3" style="text-align: right;">Order Total:</td>
                    <td>&pound;<?php echo number_format((float)$total, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
} else {
    // List all orders
    $orders = getUserOrders($user_id);
    ?>
    <div class="admin-table-container">
        <h3 class="section-title">All Orders</h3>
        <?php if ($orders->num_rows > 0): ?>
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
                <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                    <td><?php echo $order['item_count']; ?></td>
                    <td>&pound;<?php echo number_format((float)$order['total'], 2); ?></td>
                    <td>
                        <a href="customer_dash.php?your_orders&view=<?php echo $order['order_id']; ?>" class="btn-view btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No orders found.</p>
        <?php endif; ?>
    </div>
    <?php
}
?>