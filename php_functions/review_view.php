<?php
global $conn;
$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        r.review_id,
        r.review,
        r.rating,
        r.review_date,
        p.name AS product_name,
        p.img,
        p.product_id
    FROM reviews r
    JOIN products p ON r.product_id = p.product_id
    WHERE r.user_id = $user_id
    ORDER BY r.review_date DESC
";
$result = mysqli_query($conn, $query);
?>

<div class="admin-table-container">
    <h3 class="section-title"><i class="fas fa-star"></i> My Reviews</h3>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <img src="product_image/<?php echo htmlspecialchars($row['img']); ?>"
                             alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                             style="width:50px; height:50px; object-fit:cover; border-radius:0.5rem;">
                        <span><?php echo htmlspecialchars($row['product_name']); ?></span>
                    </div>
                </td>
                <td style="max-width:300px;"><?php echo nl2br(htmlspecialchars($row['review'])); ?></td>
                <td>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star" style="color: <?php echo $i <= $row['rating'] ? '#f59e0b' : '#d1d5db'; ?>; font-size:0.9rem;"></i>
                    <?php endfor; ?>
                    <span style="margin-left:0.25rem; color:#6b7280; font-size:0.85rem;">(<?php echo $row['rating']; ?>/5)</span>
                </td>
                <td><?php echo date('M d, Y', strtotime($row['review_date'])); ?></td>
                <td>
                    <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                        <a href="productDetails.php?id=<?php echo $row['product_id']; ?>" class="btn-edit" style="font-size:0.8rem; padding:0.4rem 0.75rem;">
                            <i class="fas fa-eye"></i> View
                        </a>

                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
    <div class="welcome-message" style="background: linear-gradient(135deg, #fef3c7, #fffbeb); border-left-color: #f59e0b;">
        <h2 class="welcome-title" style="color:#92400e;">
            <i class="fas fa-star"></i> No Reviews Yet
        </h2>
        <p class="welcome-text" style="color:#b45309;">
            You haven't reviewed any products yet. Purchase a product and share your experience!
        </p>
        <a href="products.php" class="btn-submit" style="display:inline-flex; margin-top:1rem;">
            <i class="fas fa-shopping-bag"></i> Browse Products
        </a>
    </div>
    <?php endif; ?>
</div>

