<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
$logged_in = false;
$username = "";
$user_id = 0;

if (isset($_SESSION['username'])) {
    $logged_in = true;
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id']; // optional, if you need it for review deletion
}

require_once 'php_functions/dbh.php';

// Fetch service reviews
$service_review_sql = "
    SELECT r.review_id, r.user_id, r.review, r.rating, r.review_date, u.username
    FROM service_reviews r
    JOIN users u ON r.user_id = u.user_id
    ORDER BY r.review_date DESC
";
$service_review_result = mysqli_query($conn, $service_review_sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="description" content="LuxeHome">
        <meta name="author" content="Aminah Burctoolla">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>
About Us | LuxeHome
</title>
  <!-- SEO Meta -->
  <meta name="description"
    content="Learn about LuxeHome’s journey, vision, and values. Founded in Birmingham, we elevate modern living with luxury smart furniture, innovative IoT technology, and secure home solutions." />
  <meta name="keywords"
    content="LuxeHome, about LuxeHome, smart home furniture, luxury smart living, home automation UK, IoT home technology, smart furniture innovation" />
  <meta name="author" content="LuxeHome" />

        <!-- Favicon -->
        <link rel="icon" href="images/image.png">

        <!-- TailwindCSS & Font Awesome -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- External CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/accessibility.css">

        <!-- Custom Styles -->
        <link rel="stylesheet" href="css/about_us-style.css">
        
        <style>
            .logo-img {
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
                <!-- Logo -->
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
                    <a href="products.php" class="nav-link">Shop</a>
                    <a href="about_us.php" class="nav-link active">About us</a>
                    <a href="contact.php" class="nav-link">Contact</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <button class="action-btn hover:text-emerald-600">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    <?php if ($logged_in): ?>
                        <a href="cart.php" class="action-btn relative hover:text-emerald-600">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">3</span>
                        </a>
                        <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
                    <?php else: ?>
                        <a href="cart.php" class="action-btn relative hover:text-emerald-600">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">0</span>
                        </a>
                        <a href="login.php" class="action-btn hover:text-emerald-600">
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

    <!-- Main Content -->
    <main id="main-content" class="overflow-x-hidden">
  <!-- main content area; hides horizontal overflow to prevent layout break and keep the page clean -->

    <!-- About Us Hero Section -->
    <section class="relative bg-[#0a0f23] text-white overflow-hidden">
      <!-- positioned relative for overlays, dark blue background, white text for readability, and hides any overflowing content -->

      <!-- Faint white overlay -->
      <div class="absolute inset-0 bg-white/5 pointer-events-none z-0"></div>
      <!-- faint white overlay covering the entire hero section -->

      <div class="max-w-7xl mx-auto px-0 flex flex-col md:flex-row md:items-stretch items-center w-full">
        <!-- centered container with max width, flex layout column on mobile and row on desktop, and vertical alignment -->

        <!-- Left Side -->
        <div class="relative w-full md:w-1/2 space-y-6 z-10 py-8 md:py-32 pl-6 md:pl-0 → pl-6 md:pl-6">
          <!-- left side container with full width on mobile and half width on desktop, vertical spacing between items, positioned above background, and responsive padding top and bottom with slight left padding -->

          <!-- Premium Badge -->
          <div class="inline-flex items-center gap-2
                  bg-emerald-600/20
                  border border-emerald-400/30
                  backdrop-blur-md
                  text-emerald-400
                  px-3 py-1.5
                  text-sm
                  rounded-full
                  font-semibold
                  shadow-lg">
            <!-- rounded badge with icon and text, uses green tones, small padding, shadow and blur effect, items arranged in a row -->
            <i class="fas fa-star text-emerald-400 text-sm"></i>
            <span> Prestige You Can Trust</span>
            <!-- star icon with green color and small size, including descriptive text inside the badge -->
          </div>

          <!-- Heading -->
          <h1 class="text-4xl sm:text-5xl font-bold leading-tight mt-4">
            Book a Free Installation <br>
            <span class="text-emerald-500">With Every Purchase</span>
          </h1> <!-- main heading with bold text, large size, and green text on the second line -->

          <!-- Paragraph -->
          <p class="text-lg text-gray-300 max-w-xl">
            Enjoy a free installation service with every LuxeHome purchase,
            backed by our dedicated customer support to make your smart home
            setup effortless and stress-free.
          </p> <!-- paragraph under heading with light grey text, larger font, and limited width for readability -->

          <!-- Buttons -->
          <div class="flex gap-4 mt-6">
            <a href="products.php"
              class="px-6 py-3 bg-emerald-600 rounded-md font-semibold hover:bg-emerald-700 transition">
              Explore Collection →
            </a>
            <!-- flex container for buttons with space between and top margin, button has green background, rounded corners, semi-bold text, padding, and smooth hover effect -->
            <a href="contact.php"
              class="px-6 py-3 border border-white rounded-md font-semibold hover:bg-white hover:text-[#0a0f23] transition">
              Contact Our Team
            </a>
            <!-- button with white border, rounded corners, semi-bold text, padding, changes to white background and dark text on hover with smooth transition -->
          </div>

        </div>

        <!-- Right Side -->
        <div class="relative w-full md:w-1/2 flex justify-center md:justify-end mt-4 md:mt-0">
          <!-- right side container with full width on mobile and half width on desktop, centers content horizontally, adjusts alignment on desktop, and adds responsive top margin -->
          <div class="w-full 
          md:w-full 
          md:max-w-none 
          md:h-full 
          md:aspect-square
          overflow-hidden 
          shadow-2xl
          -mx-6 
          md:mx-0">
            <!-- right side image container with full width, full height on desktop, square aspect ratio, hides overflow, adds shadow, and responsive horizontal margin -->

            <!--Photo by Anita Chong on Unsplash-->
            <img src="images/herosection/livingroom.jpg" alt="Luxury smart home interior"
              class="w-full h-full object-cover hover:scale-105 transition duration-700">
            <!-- image fills the container, maintains aspect ratio, covers entire area, and slightly scales up on hover with smooth transition -->
            </div>
        </div>

      </div>
    </section>


    <!-- Meet the Team -->
    <!-- Team photos from Unsplash: https://unsplash.com -->
    <!-- Photo by Fotos on Unsplash-->
    <section class="py-16 bg-white"> <!-- section with vertical padding py-16 for spacing, white background for clean, neutral look -->

      <!-- Section Heading -->
      <h3 class="text-3xl font-bold text-center mb-8 text-gray-900">Meet the Team</h3>
     <!-- subheading, text-3xl for large font size, bold and dark grey text for emphasis, centered horizontally for focus, mb-8 for bottom spacing -->

      <!-- Section Subheading -->
      <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        Our dedicated team creates seamless and intelligent home experiences.
      </p> <!-- paragraph, text-gray-600 for subtle grey text, centered horizontally with text-center and mx-auto, max-w-2xl for comfortable line length, mb-12 for bottom spacing -->

      <!-- Team Grid -->
      <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <!-- grid container, max-w-7xl for max width, centered with mx-auto, horizontal padding px-4 for spacing, grid columns adjust for different screens (1 on mobile, 2 on small, 3 on medium, 4 on large), gap-8 for spacing between cards so they don’t touch -->

        <!-- Team Member 1 - Asjad -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
         <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
          <img src="images/team/asjad.jpg" alt="Asjad" class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Asjad</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">Lead Developer</p>
          <!-- team member role, text-sm for smaller text size, font-medium for moderate emphasis, text-gray-600 for subtle grey color -->
        </div>

        <!-- Team Member 2 - Fahad Alajmi -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
          <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
          <img src="images/team/fahad.jpg" alt="Fahad Alajmi" class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
         <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Fahad Alajmi</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">Web Architect</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

        <!-- Team Member 3 - Aminah Burctoolla -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
         <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
          <img src="images/team/aminah.jpg" alt="Aminah Burctoolla"
            class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Aminah Burctoolla</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">UI Designer</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

        <!-- Team Member 4 - Ubaid Ullah Faisal -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
          <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
           <img src="images/team/ubaid.jpg" alt="Ubaid Ullah Faisal"
            class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Ubaid Ullah Faisal</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">Front-End Engineer</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

        <!-- Team Member 5 - Haleema Jamil -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
          <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
           <img src="images/team/haleema.jpg" alt="Haleema Jamil" class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Haleema Jamil</h4>
         <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">Product Manager</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

        <!-- Team Member 6 - Ameera Mohamed -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
          <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
           <img src="images/team/ameera.jpg" alt="Ameera Mohamed" class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Ameera Mohamed</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">UI Specialist</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

        <!-- Team Member 7 - Mohammed Riazul -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
          <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
           <img src="images/team/riazul.jpg" alt="Mohammed Riazul"
            class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Mohammed Riazul</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">Security Lead</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

        <!-- Team Member 8 - Jashandeep Singh -->
        <div
          class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transform hover:-translate-y-2 transition-transform duration-300">
          <!-- card container, bg-white for clean background, rounded-xl for smooth corners, shadow-md for subtle depth, text-center for horizontal alignment, hover:shadow-xl and hover:-translate-y-2 to lift card on hover, transition-transform and duration-300 for smooth animation -->
           <img src="images/team/jashandeep.jpg" alt="Jashandeep Singh"
            class="w-28 h-28 mx-auto rounded-lg object-cover mb-4">
          <!-- team member image, src points to file, alt text for accessibility, w-28 h-28 for square size, mx-auto to center, rounded-lg for smooth corners, object-cover ensures image fills square, mb-4 for spacing below -->
           <h4 class="text-lg font-semibold text-emerald-600">Jashandeep Singh</h4>
          <!-- team member name, text-lg for readable size, font-semibold for emphasis, text-emerald-600 for green brand color -->
          <p class="text-sm text-gray-600 font-medium">Full-Stack Developer</p>
          <!-- team member role, text-sm for smaller size to show hierarchy, font-medium for moderate emphasis, text-gray-600 for subtle, readable color -->
        </div>

      </div>
    </section>

    <!--Our Inspiring History-->
    <!--Photos from Unsplash: https://unsplash.com -->
    <!-- Old headquarters photo by Agung Vitrama on Unsplash-->
    <!-- New headquarters photo by Nils Huenerfuerst on Unsplash-->
    <!-- Smart furniture photo by Jakub Żerdzicki on Unsplash-->

    <!-- Our Journey Through Time -->
    <section class="py-16 bg-white"> <!-- section with vertical padding py-16 for spacing between sections, bg-white for clean, neutral background -->

      <!-- Section Heading -->
      <h3 class="text-3xl font-bold text-center mb-12 text-gray-900">
        <!-- section heading, text-3xl for readable size, font-bold for emphasis, text-center to focus attention, mb-12 for spacing below, text-gray-900 for good contrast, clear layout helps accessibility and makes content easy for users to scan -->
          Our Journey Through Time
      </h3>

      <!-- Timeline Container -->
      <div class="relative max-w-6xl mx-auto px-4">
        <!-- timeline container, relative positioning for layered elements, max-w-6xl limits content width for readability, mx-auto centers content horizontally for balanced layout, px-4 adds horizontal padding, clear structure helps accessibility and makes timeline easy for users to follow -->

        <!-- Vertical line for timeline -->
        <div class="absolute left-1/2 transform -translate-x-1/2 top-0 bottom-0 border-l-[2px] border-emerald-500"
          style="height: calc(100% - 4rem); top: 2rem;"></div>
        <!-- vertical line for timeline, absolute positioning and left-1/2 with -translate-x-1/2 to center horizontally, stretches from top to bottom (height calc 100% - 4rem) for full timeline coverage, border-l-2 and border-emerald-500 for clear visual guide, helps users easily follow timeline events -->

        <!-- Timeline Events -->
        <div class="space-y-12"> <!-- timeline events container, space-y-12 adds vertical spacing between cards for clear separation, improves readability and makes it easier for users to follow the timeline -->

          <!-- Timeline Event 1 -->
          <div
            class="timeline-card flex flex-col md:flex-row items-center gap-8 relative bg-white rounded-lg shadow-md p-4 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
            <!-- timeline event card, flex layout stacked vertically on mobile and horizontally on desktop, items centered, gap-8 between elements, white background, rounded corners, shadow for depth, hover lifts card slightly and adds bigger shadow -->

            <!-- Dot -->
            <span
              class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-5 h-5 bg-emerald-400 rounded-full z-20 ring-2 ring-white"></span>
            <!-- dot in timeline, absolute positioning centers it on parent line for clarity, w-5 h-5 for visible size, green background for contrast, rounded-full for clear circle shape, z-20 to stay on top, ring-2 and white outline for visibility, helps users easily see timeline points, clear placement improves UX and makes the timeline easy to follow on all screen sizes -->

            <!-- Image -->
            <div class="flex-shrink-0 md:w-1/2">
              <!-- image container, flex-shrink-0 prevents image from shrinking, md:w-1/2 sets width to half on medium screens, keeps image clear and readable, responsive layout improves UX and convenience for users on different screen sizes -->
               <img src="images/timeline/old.jpg" alt="Old Headquarters"
                class="w-full h-48 object-cover rounded-md shadow-md mx-auto transition">
            </div>
            <!-- image, src points to file, alt text for accessibility, w-full h-48 for size, object-cover to fill container without distortion, rounded-md for smooth corners, shadow-md for depth, centered with mx-auto, transition for smooth hover effect, clear and readable image improves UX and makes content easy for users to view -->

            <!-- Text -->
            <div class="text-center md:text-left md:flex-1">
              <!-- text container, text-center on small screens and md:text-left on larger screens for better readability, md:flex-1 makes div take available space on larger screens, responsive layout improves UX and makes content easier for users to follow -->

              <!-- Year Label -->
              <div class="text-sm font-semibold text-emerald-600 mb-2">
                <!-- year label, text-sm for small readable size, font-semibold for emphasis, green text for visibility, mb-2 for spacing below, clear and visible label helps users quickly understand timeline events -->
                 2015
              </div>

              <h4 class="text-xl font-semibold text-gray-900">
                Humble Beginnings
              </h4> <!-- event title, text-xl for large readable size, font-semibold for emphasis, text-gray-900 for good contrast, clear and visible heading helps users quickly understand timeline events -->

              <p class="text-gray-600 mt-2">
                Started small in Birmingham, growing with care and trust.
              </p> <!-- event description, text-gray-600 for readable contrast, mt-2 for spacing above, short clear text helps users easily understand timeline story and improves readability -->

            </div>
          </div>

          <!-- Timeline Event 2 -->
          <div
            class="timeline-card flex flex-col md:flex-row-reverse items-center gap-8 relative bg-white rounded-lg shadow-md p-4 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
            <!-- timeline event card, flex layout stacked vertically on mobile and horizontally on desktop, items centered, gap-8 between elements, white background, rounded corners, shadow for depth, hover lifts card slightly and adds bigger shadow -->

            <!-- Dot -->
            <span
              class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-5 h-5 bg-emerald-400 rounded-full z-20 ring-2 ring-white"></span>
                <!-- dot in timeline, absolute positioning centers it on parent line for clarity, w-5 h-5 for visible size, green background for contrast, rounded-full for clear circle shape, z-20 to stay on top, ring-2 and white outline for visibility, helps users easily see timeline points, clear placement improves UX and makes the timeline easy to follow on all screen sizes -->

            <!-- Image -->
            <div class="flex-shrink-0 md:w-1/2">
              <!-- image container, flex-shrink-0 prevents image from shrinking, md:w-1/2 sets width to half on medium screens, keeps image clear and readable, responsive layout improves UX and convenience for users on different screen sizes -->
               <img src="images/timeline/new.jpg" alt="New Headquarters"
                class="w-full h-48 object-cover rounded-md shadow-md mx-auto transition">
            </div>
            <!-- image, src points to file, alt text for accessibility, w-full h-48 for size, object-cover to fill container without distortion, rounded-md for smooth corners, shadow-md for depth, centered with mx-auto, transition for smooth hover effect, clear and readable image improves UX and makes content easy for users to view -->

            <!-- Text -->
            <div class="text-center md:text-right md:flex-1">
              <!-- text container, text-center on small screens and md:text-right on larger screens for better readability, md:flex-1 makes div take available space on larger screens, responsive layout improves UX and makes content easier for users to follow -->
               
              <!-- Year Label -->
              <div class="text-sm font-semibold text-emerald-600 mb-2">
                <!-- year label, text-sm for small readable size, font-semibold for emphasis, green text for visibility, mb-2 for spacing below, clear and visible label helps users quickly understand timeline events -->
                2025
              </div>

              <h4 class="text-xl font-semibold text-gray-900">
                New Horizons
              </h4> <!-- event title, text-xl for large readable size, font-semibold for emphasis, text-gray-900 for good contrast, clear and visible heading helps users quickly understand timeline events -->

              <p class="text-gray-600 mt-2">
                Moved to a central hub, expanding reach and innovation.
              </p> <!-- event description, text-gray-600 for readable contrast, mt-2 for spacing above, short clear text helps users easily understand timeline story and improves readability -->

            </div>
          </div>

          <!-- Timeline Event 3 -->
          <div
            class="timeline-card flex flex-col md:flex-row items-center gap-8 relative bg-white rounded-lg shadow-md p-4 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
            <!-- timeline event card, flex layout stacked vertically on mobile and horizontally on desktop, items centered, gap-8 between elements, white background, rounded corners, shadow for depth, hover lifts card slightly and adds bigger shadow -->

            <!-- Dot -->
            <span
              class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-5 h-5 bg-emerald-400 rounded-full z-20 ring-2 ring-white"></span>
                <!-- dot in timeline, absolute positioning centers it on parent line for clarity, w-5 h-5 for visible size, green background for contrast, rounded-full for clear circle shape, z-20 to stay on top, ring-2 and white outline for visibility, helps users easily see timeline points, clear placement improves UX and makes the timeline easy to follow on all screen sizes -->

            <!-- Image -->
            <div class="flex-shrink-0 md:w-1/2">
              <!-- image container, flex-shrink-0 prevents image from shrinking, md:w-1/2 sets width to half on medium screens, keeps image clear and readable, responsive layout improves UX and convenience for users on different screen sizes -->
               <img src="images/timeline/SmartFurniture.jpg" alt="Smart Furniture"
                class="w-full h-48 object-cover rounded-md shadow-md mx-auto transition">
            </div>
            <!-- image, src points to file, alt text for accessibility, w-full h-48 for size, object-cover to fill container without distortion, rounded-md for smooth corners, shadow-md for depth, centered with mx-auto, transition for smooth hover effect, clear and readable image improves UX and makes content easy for users to view -->

            <!-- Text -->
            <div class="text-center md:text-left md:flex-1">
              <!-- text container, text-center on small screens and md:text-left on larger screens for better readability, md:flex-1 makes div take available space on larger screens, responsive layout improves UX and makes content easier for users to follow -->

              <!-- Year Label -->
              <div class="text-sm font-semibold text-emerald-600 mb-2">
                2025+
              </div> <!-- year label, text-sm for small readable size, font-semibold for emphasis, green text for visibility, mb-2 for spacing below, clear and visible label helps users quickly understand timeline events -->

              <h4 class="text-xl font-semibold text-gray-900">
                Smart Living
              </h4> <!-- event title, text-xl for large readable size, font-semibold for emphasis, text-gray-900 for good contrast, clear and visible heading helps users quickly understand timeline events -->

              <p class="text-gray-600 mt-2">
                Innovating homes with smart, elegant furniture solutions.
              </p> <!-- event description, text-gray-600 for readable contrast, mt-2 for spacing above, short clear text helps users easily understand timeline story and improves readability -->

            </div>
          </div>

        </div>
      </div>
    </section>


    <!-- What Drives LuxeHome Section -->
    <section class="pt-20 pb-10 bg-white relative overflow-hidden">
      <!-- section container, pt-20 pb-10 for top and bottom spacing, bg-white for clean background, relative positioning for layered elements, overflow-hidden to prevent content spilling, spacing and clear layout improve readability and make content easy for users to follow -->
       <div class="max-w-7xl mx-auto px-4 md:px-20 relative z-10">
        <!-- content container, max-w-7xl limits width for readability, mx-auto centers horizontally, px-4 and md:px-20 add horizontal padding on different screens, relative z-10 positions above background, clear layout and spacing improve UX and make content easy for users to view -->

        <h3 class="text-3xl font-bold text-center mb-12 text-gray-900">What Drives LuxeHome</h3>
        <!-- section heading, text-3xl for readable large size, font-bold for emphasis, text-center to focus user attention, mb-12 for spacing below, text-gray-900 for good contrast, clear heading improves UX and makes content easy for users to scan -->

        <!-- Cards Container -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 relative z-10">
          <!-- cards container, grid layout with 1 column on small screens and 3 on medium+ screens, gap-10 adds spacing between cards, relative z-10 positions above background, responsive layout and clear spacing improve UX and make content easy for users to read -->

          <!-- Vision Card -->
          <div
            class="relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-300 ease-out overflow-hidden">
            <!-- card container, relative positioning for layered elements, white background for clean look, p-8 adds spacing inside, rounded-2xl for smooth corners, shadow-xl for depth, hover lifts card and adds bigger shadow for interactivity, transition makes hover smooth, overflow-hidden prevents content spilling, clear and interactive design improves UX and makes content easy to view -->
             <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-200 to-emerald-400"></div>
            <!-- horizontal bar, absolute positioned at top-left of parent, w-full for full width, h-2 for thin height, green gradient from left to right for visual interest, helps users quickly see section progress and improves UX -->

            <div class="bg-emerald-100 w-16 h-16 flex items-center justify-center rounded-lg mb-6 relative z-10">
              <!-- icon container, light green background for visibility, w-16 h-16 for square size, flex to center content vertically and horizontally, rounded-lg for smooth corners, mb-6 for spacing below, relative z-10 positions above background, clear and balanced layout improves UX and makes content easy to view -->
               <svg class="w-8 h-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
              </svg> <!-- icon, w-8 h-8 sets size, green color for visibility, no fill for clean look, stroke-width 1.5 for clear lines, rounded caps and joins for smooth edges, visible and readable icon improves UX and makes content easy to understand -->
             </div>

            <h4 class="text-2xl font-semibold text-gray-900 mb-4 relative z-10">Vision</h4>
            <!-- section title, text-2xl for readable large size, font-semibold for emphasis, text-gray-900 for good contrast, mb-4 for spacing below, relative z-10 to layer above background, clear heading improves UX and helps users quickly understand content -->
             <p class="text-gray-700 mb-2 relative z-10">
              We aspire to be a global leader in smart home living, delivering intelligent and luxurious solutions for
              every home.
            </p> <!-- paragraph text, text-gray-700 for readable contrast, mb-2 for spacing below, relative z-10 to layer above background, clear and concise text improves readability, helps users easily understand the vision statement -->
            <p class="text-gray-700 relative z-10">
              Our team innovates daily, blending creativity and research to offer safe, elegant, and high-tech smart
              home experiences.
            </p> <!-- paragraph text, text-gray-700 for readable contrast, relative z-10 to layer above background, clear and concise text improves readability and helps users easily understand the team’s approach and smart home solutions -->
          </div>

          <!-- Scope Card -->
          <div
            class="relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-300 ease-out overflow-hidden">
            <!-- card container, relative positioning for layered elements, white background for clean look, p-8 adds spacing inside, rounded-2xl for smooth corners, shadow-xl for depth, hover lifts card and adds bigger shadow for interactivity, transition makes hover smooth, overflow-hidden prevents content spilling, clear and interactive design improves UX and makes content easy to view -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <!-- horizontal bar, absolute positioned at top-left of parent, w-full for full width, h-2 for thin height, green gradient from left to right for visual interest, helps users quickly see section progress and improves UX -->

            <div class="bg-emerald-100 w-16 h-16 flex items-center justify-center rounded-lg mb-6 relative z-10">
              <!-- icon container, light green background for visibility, w-16 h-16 for square size, flex to center content vertically and horizontally, rounded-lg for smooth corners, mb-6 for spacing below, relative z-10 positions above background, clear and balanced layout improves UX and makes content easy to view -->
              <svg class="w-8 h-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
              </svg> <!-- icon, w-8 h-8 sets size, green color for visibility, no fill for clean look, stroke-width 1.5 for clear lines, rounded caps and joins for smooth edges, visible and readable icon improves UX and makes content easy to understand -->
             </div>

            <h4 class="text-2xl font-semibold text-gray-900 mb-4 relative z-10">Scope</h4>
            <!-- section title, text-2xl for readable large size, font-semibold for emphasis, text-gray-900 for good contrast, mb-4 for spacing below, relative z-10 to layer above background, clear heading improves UX and helps users quickly understand content -->
             <p class="text-gray-700 mb-2 relative z-10">
              LuxeHome designs and produces smart furniture and appliances across the UK, with plans for international
              expansion.
            </p> <!-- paragraph text, text-gray-700 for readable contrast, mb-2 for spacing below, relative z-10 to layer above background, clear and concise text improves readability, helps users easily understand the scope statement -->
            <p class="text-gray-700 relative z-10">
              From living rooms to outdoor spaces, we embed advanced technology into furniture, creating intelligent,
              secure, and elegant environments.
            </p> <!-- paragraph text, text-gray-700 for readable contrast, relative z-10 to layer above background, clear and concise text improves readability and helps users easily understand the team’s approach and smart home solutions -->
          </div>

          <!-- Values Card -->
          <div
            class="relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-300 ease-out overflow-hidden">
            <!-- card container, relative positioning for layered elements, white background for clean look, p-8 adds spacing inside, rounded-2xl for smooth corners, shadow-xl for depth, hover lifts card and adds bigger shadow for interactivity, transition makes hover smooth, overflow-hidden prevents content spilling, clear and interactive design improves UX and makes content easy to view -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-600 to-emerald-800"></div>
            <!-- horizontal bar, absolute positioned at top-left of parent, w-full for full width, h-2 for thin height, green gradient from left to right for visual interest, helps users quickly see section progress and improves UX -->

            <div class="bg-emerald-100 w-16 h-16 flex items-center justify-center rounded-lg mb-6 relative z-10">
              <!-- icon container, light green background for visibility, w-16 h-16 for square size, flex to center content vertically and horizontally, rounded-lg for smooth corners, mb-6 for spacing below, relative z-10 positions above background, clear and balanced layout improves UX and makes content easy to view -->
              <svg class="w-8 h-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
              </svg> <!-- icon, w-8 h-8 sets size, green color for visibility, no fill for clean look, stroke-width 1.5 for clear lines, rounded caps and joins for smooth edges, visible and readable icon improves UX and makes content easy to understand -->
             </div>

            <h4 class="text-2xl font-semibold text-gray-900 mb-4 relative z-10">Values</h4>
            <!-- section title, text-2xl for readable large size, font-semibold for emphasis, text-gray-900 for good contrast, mb-4 for spacing below, relative z-10 to layer above background, clear heading improves UX and helps users quickly understand content -->
             <p class="text-gray-700 mb-2 relative z-10">
              Innovation, reliability, and customer satisfaction guide every decision we make.
            </p> <!-- paragraph text, text-gray-700 for readable contrast, mb-2 for spacing below, relative z-10 to layer above background, clear and concise text improves readability, helps users easily understand the values statement -->
            <p class="text-gray-700 relative z-10">
              Our dedicated team ensures LuxeHome delivers intelligent, elegant, and trustworthy smart home solutions to
              all our clients.
            </p> <!-- paragraph text, text-gray-700 for readable contrast, relative z-10 to layer above background, clear and concise text improves readability and helps users easily understand the team’s approach and smart home solutions -->
          </div>

        </div>
      </div>
    </section>


    <!-- Customer Promises Section -->
    <section class="pt-24 pb-12 bg-white"> <!-- Customer Promises section, pt-24 pb-12 for top and bottom spacing, bg-white for clean background, clear spacing and layout improve readability and make content easy for users to follow -->
      <div class="max-w-7xl mx-auto px-4 md:px-20">
        <!-- content container, max-w-7xl limits width for readability, mx-auto centers horizontally, px-4 and md:px-20 add horizontal padding on small and larger screens, responsive spacing improves UX and makes content easy for users to read -->

        <!-- Section Heading -->
        <h3 class="text-3xl font-bold text-center mb-12 text-gray-900">
          Our Customer Promise
        </h3> <!-- section heading, text-3xl for large readable size, font-bold for emphasis, text-center to focus user attention, mb-12 for spacing below, text-gray-900 for good contrast, clear heading improves UX and makes content easy for users to scan -->

        <!-- Promises Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- cards container, grid layout with 1 column on small screens, 2 on medium, 3 on large screens, gap-8 adds spacing between cards, responsive layout and spacing improve UX and make content easy for users to view -->

          <!-- Promise 1: 24/7 Data Protection -->
          <div
            class="bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-xl">
            <!-- card container, white background for clean look, rounded-xl for smooth corners, p-6 for padding inside, text-center to align content, hover lifts card and adds bigger shadow for interactive feel, transition makes hover smooth, clear and interactive design improves UX and makes content easy to view -->
             <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-emerald-500 mb-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <!-- icon, w-16 h-16 sets size, mx-auto centers horizontally, green color for visibility, mb-4 adds spacing below, no fill for clean look, stroke-width 1.5 for clear lines, visible and readable icon improves UX and helps users understand content quickly -->
             <h4 class="text-lg font-semibold text-gray-900 mb-2">24/7 Data Protection</h4>
            <!-- feature title, text-lg for readable size, font-semibold for emphasis, text-gray-900 for good contrast, mb-2 for spacing below, clear heading improves UX and helps users quickly understand key feature -->
             <p class="text-gray-600 text-sm">
              LuxeHome ensures your data is protected 24/7 through secure networks, monitored servers, and strict
              privacy practices.
            </p> <!-- paragraph text, text-gray-600 for readable contrast, text-sm for smaller descriptive text, explains feature clearly, helps users quickly understand 24/7 data protection, improves readability and UX -->
          </div>

          <!-- Promise 2: Customer Privacy -->
          <div
            class="bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-xl">
            <!-- card container, white background for clean look, rounded-xl for smooth corners, p-6 for padding inside, text-center to align content, hover lifts card and adds bigger shadow for interactive feel, transition makes hover smooth, clear and interactive design improves UX and makes content easy to view -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-emerald-500 mb-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>
            <!-- icon, w-16 h-16 sets size, mx-auto centers horizontally, green color for visibility, mb-4 adds spacing below, no fill for clean look, stroke-width 1.5 for clear lines, visible and readable icon improves UX and helps users understand content quickly -->
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Customer Privacy</h4>
            <!-- feature title, text-lg for readable size, font-semibold for emphasis, text-gray-900 for good contrast, mb-2 for spacing below, clear heading improves UX and helps users quickly understand key feature -->
            <p class="text-gray-600 text-sm">
              You control your personal information—update, modify, or remove details at any time for full privacy.
            </p> <!-- paragraph text, text-gray-600 for readable contrast, text-sm for smaller descriptive text, explains user control over personal info, clear wording improves readability and helps users understand privacy options, enhancing UX -->
          </div>

          <!-- Promise 3: Account Security -->
          <div
            class="bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-xl">
            <!-- card container, white background for clean look, rounded-xl for smooth corners, p-6 for padding inside, text-center to align content, hover lifts card and adds bigger shadow for interactive feel, transition makes hover smooth, clear and interactive design improves UX and makes content easy to view -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-emerald-500 mb-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
            <!-- icon, w-16 h-16 sets size, mx-auto centers horizontally, green color for visibility, mb-4 adds spacing below, no fill for clean look, stroke-width 1.5 for clear lines, visible and readable icon improves UX and helps users understand content quickly -->
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Account Security</h4>
            <!-- feature title, text-lg for readable size, font-semibold for emphasis, text-gray-900 for good contrast, mb-2 for spacing below, clear heading improves UX and helps users quickly understand key feature -->
            <p class="text-gray-600 text-sm">
              Change your passwords whenever you like to maintain maximum account security.
            </p> <!-- paragraph text, text-gray-600 for readable contrast, text-sm for smaller descriptive text, explains password control for security, clear wording improves readability and helps users understand how to keep their account safe, enhancing UX -->
          </div>

          <!-- Promise 4: Reliable Service -->
          <div
            class="bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-xl">
            <!-- card container, white background for clean look, rounded-xl for smooth corners, p-6 for padding inside, text-center to align content, hover lifts card and adds bigger shadow for interactive feel, transition makes hover smooth, clear and interactive design improves UX and makes content easy to view -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-emerald-500 mb-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.121a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <!-- icon, w-16 h-16 sets size, mx-auto centers horizontally, green color for visibility, mb-4 adds spacing below, no fill for clean look, stroke-width 1.5 for clear lines, visible and readable icon improves UX and helps users understand content quickly -->
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Reliable Service</h4>
            <!-- feature title, text-lg for readable size, font-semibold for emphasis, text-gray-900 for good contrast, mb-2 for spacing below, clear heading improves UX and helps users quickly understand key feature -->
            <p class="text-gray-600 text-sm">
              Our reliable databases, built by our skilled team, guarantee uninterrupted access and smooth e-commerce
              operations.
            </p> <!-- paragraph text, text-gray-600 for readable contrast, text-sm for smaller descriptive text, explains database reliability for smooth e-commerce, clear wording improves readability and helps users understand service reliability, enhancing UX -->
          </div>

          <!-- Promise 5: Product Guarantee -->
          <div
            class="bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-xl">
            <!-- card container, white background for clean look, rounded-xl for smooth corners, p-6 for padding inside, text-center to align content, hover lifts card and adds bigger shadow for interactive feel, transition makes hover smooth, clear and interactive design improves UX and makes content easy to view -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-emerald-500 mb-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
            </svg>
            <!-- icon, w-16 h-16 sets size, mx-auto centers horizontally, green color for visibility, mb-4 adds spacing below, no fill for clean look, stroke-width 1.5 for clear lines, visible and readable icon improves UX and helps users understand content quickly -->
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Product Guarantee</h4>
            <!-- feature title, text-lg for readable size, font-semibold for emphasis, text-gray-900 for good contrast, mb-2 for spacing below, clear heading improves UX and helps users quickly understand key feature -->
            <p class="text-gray-600 text-sm">
              If a product doesn’t meet your expectations, our easy return policy ensures a smooth resolution.
            </p> <!-- paragraph text, text-gray-600 for readable contrast, text-sm for smaller descriptive text, explains easy return policy, clear wording improves readability and helps users understand hassle-free returns, enhancing UX and user confidence -->
          </div>

          <!-- Promise 6: Feedback & Improvement -->
          <div
            class="bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-xl">
            <!-- card container, white background for clean look, rounded-xl for smooth corners, p-6 for padding inside, text-center to align content, hover lifts card and adds bigger shadow for interactive feel, transition makes hover smooth, clear and interactive design improves UX and makes content easy to view -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-emerald-500 mb-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
            </svg>
            <!-- icon, w-16 h-16 sets size, mx-auto centers horizontally, green color for visibility, mb-4 adds spacing below, no fill for clean look, stroke-width 1.5 for clear lines, visible and readable icon improves UX and helps users understand content quickly -->
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Feedback & Improvement</h4>
            <!-- feature title, text-lg for readable size, font-semibold for emphasis, text-gray-900 for good contrast, mb-2 for spacing below, clear heading improves UX and helps users quickly understand key feature -->
            <p class="text-gray-600 text-sm">
              We value your feedback. Reviews help us improve and provide even better service for your next purchase.
            </p> <!-- paragraph text, text-gray-600 for readable contrast, text-sm for smaller descriptive text, explains importance of user feedback, clear wording improves readability and helps users understand how reviews enhance future service, improving UX -->
          </div>

        </div>
      </div>
    </section>

    <!-- Closing/About Us Summary Section -->
    <section class="relative pt-20 pb-20" style="
  background:
    linear-gradient(
      90deg,
      rgba(110, 231, 183, 0.14) 0%,
      rgba(110, 231, 183, 0.08) 38%,
      rgba(110, 231, 183, 0.04) 62%,
      rgba(110, 231, 183, 0.0) 85%
    ),
    rgb(255, 255, 255); /* pure white */
"> <!-- section container, relative positioning, pt-20 pb-20 for vertical spacing, background uses subtle left-to-right green gradient over white for visual appeal, soft gradient improves readability and draws attention without distracting users, enhancing UX -->
      <div class="max-w-4xl mx-auto px-4 text-center">
        <!-- content container, max-w-4xl limits width for easy reading, mx-auto centers horizontally, px-4 adds horizontal padding, text-center centers text, responsive layout improves readability and user experience -->

        <!-- Heading -->
        <h3 class="text-3xl font-bold text-gray-900 mb-10">
          LuxeHome — Smart Living, Thoughtfully Designed
        </h3> <!-- section heading, text-3xl for large readable size, font-bold for emphasis, text-gray-900 for good contrast, mb-10 for spacing below, clear heading improves UX and makes it easy for users to understand the section purpose -->

        <!-- Short Summary -->
        <p class="text-gray-700 text-lg mb-10">
          Founded in Birmingham, LuxeHome crafts smart, sophisticated homes that blend technology with style. Discover our collection and experience effortless luxury in every corner of your home.
        </p> <!-- paragraph text, text-gray-700 for readable contrast, text-lg for larger, easy-to-read size, mb-10 for spacing below, explains LuxeHome’s design and technology, clear wording improves readability and helps users quickly understand the brand, enhancing UX -->

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-5">
          <!-- action buttons container, flex layout stacks vertically on small screens and horizontally on larger screens, justify-center centers buttons, gap-5 adds space between buttons, responsive layout improves usability and makes buttons easy to interact with, enhancing UX -->

          <!-- Primary CTA -->
          <a href="products.php"
            class="px-8 py-4 bg-emerald-600 text-white font-semibold rounded-md shadow-sm hover:bg-emerald-700 transition">
            Start Shopping →
          </a><!-- primary CTA button, links to products page, px-8 py-4 for comfortable clickable size, green background with white text for good contrast, font-semibold for emphasis, rounded corners and shadow for visual clarity, hover changes color to dark green with smooth transition, clear and visible button improves UX and makes action easy for users -->

          <!-- Secondary CTA -->
          <a href="contact.php"
            class="px-8 py-4 bg-white text-gray-700 font-semibold rounded-md border border-gray-300 hover:bg-gray-100 transition">
            Contact Our Team
          </a>
          <!-- secondary CTA button, links to contact page, px-8 py-4 for comfortable clickable size, white background with grey text for good contrast, font-semibold for emphasis, rounded corners with border for clear shape, hover changes background to light grey with smooth transition, visible and easy-to-use button improves UX and accessibility -->

        </div>
      </div>
    </section>

    <?php
// Fetch all service reviews
$service_sql = "SELECT s.review_id, s.user_id, s.review, s.rating, s.review_date, u.username
                FROM service_reviews s
                JOIN users u ON s.user_id = u.user_id
                ORDER BY s.review_date DESC";
$service_result = mysqli_query($conn, $service_sql);
?>
<section id="service-reviews" class="mt-10">
<h2 class="text-xl font-bold mb-6">Customer Service Reviews</h2>

<?php if(mysqli_num_rows($service_result) > 0): ?>
<div class="space-y-6">
<?php while($review = mysqli_fetch_assoc($service_result)): ?>
<div class="bg-white border rounded-lg p-5 shadow-sm">
    <div class="flex justify-between items-center mb-2">
        <span class="font-semibold text-gray-800"><?= htmlspecialchars($review['username']) ?></span>
        <span class="text-sm text-gray-500"><?= date("M d, Y", strtotime($review['review_date'])) ?></span>
    </div>
    <div class="text-yellow-400 mb-2">
        <?php
        for($i=1;$i<=5;$i++){
            echo $i <= $review['rating'] ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star text-gray-300"></i>';
        }
        ?>
    </div>
    <p class="text-gray-700 mb-3"><?= htmlspecialchars($review['review']) ?></p>

    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
    <form action="php_functions/deleteServiceReview.php" method="POST">
        <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
        <button type="submit" class="text-red-600 text-sm hover:underline">
            <i class="fas fa-trash"></i> Delete Review
        </button>
    </form>
    <?php endif; ?>
</div>
<?php endwhile; ?>
</div>
<?php else: ?>
<p class="text-gray-500">No service reviews yet. Be the first to share your experience!</p>
<?php endif; ?>
</section>

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