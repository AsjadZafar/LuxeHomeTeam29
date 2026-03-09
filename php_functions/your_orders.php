<?php
$orders = getUserOrders($_SESSION['user_id']);
$selected_order = isset($_GET['view']) ? $_GET['view'] : null;

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    addProductReview($_SESSION['user_id'], $_POST['product_id'], $_POST['rating'], $_POST['review']);
    header('Location: customer_dash.php?your_orders&view=' . $_POST['order_id']);
    exit();
}
?>

<?php if ($selected_order): ?>
    <?php
    $order_items = getOrderItems($selected_order);
    $order_details = null;
    $orders->data_seek(0);
    while($order = $orders->fetch_assoc()) {
        if($order['orders_id'] == $selected_order) {
            $order_details = $order;
            break;
        }
    }
    ?>
    
    <!-- Back Button -->
    <div style="margin-bottom: 1.5rem;">
        <a href="customer_dash.php?your_orders" class="btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
            Back to All Orders
        </a>
    </div>
    
    <!-- Order Details -->
    <div class="order-details">
        <div class="order-header">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827;">
                Order #<?php echo $selected_order; ?>
            </h2>
        </div>
        
        <?php if ($order_details): ?>
        <div class="order-info-grid">
            <div class="order-info-item">
                <span class="order-info-label">Order Date</span>
                <span class="order-info-value"><?php echo date('F j, Y', strtotime($order_details['order_date'])); ?></span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Total Amount</span>
                <span class="order-info-value">$<?php echo number_format($order_details['price'], 2); ?></span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Shipping Address</span>
                <span class="order-info-value"><?php echo htmlspecialchars($order_details['address_line1']); ?></span>
                <span style="color: #6b7280; font-size: 0.875rem;">
                    <?php echo htmlspecialchars($order_details['city'] . ', ' . $order_details['postcode']); ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Order Items -->
        <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 1.5rem 0 1rem;">
            <i class="fas fa-box"></i>
            Order Items
        </h3>
        
        <div class="product-items-list">
            <?php while($item = $order_items->fetch_assoc()): 
                $already_reviewed = hasUserReviewedProduct($_SESSION['user_id'], $item['product_id']);
                if ($already_reviewed) {
                    $user_review = getUserProductReview($_SESSION['user_id'], $item['product_id']);
                }
            ?>
            <div class="product-item" style="flex-direction: column; align-items: flex-start;">
                <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 1rem;">
                    <div class="product-item-info">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <?php if ($item['img']): ?>
                        <img src="images/<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 0.5rem; margin-top: 0.5rem;">
                        <?php endif; ?>
                    </div>
                    <div class="product-item-price">
                        $<?php echo number_format($item['price_each'], 2); ?> each
                    </div>
                </div>
                
                <!-- Review Section for this product -->
                <?php if (!$already_reviewed): ?>
                <div style="width: 100%; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #e5e7eb;">
                    <button onclick="toggleReviewForm(<?php echo $item['product_id']; ?>)" class="btn-secondary btn-sm">
                        <i class="fas fa-star"></i>
                        Rate this Product
                    </button>
                    
                    <div id="reviewForm-<?php echo $item['product_id']; ?>" style="display: none; margin-top: 1rem;">
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $selected_order; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            
                            <div class="form-group">
                                <label>Rating</label>
                                <div class="rating-input">
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>-<?php echo $item['product_id']; ?>" required>
                                    <label for="star<?php echo $i; ?>-<?php echo $item['product_id']; ?>">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="review-<?php echo $item['product_id']; ?>">Your Review</label>
                                <textarea id="review-<?php echo $item['product_id']; ?>" name="review" class="form-control" rows="3" 
                                          placeholder="Share your thoughts about this product..."></textarea>
                            </div>
                            
                            <button type="submit" name="submit_review" class="btn-submit btn-sm">
                                <i class="fas fa-paper-plane"></i>
                                Submit Review
                            </button>
                        </form>
                    </div>
                </div>
                <?php elseif ($user_review): ?>
                <div style="width: 100%; margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span class="status-badge status-delivered">
                            <i class="fas fa-check-circle"></i>
                            Reviewed
                        </span>
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="<?php echo $i <= $user_review['rating'] ? 'fas' : 'far'; ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <?php if ($user_review['review']): ?>
                    <p style="color: #4b5563; font-style: italic;">"<?php echo htmlspecialchars($user_review['review']); ?>"</p>
                    <?php endif; ?>
                    <p style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.5rem;">
                        Reviewed on <?php echo date('M d, Y', strtotime($user_review['review_date'])); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <script>
    function toggleReviewForm(productId) {
        var form = document.getElementById('reviewForm-' + productId);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
    </script>
    
<?php else: ?>
    <!-- Orders List -->
    <div class="admin-table-container">
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
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $order['orders_id']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                        <td><?php echo $order['orders_id']; ?></td>
                        <td>$<?php echo number_format($order['price'], 2); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="customer_dash.php?your_orders&view=<?php echo $order['orders_id']; ?>" class="btn-view btn-sm">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem;">
                <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.25rem; color: #4b5563; margin-bottom: 1rem;">No orders yet</h3>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">Start shopping to see your orders here!</p>
                <a href="products.php" class="btn-submit">
                    <i class="fas fa-store"></i>
                    Browse Products
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>