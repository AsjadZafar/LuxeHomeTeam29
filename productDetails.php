<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
$logged_in = false;
$username = "";

if (isset($_SESSION['username'])) {
  $logged_in = true;
  $username = $_SESSION['username'];
}

require_once 'php_functions/dbh.php';

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate cart count for badge
$cart_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $sql = "SELECT * from products WHERE product_id = $id";

  $result = mysqli_query($conn, $sql);

  $row = mysqli_fetch_assoc($result);

  if(!$row) {
    die("Product not found");
  }
} else {
  die("No product ID Provided");
}

//SQL for Reviews

$review_sql = "SELECT r.rating, r.review, r.review_date, u.username
               FROM reviews r
               JOIN users u ON r.user_id = u.user_id
               WHERE r.product_id = $id
               ORDER BY r.review_date DESC";

$review_result = mysqli_query($conn, $review_sql);

//SQL to VIEW the reviews

$review_sql_view = "SELECT r.review_id, r.user_id, r.review, r.rating, r.review_date, 
                   u.username, r.helpful_count,
                   (SELECT COUNT(*) FROM review_helpful h 
                    WHERE h.review_id = r.review_id 
                    AND h.user_id = " . ($_SESSION['user_id'] ?? 0) . ") AS user_voted
               FROM reviews r
               JOIN users u ON r.user_id = u.user_id
               WHERE r.product_id = $id
               ORDER BY r.review_date DESC";

$review_result_view = mysqli_query($conn, $review_sql_view);


?> 

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Product Details | LuxeHome</title>
  <meta name="description" content="Product details and images for LuxeHome smart home products." />
  
  <!-- Favicon -->
  <link rel="icon" href="images/image.png">
  
  <!-- Tailwind & Font Awesome -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- CSS Files -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/accessibility.css">
  
  <style>
    .logo-img{ 
        width:48px; 
        height:48px; 
        border-radius:50%; 
        object-fit:cover;
    }
    
    .nav-link {
        font-size: 0.875rem;
        font-weight: 500;
        color: #4b5563;
        transition: color 0.3s ease;
    }
    
    .nav-link.active {
        color: #047857;
        border-bottom: 2px solid #047857;
        padding-bottom: 0.25rem;
    }
    
    .nav-link:hover {
        color: #047857;
    }
    
    .action-btn {
        color: #4b5563;
        transition: color 0.3s ease;
    }
    
    .action-btn:hover {
        color: #047857;
    }
    
    .cart-badge {
        position: absolute;
        top: -0.5rem;
        right: -0.5rem;
        background: #059669;
        color: white;
        font-size: 0.75rem;
        border-radius: 50%;
        width: 1.25rem;
        height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
  </style>
</head>
<body class="bg-gray-50">
  <!-- Skip Link for Accessibility -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Accessibility Panel -->
  <div id="accessibilityPanel" class="accessibility-panel">
    <div class="accessibility-header">
      <h2 class="accessibility-title">Accessibility Settings</h2>
      <p class="accessibility-subtitle">Customize your browsing experience</p>
      <button id="closePanel" class="accessibility-close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="accessibility-content">
      <div class="accessibility-section">
        <h3 class="accessibility-section-title">
          <i class="fas fa-eye"></i>
          Visual Preferences
        </h3>
        <div class="accessibility-options">
          <div class="accessibility-option">
            <input type="checkbox" id="darkMode">
            <label for="darkMode">Dark Mode</label>
          </div>
          <div class="accessibility-option">
            <input type="checkbox" id="highContrast">
            <label for="highContrast">High Contrast</label>
          </div>
          <div class="accessibility-option">
            <label for="fontSize">Font Size</label>
            <input type="range" id="fontSize" min="0" max="3" value="1">
            <div class="font-size-display" id="fontSizeDisplay">Normal</div>
          </div>
        </div>
      </div>

      <div class="accessibility-section">
        <h3 class="accessibility-section-title">
          <i class="fas fa-text-height"></i>
          Text & Reading
        </h3>
        <div class="accessibility-options">
          <div class="accessibility-option">
            <input type="checkbox" id="dyslexiaFont">
            <label for="dyslexiaFont">Dyslexia-Friendly Font</label>
          </div>
          <div class="accessibility-option">
            <input type="checkbox" id="lineSpacing">
            <label for="lineSpacing">Increased Line Spacing</label>
          </div>
        </div>
      </div>

      <div class="accessibility-section">
        <h3 class="accessibility-section-title">
          <i class="fas fa-mouse-pointer"></i>
          Navigation
        </h3>
        <div class="accessibility-options">
          <div class="accessibility-option">
            <input type="checkbox" id="focusIndicator">
            <label for="focusIndicator">Enhanced Focus Indicators</label>
          </div>
          <div class="accessibility-option">
            <input type="checkbox" id="skipLinks">
            <label for="skipLinks">Enable Skip Links</label>
          </div>
        </div>
      </div>

      <button id="resetSettings" class="accessibility-reset">
        <i class="fas fa-undo"></i> Reset All Settings
      </button>
    </div>
  </div>

  <!-- Accessibility Panel Overlay -->
  <div id="panelOverlay" class="panel-overlay"></div>

  <!-- Accessibility Toggle Button -->
  <button id="togglePanel" class="accessibility-toggle">
    <i class="fas fa-universal-access"></i>
  </button>

  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <a href="index.php" class="flex items-center space-x-3">
          <img src="images/image.png" alt="LuxeHome logo" class="logo-img">
          <div>
            <h1 class="text-xl font-bold text-gray-900">LuxeHome</h1>
            <p class="text-xs text-gray-500">Smart Living Elevated</p>
          </div>
        </a>
  	
  	    <!-- Navigation -->
        <nav class="hidden md:flex space-x-8">
          <a href="index.php" class="nav-link">Home</a>
          <a href="products.php" class="nav-link active">Shop</a>
          <a href="about_us.php" class="nav-link">About us</a>
          <a href="contact.php" class="nav-link">Contact</a>
        </nav>

        <div class="flex items-center space-x-4">
          <a href="products.php" class="nav-link hover:text-emerald-600">Back to products</a>
          
          <?php if ($logged_in): ?>
            <a href="cart.php" class="action-btn relative">
              <i class="fas fa-shopping-cart"></i>
              <span id="cartCount2" class="cart-badge"><?php echo $cart_count; ?></span>
            </a>
            <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
          <?php else: ?>
            <a href="cart.php" class="action-btn relative">
              <i class="fas fa-shopping-cart"></i>
              <span id="cartCount2" class="cart-badge"><?php echo $cart_count; ?></span>
            </a>
            <a href="login.php" class="action-btn">
              <i class="fas fa-user"></i>
            </a>
          <?php endif; ?>
        </div>
        
        <?php if ($logged_in): ?>
          <div class="flex items-center space-x-2 ml-4">
            <form method="POST" action="php_functions/logout.php">
              <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">Log out</button>
            </form>
            <form method="POST" action="admin_dash.php">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Admin Dash</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main id="main-content" class="max-w-6xl mx-auto px-4 py-10">
    <div id="productWrap" class="bg-white rounded-xl shadow p-6 grid lg:grid-cols-2 gap-6">

      <div>
        <img src="product_image/<?= $row['img'] ?>" class="w-full rounded-lg object-cover h-80" alt="<?= htmlspecialchars($row['name']) ?>">
      </div>

      <div>
        <h1 class="text-2xl font-bold mb-2"><?= $row['name'] ?></h1>
        <p class="text-emerald-600 font-semibold text-xl mb-4">&pound;<?= number_format($row['price'], 2) ?></p>
        <p class="text-gray-600 mb-4"><?= $row['description'] ?></p>
        <p class="text-gray-600 mb-4">Category: <?= $row['category'] ?></p>

        <div class="mb-4">
          <label class="block text-sm text-gray-600 mb-2">Quantity in stock: <?= $row['quantity']?></label>
          <input id="qty" type="number" min="1" value="1" max="<?= $row['quantity'] ?>" class="w-24 p-2 border rounded-md" />
        </div>

        <div class="flex gap-3">
    <!-- Add to Cart Form -->
    <form action="php_functions/addToCart.php" method="POST" id="addToCartForm" class="flex-1">
        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
        <input type="hidden" name="quantity" id="quantityInput" value="1">
        <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
            <i class="fas fa-shopping-cart"></i> Add to Basket
        </button>
    </form>
    
    <!-- Add to Wishlist Form -->
    <?php if ($logged_in): ?>
    <form action="php_functions/addToWishlist.php" method="POST" id="addToWishlistForm">
        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
        <?php
        // Check if already in wishlist
        $already_in_wishlist = false;
        if (isset($_SESSION['user_id'])) {
            require_once 'php_functions/dashboard_functions.php';
            global $conn;
            $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
            $check->bind_param("ii", $_SESSION['user_id'], $row['product_id']);
            $check->execute();
            $result = $check->get_result();
            $already_in_wishlist = $result->num_rows > 0;
        }
        ?>
        <button type="submit" class="wishlist-btn <?php echo $already_in_wishlist ? 'bg-pink-600 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'; ?> text-white px-4 py-2 rounded-md transition-colors" <?php echo $already_in_wishlist ? 'disabled' : ''; ?>>
            <i class="fas fa-heart"></i> 
            <?php echo $already_in_wishlist ? 'In Wishlist' : 'Add to Wishlist'; ?>
        </button>
    </form>
    <?php else: ?>
    <a href="login.php" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors inline-block">
        <i class="fas fa-heart"></i> Login to Wishlist
    </a>
    <?php endif; ?>
</div>

<!-- Message display area for wishlist -->
<p id="wishlistMsg" class="text-green-600 mt-4 hidden"></p>

        <p id="addedMsg" class="text-green-600 mt-4 hidden">Added to basket</p>
      </div>
    </div>
  </main>


  <?php
// Get average rating + total reviews for THIS product
$avg_sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
            FROM reviews 
            WHERE product_id = $id";

$avg_result = mysqli_query($conn, $avg_sql);
$avg_data = mysqli_fetch_assoc($avg_result);

$avg_rating = round($avg_data['avg_rating'], 1);
$total_reviews = $avg_data['total_reviews'];
?>

<!-- Blue Section: Responsive Three Parts -->
<div class="w-full mb-3 mt-3">
    <div class="bg-[#0a0f1f] border flex flex-col sm:flex-row">
        <!-- Left third- Installation Services -->
        <div class="flex-1 flex justify-center items-center border-b sm:border-b-0 sm:border-r border-green-500 px-4 py-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white mr-2">
                <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z" clip-rule="evenodd" />
                <path d="m10.076 8.64-2.201-2.2V4.874a.75.75 0 0 0-.364-.643l-3.75-2.25a.75.75 0 0 0-.916.113l-.75.75a.75.75 0 0 0-.113.916l2.25 3.75a.75.75 0 0 0 .643.364h1.564l2.062 2.062 1.575-1.297Z" />
                <path fill-rule="evenodd" d="m12.556 17.329 4.183 4.182a3.375 3.375 0 0 0 4.773-4.773l-3.306-3.305a6.803 6.803 0 0 1-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 0 0-.167.063l-3.086 3.748Zm3.414-1.36a.75.75 0 0 1 1.06 0l1.875 1.876a.75.75 0 1 1-1.06 1.06L15.97 17.03a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
            <span class="text-white text-center">Complimentary installation services</span>
        </div>

        <!-- Middle third- Free Shipping -->
        <div class="flex-1 flex justify-center items-center border-b sm:border-b-0 sm:border-r border-green-500 px-4 py-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white mr-2">
                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z" />
                <path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z" />
                <path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
            </svg>
            <span class="text-white text-center">Free shipping on all orders</span>
        </div>

        <!-- Right third- Free Returns -->
<div class="flex-1 flex justify-center items-center px-4 py-4">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white mr-2">
        <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z" clip-rule="evenodd" />
    </svg>
    <span class="text-white text-center">Free returns on all orders</span>
</div>

    </div>
</div>

<!-- Heading Section -->
<div class="text-center mt-12">
    <h2 class="text-3xl font-bold mb-4 text-gray-900">Customer Reviews</h2>
    <p class="text-gray-600 mb-10">See what customers are saying about this product</p>

    <!-- Green Info Box -->
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 max-w-3xl mx-auto flex justify-between items-center">
        
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>All reviews are from verified purchases</span>
        </div>

        <div class="text-right">
            <span class="font-semibold text-lg">⭐ <?= $avg_rating ? $avg_rating : "0.0" ?>/5</span>
            <span class="text-sm text-gray-600">(based on <?= $total_reviews ?> reviews)</span>
        </div>

    </div>
</div>

<!-- Review Form -->
<?php if ($logged_in): ?>
<form action="php_functions/addReview.php" method="POST" class="mb-8 bg-gray-50 p-6 rounded-lg border max-w-5xl mx-auto">

<input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">

<h3 class="text-lg font-semibold mb-4 text-gray-900">Liked this product? Leave a Review</h3>

<!-- Rating -->
<label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>

<div class="flex gap-2 mb-4">
<?php for($i=1;$i<=5;$i++): ?>
<label class="cursor-pointer">
<input type="radio" name="rating" value="<?= $i ?>" required class="hidden peer">
<i class="fa fa-star text-gray-300 peer-checked:text-yellow-400 text-xl transition-colors"></i>
</label>
<?php endfor; ?>
</div>

<!-- Review Text -->
<label class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>

<textarea
name="review"
rows="4"
class="w-full border rounded-md p-3 mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
placeholder="Share your experience with this product..."
required></textarea>

<!-- Submit Button-->
<button
type="submit"
class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 transition-colors">
<i class="fas fa-paper-plane"></i> Submit Review
</button>

</form>

<?php else: ?>

<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8 max-w-3xl mx-auto text-center">
    <p>
        Want to share your experience? You must be 
        <a href="login.php" class="underline font-medium text-yellow-800 hover:text-yellow-900">logged in</a> 
        to leave a review.
    </p>
</div>

<?php endif; ?>


<!-- Reviews List -->
<div id="reviews" class="mt-10">

<?php if(mysqli_num_rows($review_result_view) > 0): ?>

<div class="space-y-2 w-full mb-16">

<?php while($review = mysqli_fetch_assoc($review_result_view)): ?>

<div class="bg-white border rounded-lg p-5 shadow-sm">

<!-- Username + Date -->
<div class="flex justify-between items-center mb-2">

    <div class="flex items-center gap-2">
        <span class="font-semibold text-gray-800">
            <?= htmlspecialchars($review['username']) ?>
        </span>
        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full flex items-center gap-1">
            <i class="fas fa-check-circle"></i> Verified
        </span>
    </div>

    <span class="text-sm text-gray-500">
        <?= date("M d, Y", strtotime($review['review_date'])) ?>
    </span>

</div>

<!-- Star Rating -->
<div class="text-yellow-400 mb-2">
<?php
for($i=1; $i<=5; $i++){
    echo $i <= $review['rating'] 
        ? '<i class="fa fa-star"></i>' 
        : '<i class="fa fa-star text-gray-300"></i>';
}
?>
</div>

<!-- Review Text -->
<p class="text-gray-700 mb-3">
<?= htmlspecialchars($review['review']) ?>
</p>

<!-- Helpful Section -->
<?php if(isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')): ?>

    <div class="mt-1 text-sm flex items-center gap-4">

        <?php if($review['user_voted'] > 0): ?>
            <span class="text-green-600 flex items-center gap-2">
                <i class="fas fa-check"></i> You marked this as helpful
            </span>
        <?php else: ?>
            <span>Was this review helpful?</span>

            <form action="php_functions/markHelpfulProduct.php" method="POST" class="inline">
                <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                <button type="submit" class="text-green-600 flex items-center gap-2 text-sm">
                    <i class="fas fa-thumbs-up"></i> Yes
                </button>
            </form>
        <?php endif; ?>

        <span class="text-gray-500">
            <?= $review['helpful_count'] ?? 0 ?> people found this helpful
        </span>
    </div>

<?php elseif(!isset($_SESSION['user_id'])): ?>

    <div class="mt-1 text-sm text-gray-500">
        <?= $review['helpful_count'] ?? 0 ?> people found this helpful . 
        <a href="login.php" class="text-green-600 hover:underline">Log in</a> to mark as helpful
    </div>

<?php endif; ?>

<!-- Delete button -->
<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
<form action="php_functions/deleteReview.php" method="POST" class="mt-1">
    <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
    <input type="hidden" name="product_id" value="<?= $id ?>">
    
    <button type="submit" class="text-red-600 text-sm hover:underline">
        <i class="fas fa-trash"></i> Delete Review
    </button>
</form>
<?php endif; ?>

</div>

<?php endwhile; ?>

</div>

<?php else: ?>

<p class="text-gray-500 text-center mb-16">
No reviews yet. Be the first to review this product.
</p>

<?php endif; ?>

</div>

</div>

<!--End of View the Review-->

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand-logo">
            <img src="images/image.png" alt="LuxeHome Logo" class="logo-img">
            <div>
              <h3 class="brand-name">LuxeHome</h3>
              <p class="brand-tagline">Smart Living Elevated</p>
            </div>
          </div>
          <p class="brand-description">
            Experience the pinnacle of intelligent living with our curated collection of premium smart home technology designed for modern lifestyles.
          </p>
          <div class="social-links">
            <a href="#" class="social-link">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-link">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="social-link">
              <i class="fab fa-pinterest"></i>
            </a>
          </div>
        </div>
        
        <div class="footer-links">
          <h4 class="footer-heading">Quick Links</h4>
          <ul class="footer-list">
            <li><a href="index.php" class="footer-link">Home</a></li>
            <li><a href="products.php" class="footer-link">Shop</a></li>
            <li><a href="about_us.php" class="footer-link">About us</a></li>
            <li><a href="contact.php" class="footer-link">Contact</a></li>
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
          © 2023 LuxeHome. All rights reserved. | 
          <a href="#" class="footer-legal-link">Privacy Policy</a> | 
          <a href="#" class="footer-legal-link">Terms of Service</a>
        </p>
      </div>
    </div>
  </footer>

  <script>
    // Update quantity input when user changes the quantity field
    document.getElementById('qty').addEventListener('change', function() {
      document.getElementById('quantityInput').value = this.value;
    });

    // Handle form submission
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('php_functions/addToCart.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        // Show success message
        document.getElementById('addedMsg').classList.remove('hidden');
        
        // Update cart count
        fetch('getCartCount.php')
          .then(response => response.text())
          .then(count => {
            document.querySelectorAll('.cart-badge').forEach(badge => {
              badge.textContent = count;
            });
          });
        
        // Hide message after 2 seconds
        setTimeout(() => {
          document.getElementById('addedMsg').classList.add('hidden');
        }, 2000);
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to add item to cart. Please try again.');
      });
    });

    // Buy Now button functionality (dummy checkout)
    document.getElementById('buyBtn')?.addEventListener('click', () => {
      alert('Dummy checkout — payment integration not implemented in this demo.');
    });
  </script>
  
  <script src="js/script.js"></script>
  <script src="js/accessibility.js"></script>
</body>
</html>