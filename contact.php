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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact & Warranty | LuxeHome</title>
    <meta name="description" content="Contact LuxeHome for enquiries or submit warranty claims for purchased items." />
    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accessibility.css">
    <style>
        #successMessage,
        #warrantyMessage {
            transition: opacity 0.5s ease;
        }

        .logo-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .floating-chatbot {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #10b981;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 26px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            transition: transform .2s;
            z-index: 9999;
        }

        .floating-chatbot:hover {
            transform: scale(1.1);
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

    <!-- HEADER -->
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
                    <a href="products.php" class="nav-link">Shop</a>
                    <a href="about_us.php" class="nav-link">About us</a>
                    <a href="contact.php" class="nav-link active">Contact</a>
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
                        <span class="text-gray-900 font-semibold">
                            <?php echo htmlspecialchars($username) ?>!
                        </span>
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
                            <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">Log out</button>
                        </form>

                        <form method="POST" action="admin_dash.php">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Admin
                                Dash</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content">
        <!-- CONTACT HEADER -->
        <section class="py-20 bg-gray-100">
            <div class="bg-gray-800 text-center mb-12 py-10">
                <h2 class="text-3xl text-white font-bold mb-2">Get in Touch</h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Contact us or submit a warranty claim for purchased LuxeHome products.
                </p>
            </div>

            <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8 grid md:grid-cols-2 gap-10">
                <!-- CONTACT INFORMATION -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Information</h3>
                    <ul class="text-gray-700 space-y-3">
                        <li><i class="fas fa-map-marker-alt text-emerald-600 mr-2"></i>123 Innovation Street,
                            Birmingham, UK</li>
                        <li><i class="fas fa-phone text-emerald-600 mr-2"></i>+44 1234 567 890</li>
                        <li><i class="fas fa-envelope text-emerald-600 mr-2"></i>hello@luxehome.com</li>
                        <li><i class="fas fa-clock text-emerald-600 mr-2"></i>Mon–Fri: 9am–6pm</li>
                    </ul>

                    <iframe class="mt-6 rounded-lg w-full h-56"
                            src="https://www.google.com/maps?q=Birmingham,UK&output=embed"></iframe>
                </div>

                <!-- CONTACT FORM -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Send Us a Message</h3>

                    <form action="https://formspree.io/f/mzznvdon" method="POST" id="contactForm" class="space-y-4">
                        <input name="name" id="name" placeholder="Full Name" class="w-full p-3 border rounded-md"
                               required>
                        <input name="email" id="email" type="email" placeholder="Email Address"
                               class="w-full p-3 border rounded-md" required>

                        <select name="product" id="product" class="w-full p-3 border rounded-md" required>
                            <option value="">Select Product</option>
                            <option value="Smart Light">Smart Light</option>
                            <option value="Garage Door">Smart Garage Door</option>
                            <option value="Window Blinds">Automated Window Blinds</option>
                            <option value="Smart Socket">Smart Power Socket</option>
                            <option value="Sensor Kit">Sensor Kit</option>
                        </select>

                        <textarea name="message" id="message" rows="4" placeholder="Your Message"
                                  class="w-full p-3 border rounded-md" required></textarea>

                        <button type="submit" id="submitBtn"
                                class="w-full bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700">
                            <span id="btnText">Send Message</span>
                            <i id="spinner" class="fas fa-spinner fa-spin hidden ml-2"></i>
                        </button>
                    </form>

                    <p id="successMessage" class="text-center text-green-600 hidden mt-3 opacity-0">
                        Message sent successfully!
                    </p>
                </div>
            </div>
        </section>

        <!-- WARRANTY CLAIM SECTION -->
        <section class="py-20 bg-white">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-6">Warranty Claim Form</h2>

                <p class="text-center text-gray-600 max-w-2xl mx-auto mb-10">
                    Submit your warranty claim by providing order details and proof of damage.
                </p>

                <form id="warrantyForm" class="bg-gray-100 p-8 rounded-2xl shadow-md space-y-4 max-w-3xl mx-auto">
                    <input id="wName" placeholder="Full Name" class="w-full p-3 border rounded-md" required>
                    <input id="wEmail" type="email" placeholder="Email Address" class="w-full p-3 border rounded-md"
                           required>
                    <input id="orderNumber" placeholder="Order Number (e.g., LH-12345)"
                           class="w-full p-3 border rounded-md" required>

                    <select id="wProduct" class="w-full p-3 border rounded-md" required>
                        <option value="">Product Purchased</option>
                        <option>Smart Light</option>
                        <option>Garage Door Controller</option>
                        <option>Window Blinds System</option>
                        <option>Smart Socket</option>
                        <option>Sensor Kit</option>
                    </select>

                    <select id="claimReason" class="w-full p-3 border rounded-md" required>
                        <option value="">Reason for Claim</option>
                        <option>Device not working</option>
                        <option>Physical damage</option>
                        <option>Power issue</option>
                        <option>Connectivity problem</option>
                    </select>

                    <textarea id="issueDetails" rows="4" placeholder="Describe the issue..."
                              class="w-full p-3 border rounded-md" required></textarea>

                    <label class="block font-medium">Upload Proof of Damage</label>
                    <input id="damageImage" type="file" accept="image/*"
                           class="w-full p-3 border rounded-md bg-white" required>

                    <button type="submit" id="warrantyBtn"
                            class="w-full bg-emerald-700 text-white p-3 rounded-md hover:bg-emerald-800">
                        Submit Warranty Claim
                    </button>

                    <p id="warrantyMessage" class="text-center mt-3 hidden opacity-0 text-green-600 font-medium"></p>
                </form>
            </div>
        </section>
    </main>

    <!-- FLOATING CHATBOT -->
    <div onclick="toggleChatbot()" class="floating-chatbot">
        <i class="fas fa-robot"></i>
    </div>

    <!-- CHATBOT WINDOW -->
    <div id="chatbotWindow"
         class="fixed bottom-28 right-6 w-80 bg-white rounded-xl shadow-xl border p-4 hidden transition-all duration-300 z-[9999]">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold text-emerald-700">LuxeHome Assistant</h3>
            <button onclick="toggleChatbot()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>

        <div id="chatContent" class="h-64 overflow-y-auto border rounded-md p-2 text-sm">
            <p class="text-gray-500 text-center">👋 Hi! How can I help you today?</p>
        </div>

        <div class="mt-3 flex">
            <input id="chatInput" type="text" placeholder="Type a message..."
                   class="flex-grow p-2 border rounded-l-md outline-none"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();sendMessage();}">
            <button onclick="sendMessage()"
                    class="bg-emerald-600 text-white px-4 rounded-r-md hover:bg-emerald-700">Send</button>
        </div>
    </div>

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
                        Experience the pinnacle of intelligent living with our curated collection of premium smart home
                        technology designed for modern lifestyles.
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
                <p class="footeropyright">
                    © 2023 LuxeHome. All rights reserved. |
                    <a href="#" class="footer-legal-link">Privacy Policy</a> |
                    <a href="#" class="footer-legal-link">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    <script>
        // -------------------------  
        // CONTACT FORM LOGIC  
        // -------------------------  
        const contactForm = document.getElementById("contactForm");
        const cMsg = document.getElementById("successMessage");
        const spinner = document.getElementById("spinner");
        const btnText = document.getElementById("btnText");

        contactForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const email = document.getElementById("email").value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                cMsg.textContent = "⚠️ Invalid Email Address";
                cMsg.classList.remove("hidden");
                fadeMsg(cMsg);
                return;
            }

            spinner.classList.remove("hidden");
            btnText.textContent = "Sending...";

            const formData = new FormData(contactForm);

            const response = await fetch("https://formspree.io/f/mzznvdon", {
                method: "POST",
                body: formData,
                headers: { "Accept": "application/json" }
            });

            spinner.classList.add("hidden");
            btnText.textContent = "Send Message";

            if (response.ok) {
                cMsg.textContent = "✅ Message sent successfully!";
                cMsg.classList.remove("hidden");
                contactForm.reset();
            } else {
                cMsg.textContent = "❌ Error sending message.";
                cMsg.classList.remove("hidden");
            }

            fadeMsg(cMsg);
        });

        // -------------------------  
        // WARRANTY FORM LOGIC  
        // -------------------------  
        const warrantyForm = document.getElementById("warrantyForm");
        const wMsg = document.getElementById("warrantyMessage");

        warrantyForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const orderNumber = document.getElementById("orderNumber").value.trim();
            const damageImage = document.getElementById("damageImage").files.length;
            const orderPattern = /^LH-\d{5}$/;

            if (!orderPattern.test(orderNumber)) {
                wMsg.textContent = "❌ Invalid Order Number. Format must be LH-12345";
                wMsg.classList.remove("hidden");
                wMsg.classList.add("text-red-600");
                fadeMsg(wMsg);
                return;
            }

            if (damageImage === 0) {
                wMsg.textContent = "❌ Please upload proof of damage.";
                wMsg.classList.remove("hidden");
                wMsg.classList.add("text-red-600");
                fadeMsg(wMsg);
                return;
            }

            wMsg.textContent = "✅ Warranty claim submitted successfully!";
            wMsg.classList.remove("hidden", "text-red-600");
            wMsg.classList.add("text-green-600");

            warrantyForm.reset();
            fadeMsg(wMsg);
        });

        // -------------------------  
        // COMMON FADE FUNCTION  
        // -------------------------  
        function fadeMsg(el) {
            setTimeout(() => el.style.opacity = "1", 100);
            setTimeout(() => el.style.opacity = "0", 3500);
            setTimeout(() => el.classList.add("hidden"), 4000);
        }

        // -------------------------  
        // CHATBOT LOGIC (rule‑based)  
        // -------------------------  
        const chatbotWindow = document.getElementById("chatbotWindow");
        const chatContent = document.getElementById("chatContent");
        const chatInput = document.getElementById("chatInput");

        // Simple intent mappings
        const botRules = [
            {
                patterns: ["hi", "hello", "hey"],
                responses: [
                    "Hi there 👋 How can I help with LuxeHome products?",
                    "Hello! Need help with an order, product, or warranty?"
                ]
            },
            {
                patterns: ["hours", "opening", "time"],
                responses: [
                    "Our support hours are Mon–Fri, 9am–6pm (UK time)."
                ]
            },
            {
                patterns: ["warranty", "claim", "guarantee"],
                responses: [
                    "You can submit a warranty claim in the form on this page. Make sure to include your order number (format LH-12345) and a photo of the issue."
                ]
            },
            {
                patterns: ["order", "track", "shipping", "delivery"],
                responses: [
                    "For order or delivery questions, please include your order number and I’ll tell you what to do next.",
                    "You can track your order from the confirmation email, or contact us at hello@luxehome.com with your order number."
                ]
            },
            {
                patterns: ["contact", "email", "phone"],
                responses: [
                    "You can contact us at hello@luxehome.com or call +44 1234 567 890 during business hours."
                ]
            },
            {
                patterns: ["product", "smart light", "garage", "socket", "blinds", "sensor"],
                responses: [
                    "We offer Smart Lights, Garage Door controllers, Automated Window Blinds, Smart Sockets and Sensor Kits. Let me know which product you have a question about."
                ]
            },
            {
                patterns: ["thanks", "thank you", "thankyou"],
                responses: [
                    "You’re welcome!",
                    "Glad I could help."
                ]
            }
        ];

        const defaultResponses = [
            "I’m not sure I understood that. Could you rephrase or ask about warranty, orders, or contact details?",
            "I can help with order queries, warranty, and basic product info. Try asking: “How do I submit a warranty claim?”"
        ];

        // Detect dissatisfaction / not satisfied phrases
        function isUserDissatisfied(text) {
            const t = text.toLowerCase();
            const negativePhrases = [
                "not satisfied",
                "not happy",
                "unhappy",
                "this is bad",
                "bad bot",
                "useless",
                "not helpful",
                "this didn't help",
                "this did not help",
                "disappointed"
            ];
            return negativePhrases.some(p => t.includes(p));
        }

        function toggleChatbot() {
            chatbotWindow.classList.toggle("hidden");
        }

        function getBotReply(userText) {
            const text = userText.toLowerCase().trim();

            if (!text) {
                return "Please type a message so I can help.";
            }

            // If user expresses dissatisfaction, escalate to human contact
            if (isUserDissatisfied(text)) {
                return "If you’re not satisfied with my help, you can contact our team at hello@luxehome.com or call +44 1234 567 890 during business hours.";
            }

            for (const rule of botRules) {
                for (const p of rule.patterns) {
                    if (text.includes(p.toLowerCase())) {
                        const idx = Math.floor(Math.random() * rule.responses.length);
                        return rule.responses[idx];
                    }
                }
            }

            const idx = Math.floor(Math.random() * defaultResponses.length);
            return defaultResponses[idx];
        }

        function appendMessage(text, fromBot = false) {
            const wrapper = document.createElement("p");
            wrapper.className = "text-sm my-1 " + (fromBot ? "text-left" : "text-right");
            const span = document.createElement("span");
            span.className = (fromBot ? "bg-gray-200" : "bg-emerald-100") + " p-1 rounded inline-block";
            span.textContent = text;
            wrapper.appendChild(span);
            chatContent.appendChild(wrapper);
            chatContent.scrollTop = chatContent.scrollHeight;
        }

        function sendMessage() {
            const value = chatInput.value.trim();
            if (!value) return;

            appendMessage(value, false);

            const reply = getBotReply(value);

            setTimeout(() => {
                appendMessage(reply, true);
            }, 500);

            chatInput.value = "";
        }
    </script>

    <script src="js/script.js"></script>
    <script src="js/accessibility.js"></script>
</body>
</html>
