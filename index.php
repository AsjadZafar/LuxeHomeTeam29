<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$logged_in = false;
$username = "";

if (isset($_SESSION['username'])) {
  $logged_in = true;
  $username = $_SESSION['username'];
}

require_once 'php_functions/dbh.php';

// Helper for cart count
function getCartCount() {
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LuxeHome | Premium Smart Living</title>
  <link rel="icon" href="images/image.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/accessibility.css">
  <style>
    .logo-img {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>
<body class="bg-gray-50">
  <!-- Skip Link for Accessibility -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Accessibility Panel (same as before) -->
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

  <div id="panelOverlay" class="panel-overlay"></div>
  <button id="togglePanel" class="accessibility-toggle">
    <i class="fas fa-universal-access"></i>
  </button>

  <!-- Header -->
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
          <a href="index.php" class="nav-link active">Home</a>
          <a href="products.php" class="nav-link">Shop</a>
          <a href="about_us.php" class="nav-link">About us</a>
          <a href="contact.php" class="nav-link">Contact</a>
        </nav>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
          <button class="action-btn">
            <i class="fas fa-search"></i>
          </button>
          
          <?php if ($logged_in): ?>
            <a href="cart.php" class="action-btn relative">
              <i class="fas fa-shopping-cart"></i>
              <span class="cart-badge"><?php echo getCartCount(); ?></span>
            </a>
            <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
          <?php else: ?>
            <a href="cart.php" class="action-btn relative">
              <i class="fas fa-shopping-cart"></i>
              <span class="cart-badge"><?php echo getCartCount(); ?></span>
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
            <form method="POST" action="customer_dash.php">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Customer Dash</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Hero Section (unchanged) -->
  <section class="hero-section">
    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-badge">
          <i class="fas fa-star text-emerald-400"></i>
          <span class="hero-badge-text">Premium Smart Home Collection</span>
        </div>
        <h1 class="hero-title">
          Elevate Your<br>
          <span class="text-gradient">Living Experience</span>
        </h1>
        <p class="hero-description">
          Discover the finest collection of intelligent home technology, meticulously curated for the discerning homeowner who demands excellence in design, performance, and innovation.
        </p>
        <div class="hero-buttons">
          <a href="products.php" class="btn-primary">
            Explore Collection
            <i class="fas fa-arrow-right ml-2"></i>
          </a>
          <a href="contact.php" class="btn-secondary">
            Book Consultation
          </a>
        </div>
      </div>
      <div class="hero-visual">
        <div class="hero-card hero-card-back"></div>
        <div class="hero-card hero-card-front">
          <div class="card-content">
            <i class="fas fa-home text-emerald-700 text-6xl mb-4"></i>
            <h3 class="card-title">Smart Living</h3>
            <p class="card-subtitle">Redefined for the modern lifestyle</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Service Rating Banner (unchanged) -->
  <?php
  $avg_rating = 0;
  $total_reviews = 0;
  if (isset($conn)) {
      $avg_sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM service_reviews";
      $avg_result = mysqli_query($conn, $avg_sql);
      if ($avg_result) {
          $avg_data = mysqli_fetch_assoc($avg_result);
          $avg_rating = round($avg_data['avg_rating'] ?? 0, 1);
          $total_reviews = $avg_data['total_reviews'] ?? 0;
      }
  }
  ?>
  <div class="bg-emerald-100 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 max-w-4xl mx-auto mt-8 shadow-md">
    <div class="flex items-center gap-3 text-center sm:text-left">
        <div class="bg-emerald-200 text-emerald-800 rounded-full w-6 h-6 flex items-center justify-center">
            <i class="fas fa-check text-xs"></i>
        </div>
        <div class="flex flex-col">
            <span class="text-emerald-800 text-base">
                Did you know? Our services have been rated 
                <span class="text-emerald-900 font-semibold">&#9733; <?= $avg_rating ?>/5</span> 
                by verified customers
            </span>
            <span class="text-emerald-700 text-base">(based on <?= $total_reviews ?> reviews)</span>
        </div>
    </div>
    <a href="about_us.php#service-reviews" 
       class="group text-emerald-900 text-base hover:underline font-medium mt-2 sm:mt-0 flex items-center gap-1">
        See reviews 
        <span class="transition-transform duration-200 group-hover:translate-x-1">&rarr;</span>
    </a>
  </div>

  <!-- Features Section (unchanged) -->
  <section class="features-section">
    <div class="section-container">
      <div class="section-header">
        <h2 class="section-title">Why Choose LuxeHome</h2>
        <p class="section-description">We combine cutting-edge technology with elegant design to create smart home solutions that enhance your lifestyle.</p>
      </div>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-gem text-emerald-700 text-xl"></i></div>
          <h3 class="feature-title">Premium Quality</h3>
          <p class="feature-description">Handpicked luxury smart devices from world-leading brands known for their exceptional quality and reliability.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-shield-alt text-emerald-700 text-xl"></i></div>
          <h3 class="feature-title">Secure & Private</h3>
          <p class="feature-description">Military-grade encryption and privacy controls to protect your home and personal data from unauthorized access.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-tools text-emerald-700 text-xl"></i></div>
          <h3 class="feature-title">Expert Installation</h3>
          <p class="feature-description">White-glove setup service by certified technicians who ensure your smart home system works flawlessly from day one.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Shop by Room Section (updated with links) -->
  <section class="rooms-section">
    <div class="section-container">
      <div class="section-header">
        <h2 class="section-title">Shop by Room</h2>
        <p class="section-description">Explore our premium smart home solutions, organized by living space to match your needs perfectly.</p>
      </div>

      <div class="rooms-grid-primary">
        <a href="products.php?catSearch=Living%20Room" class="room-card living-room">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Living Room</h3>
            <p class="room-card-description">Transform your entertainment space</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </a>

        <a href="products.php?catSearch=Bedroom" class="room-card bedroom">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Bedroom</h3>
            <p class="room-card-description">Sleep in intelligent comfort</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </a>

        <a href="products.php?catSearch=Kitchen" class="room-card kitchen">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Kitchen</h3>
            <p class="room-card-description">Culinary innovation awaits</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </a>
      </div>

      <div class="rooms-grid-secondary">
        <a href="products.php?catSearch=Bathroom" class="room-card bathroom">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Bathroom</h3>
            <p class="room-card-description">Wellness redefined</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </a>

        <a href="products.php?catSearch=Outdoor" class="room-card outdoor">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Outdoor</h3>
            <p class="room-card-description">Garden intelligence</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- CTA Section (unchanged) -->
  <section class="cta-section">
    <div class="cta-container">
      <h2 class="cta-title">Ready to Transform Your Home?</h2>
      <p class="cta-description">Join thousands of homeowners who have embraced intelligent living with LuxeHome's premium solutions.</p>
      <div class="cta-buttons">
        <a href="products.php" class="btn-primary">
          Start Shopping
          <i class="fas fa-arrow-right ml-2"></i>
        </a>
        <a href="contact.php" class="btn-outline">
          Schedule Consultation
        </a>
      </div>
    </div>
  </section>

  <!-- Footer (social links removed) -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand-logo">
            <div class="brand-text">
              <h3 class="brand-name text-white">LuxeHome</h3>
              <p class="brand-tagline text-gray-400">Smart Living Elevated</p>
            </div>
          </div>
          <p class="brand-description">
            Experience the pinnacle of intelligent living with our curated collection of premium smart home technology designed for modern lifestyles.
          </p>
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
  <script src="js/script.js"></script>
  <script src="js/accessibility.js"></script>
</body>
</html>