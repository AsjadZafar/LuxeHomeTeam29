<?php
$wishlist_items = getWishlistItems($_SESSION['user_id']);

// Handle remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_wishlist'])) {
    removeFromWishlist($_SESSION['user_id'], $_POST['product_id']);
    header('Location: customer_dash.php?wishlist');
    exit();
}

// Handle add to cart from wishlist – also remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addToCart'])) {
    $product_id = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    // Simple list – if you want to avoid duplicates, check first
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
    
    // Remove from wishlist
    removeFromWishlist($_SESSION['user_id'], $product_id);
    
    // Redirect to cart page
    exit();
}
?>

<div class="admin-table-container">
    <div class="admin-badge" style="margin-bottom: 2rem;">
        <i class="fas fa-heart"></i>
        <span class="admin-badge-text">My Wishlist (<?php echo getWishlistCount($_SESSION['user_id']); ?> items)</span>
    </div>
    
    <?php if ($wishlist_items && $wishlist_items->num_rows > 0): ?>
        <div class="wishlist-grid">
            <?php while($item = $wishlist_items->fetch_assoc()): ?>
            <div class="wishlist-card">
                <form method="POST" class="remove-wishlist">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <button type="submit" name="remove_from_wishlist" title="Remove from wishlist">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                
                <?php if ($item['img']): ?>
                <div style="text-align: center; margin-bottom: 1rem;">
                    <img src="/product_image/<?php echo htmlspecialchars($item['img']); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 0.5rem;">
                </div>
                <?php endif; ?>
                
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem; height: 60px; overflow: hidden;">
                    <?php echo substr(htmlspecialchars($item['description'] ?? ''), 0, 100); ?>...
                </p>
                <div class="wishlist-price">&pound;<?php echo number_format($item['price'], 2); ?></div>
                
                <?php if (!empty($item['installation_available']) && $item['installation_available'] == 1): ?>
                <p style="color: #059669; font-size: 0.75rem; margin-bottom: 1rem;">
                    <i class="fas fa-tools"></i>
                    Installation Available
                </p>
                <?php endif; ?>
                
                <div class="wishlist-actions">
                    <a href="productDetails.php?id=<?php echo $item['product_id']; ?>" class="btn-view btn-sm" style="flex: 1;">
                        <i class="fas fa-eye"></i>
                        View
                    </a>
                    <form method="POST" style="flex: 1;">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-heart" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
            <h3 style="font-size: 1.25rem; color: #4b5563; margin-bottom: 1rem;">Your wishlist is empty</h3>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Save items you love to your wishlist!</p>
            <a href="products.php" class="btn-submit">
                <i class="fas fa-store"></i>
                Browse Products
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.wishlist-card {
    background: white;
    border: 1px solid #f3f4f6;
    border-radius: 0.75rem;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
}

.wishlist-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.wishlist-card h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
    padding-right: 2rem;
}

.wishlist-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #059669;
    margin: 0.5rem 0;
}

.wishlist-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.remove-wishlist {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 1.125rem;
    transition: color 0.3s ease;
}

.remove-wishlist:hover {
    color: #dc2626;
}
</style>