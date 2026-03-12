<?php
global $conn;
$user_id = $_SESSION['user_id'];
$query = "
    SELECT 
        w.warrant_id,
        w.warranty_reason,
        w.warranty_date,
        w.status,
        w.img,
        p.name as product_name,
        p.product_id
    FROM warranty w
    JOIN products p ON w.product_id = p.product_id
    WHERE w.user_id = $user_id
    ORDER BY w.warranty_date DESC
";
$result = mysqli_query($conn, $query);
?>
<div class="admin-table-container">
    <h3 class="section-title">My Warranty Claims</h3>
    <?php if (mysqli_num_rows($result) > 0): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Status</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>#<?php echo $row['warrant_id']; ?></td>
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
                    <?php if (!empty($row['img'])): 
                        $image_path = '/warranty_image/' . urlencode($row['img']);
                        // Optional: check if file exists on server (for better error handling)
                        $file_on_server = $_SERVER['DOCUMENT_ROOT'] . $image_path;
                        if (file_exists($file_on_server)): ?>
                            <a href="<?php echo $image_path; ?>" target="_blank">
                                <img src="<?php echo $image_path; ?>" alt="Damage proof" style="max-width:80px; max-height:80px; object-fit:cover; border-radius:0.5rem;">
                            </a>
                        <?php else: ?>
                            <span class="text-gray-400">File missing</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span>No image</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>You haven't submitted any warranty claims yet.</p>
    <?php endif; ?>
</div>