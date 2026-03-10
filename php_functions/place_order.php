<?php
session_start();

// Clear cart
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Redirect to a confirmation page
header("Location: /index.php");
exit;
?>
<?php
session_start();
require_once 'dbh.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

// Get user ID
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];
$stmt->close();

if (empty($_SESSION['cart'])) {
    header('Location: ../cart.php');
    exit();
}

$address_id = intval($_POST['address_id'] ?? 0);
$total = floatval($_POST['total'] ?? 0);

// If no address provided, try to get the user's first address
if ($address_id == 0) {
    $stmt = $conn->prepare("SELECT address_id FROM addresses WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $addr = $result->fetch_assoc();
    $address_id = $addr['address_id'] ?? 0;
    $stmt->close();
}

if ($address_id == 0) {
    // No address – redirect to add address page
    header('Location: ../add_address.php');
    exit();
}

// Begin transaction
mysqli_begin_transaction($conn);

try {
    // Insert order
    $order_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO orders (user_id, address_id, order_date, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $user_id, $address_id, $order_date, $price);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert each order item
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $product_id = intval($product_id);
        // Get current price
        $price_stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
        $price_stmt->bind_param("i", $product_id);
        $price_stmt->execute();
        $price_res = $price_stmt->get_result();
        $price_row = $price_res->fetch_assoc();
        $price = $price_row['price'];
        $price_stmt->close();

        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_each) VALUES (?, ?, ?, ?)");
        $item_stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
        $item_stmt->execute();
        $item_stmt->close();
    }

    mysqli_commit($conn);
    unset($_SESSION['cart']); // clear cart

    // Redirect to confirmation page
    header("Location: ../order_confirmation.php?order_id=$order_id");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log("Order placement failed: " . $e->getMessage());
    header('Location: ../checkout.php?error=1');
    exit();
}
?>
