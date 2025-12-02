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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LuxeHome | Premium Smart Living</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏠</text></svg>">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">

</head>
<body class="bg-gray-50">
  <!-- Header -->
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <a href="#" class="flex items-center space-x-3">
          <div class="logo-icon">
            <i class="fas fa-home text-white"></i>
          </div>
          <div>
            <h1 class="text-xl font-bold text-gray-900">LuxeHome</h1>
            <p class="text-xs text-gray-500">Smart Living Elevated</p>
          </div>
        </a>
        
        <!-- Navigation -->
        <nav class="hidden md:flex space-x-8">
          <a href="index.php" class="nav-link active">Home</a>
          <a href="products.php" class="nav-link">Shop</a>
          <a href="#" class="nav-link">Collections</a>
          <a href="about_us.php" class="nav-link">Inspiration</a>
          <a href="contact.php" class="nav-link">Contact</a>
        </nav>
        
        <!-- Actions -->
        <div class="flex items-center space-x-4">
          <button class="action-btn">
            <i class="fas fa-search"></i>
          </button>
          <button class="action-btn relative">
            <a href = "cart.php" class = "action-btn"> 
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-badge">3</span>
          </button>
          <?php if ($logged_in): ?>
            <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
          <?php else: ?>
              <a href="login.php" class="action-btn">
                  <i class="fas fa-user"></i>
              </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
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
          <a href="#" class="btn-primary">
            Explore Collection
            <i class="fas fa-arrow-right ml-2"></i>
          </a>
          <a href="#" class="btn-secondary">
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

  <!-- Features Section -->
  <section class="features-section">
    <div class="section-container">
      <div class="section-header">
        <h2 class="section-title">Why Choose LuxeHome</h2>
        <p class="section-description">We combine cutting-edge technology with elegant design to create smart home solutions that enhance your lifestyle.</p>
      </div>
      
      <div class="features-grid">
        <!-- Feature 1 -->
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-gem text-emerald-700 text-xl"></i>
          </div>
          <h3 class="feature-title">Premium Quality</h3>
          <p class="feature-description">Handpicked luxury smart devices from world-leading brands known for their exceptional quality and reliability.</p>
        </div>
        
        <!-- Feature 2 -->
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shield-alt text-emerald-700 text-xl"></i>
          </div>
          <h3 class="feature-title">Secure & Private</h3>
          <p class="feature-description">Military-grade encryption and privacy controls to protect your home and personal data from unauthorized access.</p>
        </div>
        
        <!-- Feature 3 -->
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-tools text-emerald-700 text-xl"></i>
          </div>
          <h3 class="feature-title">Expert Installation</h3>
          <p class="feature-description">White-glove setup service by certified technicians who ensure your smart home system works flawlessly from day one.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Shop by Room Section -->
  <section class="rooms-section">
    <div class="section-container">
      <div class="section-header">
        <h2 class="section-title">Shop by Room</h2>
        <p class="section-description">Explore our premium smart home solutions, organized by living space to match your needs perfectly.</p>
      </div>
      
      <div class="rooms-grid-primary">
        <!-- Living Room -->
        <div class="room-card living-room">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Living Room</h3>
            <p class="room-card-description">Transform your entertainment space</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </div>
        
        <!-- Bedroom -->
        <div class="room-card bedroom">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Bedroom</h3>
            <p class="room-card-description">Sleep in intelligent comfort</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </div>
        
        <!-- Kitchen -->
        <div class="room-card kitchen">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Kitchen</h3>
            <p class="room-card-description">Culinary innovation awaits</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </div>
      </div>
      
      <div class="rooms-grid-secondary">
        <!-- Bathroom -->
        <div class="room-card bathroom">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Bathroom</h3>
            <p class="room-card-description">Wellness redefined</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </div>
        
        <!-- Outdoor -->
        <div class="room-card outdoor">
          <div class="category-overlay"></div>
          <div class="room-card-content">
            <h3 class="room-card-title">Outdoor</h3>
            <p class="room-card-description">Garden intelligence</p>
            <div class="room-card-cta">
              <span>Explore</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="cta-container">
      <h2 class="cta-title">Ready to Transform Your Home?</h2>
      <p class="cta-description">Join thousands of homeowners who have embraced intelligent living with LuxeHome's premium solutions.</p>
      <div class="cta-buttons">
        <a href="#" class="btn-primary">
          Start Shopping
          <i class="fas fa-arrow-right ml-2"></i>
        </a>
        <a href="#" class="btn-outline">
          Schedule Consultation
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand-logo">
            <div class="logo-icon">
              <i class="fas fa-home text-white"></i>
            </div>
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
            <li><a href="#" class="footer-link">Shop</a></li>
            <li><a href="#" class="footer-link">Collections</a></li>
            <li><a href="#" class="footer-link">Inspiration</a></li>
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
</body>
</html>