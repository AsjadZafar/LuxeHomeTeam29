<?php
session_start();
require_once 'php_functions/dbh.php';

// If cart is empty redirect back
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}
?>






<!DOCTYPE html>
<!-- saved from url=(0152)https://storage.tutorbin.com/production/media/tasks/68e6d30bfbf57b4225fb5f46/task-invites/68e6d30bfbf57b4225fb5f46/solutions/checkout-1759962156703.php -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-commerce</title>
    <link rel="stylesheet" href="css/checkout.css">

</head>
<body data-new-gr-c-s-check-loaded="14.1256.0" data-gr-ext-installed="">
    <div class="container">
        <div>
            <!-- Delivery Address Section -->
            <div class="checkout-section">
                <div class="section-header">
                    <div class="section-number">1</div>
                    <div class="section-title">Delivery address</div>
                    <a href="https://storage.tutorbin.com/production/media/tasks/68e6d30bfbf57b4225fb5f46/task-invites/68e6d30bfbf57b4225fb5f46/solutions/checkout-1759962156703.php#" class="change-btn">Change</a>
                </div>
                <div class="address-info">
                    <p><strong>John Doe</strong></p>
                    <p>123 Main Street</p>
                    <p>New York, NY 10001</p>
                    <p>United States</p>
                </div>
                <div class="link-text">Add delivery instructions</div>
                <div class="link-text">Why can't we pick up and collect? 25 locations near this address</div>
            </div>

            <!-- Payment Method Section -->
            <div class="checkout-section">
                <div class="section-header">
                    <div class="section-number">2</div>
                    <div class="section-title">Payment method</div>
                    <a href="https://storage.tutorbin.com/production/media/tasks/68e6d30bfbf57b4225fb5f46/task-invites/68e6d30bfbf57b4225fb5f46/solutions/checkout-1759962156703.php#" class="change-btn">Change</a>
                </div>
                <div class="payment-methods">
                    <span class="payment-icon">VISA</span>
                    <span>Visa/Delta/Electron ending in 1234</span>
                </div>
                <div class="address-info">
                    <p><strong>Billing address:</strong> Same as delivery address</p>
                </div>
                <div class="link-text">+ Add a gift card or promotional code</div>
                <div class="promo-section">
                    <input type="text" class="promo-input" placeholder="Enter Code">
                    <button class="apply-btn">Apply</button>
                </div>
            </div>

            <!-- Review Items Section -->
            <div class="checkout-section">
    <div class="section-header">
        <div class="section-number">3</div>
        <div class="section-title">Review items and delivery</div>
    </div>

<?php
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $qty) {

    $sql = "SELECT name, price, img FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {

        $lineTotal = $row['price'] * $qty;
        $total += $lineTotal;

        echo "
        <div class='product-item'>
            <div class='product-image'>
                <img src='product_image/{$row['img']}' width='60'>
            </div>
            <div class='product-details'>
                <div class='product-title'>{$row['name']}</div>
                <div class='product-price'>£" . number_format($lineTotal,2) . "</div>
                <div>Qty: {$qty}</div>
            </div>
        </div>";
    }
}
?>
</div>
     
    <div class="delivery-options">
             <p style="font-weight: bold; margin-bottom: 10px;">Choose your Prime delivery option:</p>
                    
             <div class="radio-option">
                        <input type="radio" name="delivery" id="delivery1" checked="">
                        <label for="delivery1">
                            <strong>FREE One-Day Delivery</strong> - get it Tomorrow, Mar. 16
                        </label>
                    </div>

                    <div class="radio-option">
                        <input type="radio" name="delivery" id="delivery2">
                        <label for="delivery2">
                            <strong>FREE Marketplace Day Delivery</strong> - get it Wednesday, Mar. 17<br>
                            <span style="font-size: 13px; color: #565959;">We'll deliver your orders together</span>
                            <a href="https://storage.tutorbin.com/production/media/tasks/68e6d30bfbf57b4225fb5f46/task-invites/68e6d30bfbf57b4225fb5f46/solutions/checkout-1759962156703.php#" style="color: #007185; font-size: 13px; margin-left: 5px;">Choose your Marketplace Day</a>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="order-summary">
            <form method="POST" action="php_functions/place_order.php">
                <button type="submit" class="buy-now-btn">Buy now</button>
            </form>

            
            <div class="terms-text">
                By placing your order, you agree to our Conditions of Use &amp; Sale. Please see our Privacy Notice, our Cookies Notice and our Interest-Based Ads Notice.
            </div>

            <div class="summary-title">Order Summary</div>

            <?php
$shipping = 0;
$grandTotal = $total + $shipping;
?>

<div class="summary-row">
    <span>Items:</span>
    <span>£<?= number_format($total,2) ?></span>
</div>

<div class="summary-row">
    <span>Postage & Packing:</span>
    <span>£<?= number_format($shipping,2) ?></span>
</div>

<div class="summary-total">
    <span>Order Total:</span>
    <span>£<?= number_format($grandTotal,2) ?></span>
</div>


            <div class="vat-note">Order totals include VAT 
            <a href="https://storage.tutorbin.com/production/media/tasks/68e6d30bfbf57b4225fb5f46/task-invites/68e6d30bfbf57b4225fb5f46/solutions/checkout-1759962156703.php#" style="color: #007185;">See details</a></div>

            <div class="delivery-info">
                <strong>How are delivery costs calculated?</strong>
                <p>Our Prime Delivery has been applied to the eligible items in your order.</p>
            </div>
        </div>
    </div>


</body><grammarly-desktop-integration data-grammarly-shadow-root="true"><template shadowrootmode="open">
    
<div aria-label="grammarly-integration" role="group" tabindex="-1" class="grammarly-desktop-integration" data-content="{&quot;mode&quot;:&quot;full&quot;,&quot;isActive&quot;:true,&quot;isUserDisabled&quot;:false}"></div></template></grammarly-desktop-integration>
    


    
    </html>
