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
<!-- saved from url=(0150)https://storage.tutorbin.com/production/media/orders/68e69b3a7a59693e5cef7fa7/chats/632ac8cba7723378033e68f1/checkout_1759962156703-1764715241581.html -->
<html lang="en"><script src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/content/location/location.js" id="eppiocemhmnlbhjplcgkofciiegomcon"></script><script src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/libs/extend-native-history-api.js"></script><script src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/libs/requests.js"></script><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
    <title>Checkout - E-commerce</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .checkout-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .section-number {
            background: #f0f0f0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            flex: 1;
        }

        .change-btn {
            color: #007185;
            text-decoration: none;
            font-size: 14px;
        }

        .change-btn:hover {
            text-decoration: underline;
        }

        .address-info {
            margin: 15px 0;
            color: #333;
        }

        .address-info p {
            margin: 5px 0;
        }

        .link-text {
            color: #007185;
            cursor: pointer;
            font-size: 14px;
            margin: 10px 0;
        }

        .link-text:hover {
            text-decoration: underline;
        }

        .payment-methods {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .payment-icon {
            font-weight: bold;
            color: #333;
        }

        .promo-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .promo-input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .apply-btn {
            padding: 8px 20px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }

        .apply-btn:hover {
            background: #e0e0e0;
        }

        .alert-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            display: flex;
            align-items: start;
            gap: 10px;
        }

        .alert-icon {
            color: #ff9800;
            font-size: 20px;
            font-weight: bold;
        }

        .info-box {
            background: #e7f4f9;
            border: 2px solid #00a8e1;
            border-left: 4px solid #00a8e1;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .info-box-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .delivery-guarantee {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .product-item {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .product-image {
            width: 80px;
            height: 80px;
            background: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-details {
            flex: 1;
        }

        .product-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-price {
            color: #b12704;
            font-weight: bold;
            margin: 5px 0;
        }

        .delivery-options {
            margin: 15px 0;
        }

        .radio-option {
            display: flex;
            align-items: start;
            gap: 10px;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            cursor: pointer;
        }

        .radio-option:hover {
            background: #f9f9f9;
        }

        .radio-option input[type="radio"] {
            margin-top: 3px;
        }

        .order-summary {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .buy-now-btn {
            width: 100%;
            padding: 12px;
            background: #28a745;
            border: 1px solid #a88734;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .buy-now-btn:hover {
            background: linear-gradient(to bottom, #f5d78e, #edb932);
        }

        .terms-text {
            font-size: 11px;
            color: #565959;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 14px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            font-weight: bold;
            color: #b12704;
            font-size: 18px;
        }

        .vat-note {
            font-size: 12px;
            color: #565959;
        }

        .delivery-info {
            font-size: 12px;
            color: #565959;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
<script bis_use="true" type="text/javascript" charset="utf-8" data-bis-config="[&quot;facebook.com/&quot;,&quot;twitter.com/&quot;,&quot;youtube-nocookie.com/embed/&quot;,&quot;//vk.com/&quot;,&quot;//www.vk.com/&quot;,&quot;linkedin.com/&quot;,&quot;//www.linkedin.com/&quot;,&quot;//instagram.com/&quot;,&quot;//www.instagram.com/&quot;,&quot;//www.google.com/recaptcha/api2/&quot;,&quot;//hangouts.google.com/webchat/&quot;,&quot;//www.google.com/calendar/&quot;,&quot;//www.google.com/maps/embed&quot;,&quot;spotify.com/&quot;,&quot;soundcloud.com/&quot;,&quot;//player.vimeo.com/&quot;,&quot;//disqus.com/&quot;,&quot;//tgwidget.com/&quot;,&quot;//js.driftt.com/&quot;,&quot;friends2follow.com&quot;,&quot;/widget&quot;,&quot;login&quot;,&quot;//video.bigmir.net/&quot;,&quot;blogger.com&quot;,&quot;//smartlock.google.com/&quot;,&quot;//keep.google.com/&quot;,&quot;/web.tolstoycomments.com/&quot;,&quot;moz-extension://&quot;,&quot;chrome-extension://&quot;,&quot;/auth/&quot;,&quot;//analytics.google.com/&quot;,&quot;adclarity.com&quot;,&quot;paddle.com/checkout&quot;,&quot;hcaptcha.com&quot;,&quot;recaptcha.net&quot;,&quot;2captcha.com&quot;,&quot;accounts.google.com&quot;,&quot;www.google.com/shopping/customerreviews&quot;,&quot;buy.tinypass.com&quot;,&quot;gstatic.com&quot;,&quot;secureir.ebaystatic.com&quot;,&quot;docs.google.com&quot;,&quot;contacts.google.com&quot;,&quot;github.com&quot;,&quot;mail.google.com&quot;,&quot;chat.google.com&quot;,&quot;audio.xpleer.com&quot;,&quot;keepa.com&quot;,&quot;static.xx.fbcdn.net&quot;,&quot;sas.selleramp.com&quot;,&quot;1plus1.video&quot;,&quot;console.googletagservices.com&quot;,&quot;//lnkd.demdex.net/&quot;,&quot;//radar.cedexis.com/&quot;,&quot;//li.protechts.net/&quot;,&quot;challenges.cloudflare.com/&quot;,&quot;ogs.google.com&quot;]" src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/executors/traffic.js"></script></head>
<body bis_register="W3sibWFzdGVyIjp0cnVlLCJleHRlbnNpb25JZCI6ImVwcGlvY2VtaG1ubGJoanBsY2drb2ZjaWllZ29tY29uIiwiYWRibG9ja2VyU3RhdHVzIjp7IkRJU1BMQVkiOiJkaXNhYmxlZCIsIkZBQ0VCT09LIjoiZGlzYWJsZWQiLCJUV0lUVEVSIjoiZGlzYWJsZWQiLCJSRURESVQiOiJkaXNhYmxlZCIsIlBJTlRFUkVTVCI6ImRpc2FibGVkIiwiSU5TVEFHUkFNIjoiZGlzYWJsZWQiLCJUSUtUT0siOiJkaXNhYmxlZCIsIkxJTktFRElOIjoiZGlzYWJsZWQiLCJDT05GSUciOiJkaXNhYmxlZCJ9LCJ2ZXJzaW9uIjoiMi4wLjM1Iiwic2NvcmUiOjIwMDM1fV0=" __processed_2abea0f6-4946-4bba-bb31-ce2e4f7d9891__="true">
    <header>
        <h4>E-commerce store</h4>
        <marquee behavior="scroll" direction="left"></marquee>
        <hr>
    </header>
    <div class="container" bis_skin_checked="1">
        <div bis_skin_checked="1">
            <!-- Delivery Address Section -->
            <div class="checkout-section" bis_skin_checked="1">
                <div class="section-header" bis_skin_checked="1">
                    <div class="section-number" bis_skin_checked="1">1</div>
                    <div class="section-title" bis_skin_checked="1">Delivery address</div>
                    <a href="https://storage.tutorbin.com/production/media/orders/68e69b3a7a59693e5cef7fa7/chats/632ac8cba7723378033e68f1/checkout_1759962156703-1764715241581.html#" class="change-btn">Change</a>
                </div>
                <div class="address-info" bis_skin_checked="1">
                    <p><strong>John Doe</strong></p>
                    <p>123 Main Street</p>
                    <p>New York, NY 10001</p>
                    <p>United States</p>
                </div>
                <div class="link-text" bis_skin_checked="1">Add delivery instructions</div>
                <div class="link-text" bis_skin_checked="1">Why can't we pick up and collect? 25 locations near this address</div>
            </div>

            <!-- Payment Method Section -->
            <div class="checkout-section" bis_skin_checked="1">
                <div class="section-header" bis_skin_checked="1">
                    <div class="section-number" bis_skin_checked="1">2</div>
                    <div class="section-title" bis_skin_checked="1">Payment method</div>
                    <a href="https://storage.tutorbin.com/production/media/orders/68e69b3a7a59693e5cef7fa7/chats/632ac8cba7723378033e68f1/checkout_1759962156703-1764715241581.html#" class="change-btn">Change</a>
                </div>
                <div class="payment-methods" bis_skin_checked="1">
                    <span class="payment-icon">VISA</span>
                    <span>Visa/Delta/Electron ending in 1234</span>
                </div>
                <div class="address-info" bis_skin_checked="1">
                    <p><strong>Billing address:</strong> Same as delivery address</p>
                </div>
                <div class="link-text" bis_skin_checked="1">+ Add a gift card or promotional code</div>
                <div class="promo-section" bis_skin_checked="1">
                    <input type="text" class="promo-input" placeholder="Enter Code">
                    <button class="apply-btn">Apply</button>
                </div>
            </div>

            <!-- Review Items Section -->
            <div class="checkout-section" bis_skin_checked="1">
                <div class="section-header" bis_skin_checked="1">
                    <div class="section-number" bis_skin_checked="1">3</div>
                    <div class="section-title" bis_skin_checked="1">Review items and delivery</div>
                </div>
                
                <?php
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $qty) {

    $sql = "SELECT name, price, img FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {

        $lineTotal = $row['price'] * $qty;
        $total += ($lineTotal * 1.13);

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

                <div class="alert-box" bis_skin_checked="1">
                    <span class="alert-icon">⚠</span>
                    <div bis_skin_checked="1">
                        <strong>Signature required at time of delivery.</strong>
                        <p>Please ensure someone will be available to sign for this delivery.</p>
                    </div>
                </div>

                <div class="info-box" bis_skin_checked="1">
                    <div class="info-box-title" bis_skin_checked="1">Delivery on the day that you choose, in fewer boxes</div>
                    <p>Select FREE Delivery below to have orders delivered together, on the day of your choice</p>
                </div>

                <div class="delivery-guarantee" bis_skin_checked="1">
                    <strong>Guaranteed delivery: 16 Mar. 2021</strong>
                    <p style="font-size: 13px; color: #565959;">If you order in the next 4 hours and 20 minutes. Details</p>
                    <p style="font-size: 12px; color: #565959;">Items dispatched from EU Sarl</p>
                </div>


                <div class="delivery-options" bis_skin_checked="1">
                    <p style="font-weight: bold; margin-bottom: 10px;">Choose your Prime delivery option:</p>
                    
                    <div class="radio-option" bis_skin_checked="1">
                        <input type="radio" name="delivery" id="delivery1" checked="">
                        <label for="delivery1">
                            <strong>FREE One-Day Delivery</strong> - get it Tomorrow, Mar. 16
                        </label>
                    </div>

                    <div class="radio-option" bis_skin_checked="1">
                        <input type="radio" name="delivery" id="delivery2">
                        <label for="delivery2">
                            <strong>FREE Marketplace Day Delivery</strong> - get it Wednesday, Mar. 17<br>
                            <span style="font-size: 13px; color: #565959;">We'll deliver your orders together</span>
                            <a href="https://storage.tutorbin.com/production/media/orders/68e69b3a7a59693e5cef7fa7/chats/632ac8cba7723378033e68f1/checkout_1759962156703-1764715241581.html#" style="color: #007185; font-size: 13px; margin-left: 5px;">Choose your Marketplace Day</a>
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

            <div class="vat-note" bis_skin_checked="1">Order totals include VAT <a href="https://storage.tutorbin.com/production/media/orders/68e69b3a7a59693e5cef7fa7/chats/632ac8cba7723378033e68f1/checkout_1759962156703-1764715241581.html#" style="color: #007185;">See details</a></div>

            <div class="delivery-info" bis_skin_checked="1">
                <strong>How are delivery costs calculated?</strong>
                <p>Our Prime Delivery has been applied to the eligible items in your order.</p>
            </div>
        </div>
    </div>
                                        
                                        
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand-logo">
                        <div class="logo-icon"><i class="fas fa-home text-white"></i></div>
                        <div>
                            <h3 class="brand-name">LuxeHome</h3>
                            <p class="brand-tagline">Smart Living Elevated</p>
                        </div>
                    </div>
                    <p class="brand-description">
                        Experience the pinnacle of intelligent living with our curated collection of premium smart home
                        technology designed for modern lifestyles.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>

                <div class="footer-links">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-list">
                        <li><a href="index.php" class="footer-link">Home</a></li>
                        <li><a href="#" class="footer-link">Shop</a></li>
                        <li><a href="#" class="footer-link">Collections</a></li>
                        <li><a href="#" class="footer-link">Inspiration</a></li>
                        <li><a href="contacttt.php" class="footer-link">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h4 class="footer-heading">Contact</h4>
                    <ul class="footer-list">
                        <li>hello@luxehome.com</li>
                        <li>1-800-LUXE-HOME</li>
                        <li>Mon-Fri: 9am-6pm EST</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copyright">
                    Â© 2023 LuxeHome. All rights reserved. |
                    <a href="#" class="footer-legal-link">Privacy Policy</a> |
                    <a href="#" class="footer-legal-link">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>




<div id="give-freely-root-ejkiikneibegknkgimmihdpcbcedgmpo" class="give-freely-root" data-extension-id="ejkiikneibegknkgimmihdpcbcedgmpo" data-extension-name="Volume Booster" style="display: block;" bis_skin_checked="1"><template shadowrootmode="open"><style>
  :host {
    all: initial;
  }

  .gf-scroll-remove::-webkit-scrollbar {
    border-radius-bottom-right: 15px;
  }

  button {
    cursor: pointer;
    transition: transform 0.1s ease;
  }

  button:active {
    transform: scale(0.98);
  }

  .give-freely-close-button:hover {
    opacity: 0.7;
  }

  input[type="radio"] {
    margin-right: 8px;
  }

  hr {
    border: none;
    border-top: 1px solid #e5e5e5;
    margin: 1em 0;
  }

  @media (max-width: 600px), (max-height: 480px) {
    #give-freely-checkout-popup {
      display: none !important;
    }
  }
</style><div><div class="gf-app"></div></div></template></div></body></html>
