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
        <meta charset="UTF-8"/>
        <meta name="description" content="LuxeHome">
        <meta name="author" content="Aminah Burctoolla">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>About Us | LuxeHome</title>
        <!-- SEO Meta -->
        <meta name="description" content="Get in touch with LuxeHome – the premier destination for smart home technology and automation. Our experts are ready to assist with all your smart living inquiries." />
        <meta name="keywords" content="smart home, home automation, LuxeHome, contact, customer service, technology" />
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
    <main id="main-content" class="bg-gray-100">

        <!--About Us-->
        <section class="py-20 bg-gray-100">
            <div class="bg-gray-800 text-center mb-12 py-10">
                <h2 class="text-3xl font-bold text-white mb-3">
                    About Us
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto"> 
                </p>
            </div>

            <!--Our Inspiring History-->
            <h3 class="text-center mb-3"> 
                <b>Our Inspiring History</b> 
            </h3>

            <div class=" mx-auto max-w-3xl px-4 lg:px-6 text-center">
                <p class="mb-4" title="About Us"> 
                    In 2015, we started as a humble home furniture business in the small towns of Birmingham and have now diversified, with over 3,000 employees, into a powerhouse working 24/7 to meet the demand of our customers, creating the greatest home furniture innovations, embedded with our latest cutting-edge technology. 
                </p>
                <p class="mb-4" title="About Us">
                    <em>LuxeHome</em>, our business name, is taken from the need to elevate homes by gifting a luxurious experience through smart home solutions. We offer premium services and innovations tailored towards every part of your home. Your living room, kitchen, bathroom, bedroom and outdoors are digitally and securely protected with us.
                </p>
            </div>

            <hr class="my-6 border-gray-300">
            
            <!--Our Headquarters-->
            <h3 class="text-center mb-3"> 
                <b>Our Headquarters Over the Past 10 Years</b> 
            </h3>

            <p title="Our Headquarters" class="text-center mx-auto mb-4">
                We have officially relocated our headquarters to the central area of Birmingham bringing numerous opportunities for growth and development.
            </p>

            <!--Headquarter images-->
            <div class="flex flex-col md:flex-row justify-center gap-16 mt-6">
                <!-- Old Headquarters -->
                <div class="text-center">
                    <img src="https://i2-prod.staffordshire-live.co.uk/incoming/article7455841.ece/ALTERNATES/s1200/0_Screenshot-160.png" 
                         alt="Old Building"
                         class="w-64 h-48 object-cover rounded-md mx-auto">
                    <div style="font-size: 12px;" class="mt-2">
                        Old Headquarters established in 2015
                    </div>
                </div>

                <!-- New Headquarters -->
                <div class="text-center">
                    <img src="https://www.paradisebirmingham.co.uk/wp-content/uploads/2023/02/ocw-front-on-min-1024x640.jpg" 
                         alt="New Building" 
                         class="w-64 h-48 object-cover rounded-md mx-auto">
                    <div style="font-size: 12px;" class="mt-2">
                        New Headquarters finished construction in 2025, located in the heart of Birmingham
                    </div>
                </div>
            </div>
        </section>
        
        <hr class="my-6 border-gray-300">

        <!--Vision & Scope Section-->
        <section class="py-2"> 
            <div class="flex flex-col md:flex-row gap-12 px-4 md:px-20">
                <!-- Our Vision & Scope -->
                <div class="flex-1 space-y-4">
                    <h3 class="mb-3"> 
                        <b>Our Vision</b>
                    </h3>

                    <p title="Our Vision" class="mt-2 mb-4">
                        Our vision is to be recognised as a global leader of elevating home living with our groundbreaking IoT and smart device innovations.
                    </p>
                    <p title="Our Vision" class="mt-2 mb-4">
                        Our hardworking team is innovating every day and keeping updated with new research. In the future, we aim to expand our services to customers across the globe with hundreds of more smart home innovations.
                    </p>

                    <!-- Our Scope -->
                    <h3 class="mb-3">
                        <b>Our Scope</b>
                    </h3>
                    <p class="mt-2 mb-4" title="Our Scope">
                        We produce smart home appliances and furniture and operate in the UK with plans to branch out to different locations. We serve tech savvy homeowners who are looking for a luxurious upgrade from their simple home living.
                    </p>
                    <p class="mt-2 mb-4" title="Our Scope">
                        Our products extend anywhere from living room, kitchen, bathroom, bedroom and outdoors. We are one of the few furniture businesses in the world to incorporate cutting edge technology and smart devices into ordinary home appliances at a large scale, making it our unique selling point in the business.
                    </p>
                </div>

                <!-- Furniture Image -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="https://thumbs.dreamstime.com/b/hologram-smart-furniture-interior-hologram-smart-furniture-interior-living-room-303491528.jpg" 
                         alt="Smart Furniture" 
                         class="w-72 h-[310px] object-cover mx-auto">
                </div>
            </div>
        </section>

        <hr class="my-6 border-gray-300">

        <!--What We Value-->
        <section class="py-2 text-center space-y-4 px-6">
            <h3 class="mb-3"> 
                <b>What We Value </b> 
            </h3>
            
            <div class="space-y-3">
                <p title="What we value" class="mb-4">
                    Our team is filled with 3,000 highly skilled employees that have been trained to be very reliable and capable in protecting customers and delivering you with the latest and innovative home technology.
                </p>

                <p title="What we value" class="mb-4">
                    We value authenticity and transparency above all things, so we are always at the forefront of helping you with reliable products you can trust.
                </p>
            </div>
        </section>

        <hr class="my-6 border-gray-300">

        <!--Customer Promises Section-->
        <section class="py-2">
            <div class="flex flex-col md:flex-row gap-10 px-4 max-w-6xl mx-auto">
                <!-- Images -->
                <div class="flex flex-col space-y-4">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/016/314/360/small_2x/transparent-24-hour-service-free-png.png" 
                         alt="24 hour" 
                         class="w-28 h-28 object-contain mx-auto">
                    <img src="https://png.pngtree.com/png-vector/20220724/ourmid/pngtree-user-privacy-icon-secure-vector-illustrations-vector-png-image_38119460.png" 
                         alt="customer privacy"
                         class="w-28 h-28 object-contain mx-auto">
                    <img src="https://cdn-icons-png.flaticon.com/512/9745/9745876.png" 
                         alt="customer security" 
                         class="w-28 h-28 object-contain mx-auto">
                    <img src="https://cdn-icons-png.flaticon.com/512/5569/5569615.png" 
                         alt="customer satisfaction"
                         class="w-28 h-28 object-contain mx-auto">
                </div>

                <!-- Customer Promises -->
                <div class="max-w-3xl space-y-4 text-center md:text-left mx-auto">
                    <h3 class="mb-3"> 
                        <b> Our Customer Promise</b>
                    </h3>

                    <ol class="space-y-4 list-decimal list-inside">
                        <li>LuxeHome prides itself on keeping our customer's data protected 24/7 by building secure networks, diligently monitoring our servers, and handling customer data securely.</li>
                        <li>Customer privacy is very important to us, so we give customers the opportunity to modify, remove, and update their own personal information, allowing them to choose what information is in our systems.</li>
                        <li>We also allow our customers to change their password at any point to keep our systems more secure and improve their safety.</li>
                        <li>We take immense pride in our robust databases created by our intelligent team of highly skilled and trustworthy employees, leaving you with uninterrupted services and communication through an intuitive and user-friendly e-commerce platform that is fully functioning and always accessible.</li>
                        <li>We always dedicate ourselves to deliver reliable products you trust. Therefore, if you received a product and you are unhappy with the quality, we would love to help you. Our company return policy lets you return products you have previously purchased.</li>
                        <li>We welcome you to review the products and service provided by the website so we can improve the next time you see us.</li>
                    </ol>
                </div>
            </div>
        </section>

        <hr class="my-6 border-gray-300">

        <!-- Meet The Team-->
        <section class="py-10">
            <h3 class="text-center mb-3"> 
                <b>Meet the Team </b> 
            </h3>

            <p class="text-center mb-4"> 
                Our team includes:
            </p>
            
            <div class="bg-white shadow-md rounded-2xl p-8 max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-10">
                <!-- Profile 1 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 1" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Asjad</p>
                </div>

                <!-- Profile 2 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 2" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Fahad Alajmi</p>
                </div>

                <!-- Profile 3 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 3" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Aminah Burctoolla</p>
                </div>

                <!-- Profile 4 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 4" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Ubaid Ullah Faisal</p>
                </div>

                <!-- Profile 5 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 5" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Haleema Jamil</p>
                </div>

                <!-- Profile 6 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 6" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Ameera Mohamed</p>
                </div>

                <!-- Profile 7 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 7" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Mohammed Riazul</p>
                </div>

                <!-- Profile 8 -->
                <div class="text-center">
                    <img src="https://media.istockphoto.com/id/1473473676/vector/default-avatar-profile-user-profile-icon-profile-picture-portrait-symbol-user-member-people.jpg?s=612x612&w=0&k=20&c=P40rLUrjwNod5EcUcsHhBNpNziitznnA3BimIa5CjKQ=" 
                         alt="profile pic 8" 
                         class="w-24 h-28 mx-auto bg-white rounded-md object-contain">
                    <p class="mt-3 font-semibold" style="color: black;">Jashandeep Singh</p>
                </div>
            </div>
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