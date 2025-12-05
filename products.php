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

$search = "";

$sql = "SELECT * FROM products";

// If user typed something in the search bar
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $sql .= " WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Products | LuxeHome</title>
  <meta name="description" content="Browse LuxeHome premium smart home products for living room, kitchen, bedroom and outdoors." />
  <link rel="icon" href="images/image.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/accessibility.css">
  <style>
    .logo-img{ 
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
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
          <a href="index.php" class="nav-link">Home</a>
          <a href="products.php" class="nav-link active">Shop</a>
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
              <span class="cart-badge">3</span>
            </a>
            <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
          <?php else: ?>
            <a href="cart.php" class="action-btn relative">
              <i class="fas fa-shopping-cart"></i>
              <span class="cart-badge">0</span>
            </a>
            <a href="login.php" class="action-btn">
              <i class="fas fa-user"></i>
            </a>
          <?php endif; ?>
        </div>
        
        <?php if ($logged_in): ?>
        <div class="flex items-center space-x-2">
          <form method="POST" action="php_functions/logout.php">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">Log out</button>
          </form>
          
          <?php 
          // Check if user is admin (you'll need to adjust this based on your user roles)
          // For now, showing admin dash for all logged-in users
          ?>
          <form method="POST" action="admin_dash.php">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Admin Dash</button>
          </form>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main id="main-content" class="max-w-7xl mx-auto px-4 py-8">
    <!-- Search Bar -->
    <section class="py-8 bg-gray-100">
      <div class="max-w-6xl mx-auto px-4">
        <form action="" method="GET">
          <div class="bg-white rounded-xl shadow-sm p-6 flex gap-4 items-center">
            
            <!-- Search -->
            <input 
              id="searchInput" 
              type="text" 
              name="search"
              placeholder="Search products, e.g. Smart Lamp, Thermostat"
              class="w-full p-3 border rounded-md focus:ring-emerald-500 focus:border-emerald-500"
            />

            <!-- NEED TO ADD CATEGORY!!!!! -->
            <select id="categoryFilter" name="category" class="p-3 border rounded-md">
              <option value="">All categories</option>
              <option value="Living Room">Living Room</option>
              <option value="Kitchen">Kitchen</option>
              <option value="Bedroom">Bedroom</option>
              <option value="Bathroom">Bathroom</option>
              <option value="Outdoor">Outdoor</option>
            </select>

            <button 
              type="submit" 
              class="bg-emerald-600 text-white px-4 py-3 rounded-md hover:bg-emerald-700"
            >
              Search
            </button>

          </div>
        </form>
      </div>
    </section>

    <!-- Products Grid with php -->
    <div class="py-8">
      <h2 class="text-2xl font-semibold mb-6">Featured LuxeHome Products</h2>
      <div id="productsGrid" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <article class="bg-white rounded-xl shadow p-4 hover:shadow-md transition">
            <a href="productDetails.php?id=<?php echo $row['product_id']; ?>" class="block">
                <img src="product_image/<?php echo $row['img']; ?>" 
                     alt="<?php echo htmlspecialchars($row['name']); ?>" 
                     class="w-full h-48 object-cover rounded-md mb-3">

                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($row['name']); ?></h3>

                <p class="text-sm text-gray-500 mb-2">
                    <?php echo substr($row['description'], 0, 60) . "..."; ?>
                </p>

                <div class="flex items-center justify-between">
                    <span class="text-emerald-600 font-semibold">&pound;<?php echo number_format($row['price'], 2); ?></span>
                </div>
            </a>
        </article>
        <?php } ?>

      </div>
    </div>
  </main>

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
  
  <script src="js/script.js"></script>
  <script src="js/accessibility.js"></script>
</body>
</html>