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
        #successMessage, #warrantyMessage { transition: opacity 0.5s ease; }
        .logo-img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
        .floating-chatbot {
            position: fixed; bottom: 25px; right: 25px; background: #10b981;
            width: 60px; height: 60px; border-radius: 50%; display: flex;
            justify-content: center; align-items: center; color: white;
            font-size: 26px; cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.25); transition: transform .2s; z-index: 9999;
        }
        .floating-chatbot:hover { transform: scale(1.1); }
        .nav-link { font-size: 0.875rem; font-weight: 500; color: #4b5563; transition: color 0.3s ease; }
        .nav-link.active { color: #047857; border-bottom: 2px solid #047857; padding-bottom: 0.25rem; }
        .nav-link:hover { color: #047857; }
        .action-btn { color: #4b5563; transition: color 0.3s ease; }
        .action-btn:hover { color: #047857; }
        .cart-badge {
            position: absolute; top: -0.5rem; right: -0.5rem; background: #059669;
            color: white; font-size: 0.75rem; border-radius: 50%;
            width: 1.25rem; height: 1.25rem; display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>

<body class="bg-gray-50">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Accessibility Panel -->
    <div id="accessibilityPanel" class="accessibility-panel">
        <div class="accessibility-header">
            <h2 class="accessibility-title">Accessibility Settings</h2>
            <p class="accessibility-subtitle">Customize your browsing experience</p>
            <button id="closePanel" class="accessibility-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="accessibility-content">
            <div class="accessibility-section">
                <h3 class="accessibility-section-title"><i class="fas fa-eye"></i> Visual Preferences</h3>
                <div class="accessibility-options">
                    <div class="accessibility-option"><input type="checkbox" id="darkMode"><label for="darkMode">Dark Mode</label></div>
                    <div class="accessibility-option"><input type="checkbox" id="highContrast"><label for="highContrast">High Contrast</label></div>
                    <div class="accessibility-option">
                        <label for="fontSize">Font Size</label>
                        <input type="range" id="fontSize" min="0" max="3" value="1">
                        <div class="font-size-display" id="fontSizeDisplay">Normal</div>
                    </div>
                </div>
            </div>
            <div class="accessibility-section">
                <h3 class="accessibility-section-title"><i class="fas fa-text-height"></i> Text & Reading</h3>
                <div class="accessibility-options">
                    <div class="accessibility-option"><input type="checkbox" id="dyslexiaFont"><label for="dyslexiaFont">Dyslexia-Friendly Font</label></div>
                    <div class="accessibility-option"><input type="checkbox" id="lineSpacing"><label for="lineSpacing">Increased Line Spacing</label></div>
                </div>
            </div>
            <div class="accessibility-section">
                <h3 class="accessibility-section-title"><i class="fas fa-mouse-pointer"></i> Navigation</h3>
                <div class="accessibility-options">
                    <div class="accessibility-option"><input type="checkbox" id="focusIndicator"><label for="focusIndicator">Enhanced Focus Indicators</label></div>
                    <div class="accessibility-option"><input type="checkbox" id="skipLinks"><label for="skipLinks">Enable Skip Links</label></div>
                </div>
            </div>
            <button id="resetSettings" class="accessibility-reset"><i class="fas fa-undo"></i> Reset All Settings</button>
        </div>
    </div>
    <div id="panelOverlay" class="panel-overlay"></div>
    <button id="togglePanel" class="accessibility-toggle"><i class="fas fa-universal-access"></i></button>

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
                <nav class="hidden md:flex space-x-8">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="products.php" class="nav-link">Shop</a>
                    <a href="about_us.php" class="nav-link">About us</a>
                    <a href="contact.php" class="nav-link active">Contact</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <button class="action-btn"><i class="fas fa-search"></i></button>
                    <?php if ($logged_in): ?>
                    <a href="cart.php" class="action-btn relative">
                        <i class="fas fa-shopping-cart"></i><span class="cart-badge">3</span>
                    </a>
                    <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($username) ?>!</span>
                    <?php else: ?>
                    <a href="cart.php" class="action-btn relative">
                        <i class="fas fa-shopping-cart"></i><span class="cart-badge">0</span>
                    </a>
                    <a href="login.php" class="action-btn"><i class="fas fa-user"></i></a>
                    <?php endif; ?>
                </div>
                <?php if ($logged_in): ?>
                <div class="flex items-center space-x-2">
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

    <main id="main-content">

        <!-- CONTACT SECTION -->
        <section class="py-20 bg-gray-100">
            <div class="bg-gray-800 text-center mb-12 py-10">
                <h2 class="text-3xl text-white font-bold mb-2">Get in Touch</h2>
                <p class="text-gray-300 max-w-2xl mx-auto">Contact us or submit a warranty claim for purchased LuxeHome products.</p>
            </div>
            <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8 grid md:grid-cols-2 gap-10">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Information</h3>
                    <ul class="text-gray-700 space-y-3">
                        <li><i class="fas fa-map-marker-alt text-emerald-600 mr-2"></i>123 Innovation Street, Birmingham, UK</li>
                        <li><i class="fas fa-phone text-emerald-600 mr-2"></i>+44 1234 567 890</li>
                        <li><i class="fas fa-envelope text-emerald-600 mr-2"></i>hello@luxehome.com</li>
                        <li><i class="fas fa-clock text-emerald-600 mr-2"></i>Mon–Fri: 9am–6pm</li>
                    </ul>
                    <iframe class="mt-6 rounded-lg w-full h-56" src="https://www.google.com/maps?q=Birmingham,UK&output=embed"></iframe>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Send Us a Message</h3>
                    <form action="https://formspree.io/f/mzznvdon" method="POST" id="contactForm" class="space-y-4">
                        <input name="name" id="name" placeholder="Full Name" class="w-full p-3 border rounded-md" required>
                        <input name="email" id="email" type="email" placeholder="Email Address" class="w-full p-3 border rounded-md" required>
                        <select name="product" id="product" class="w-full p-3 border rounded-md" required>
                            <option value="">Select Product</option>
                            <option value="Smart Light">Smart Light</option>
                            <option value="Garage Door">Smart Garage Door</option>
                            <option value="Window Blinds">Automated Window Blinds</option>
                            <option value="Smart Socket">Smart Power Socket</option>
                            <option value="Sensor Kit">Sensor Kit</option>
                        </select>
                        <textarea name="message" id="message" rows="4" placeholder="Your Message" class="w-full p-3 border rounded-md" required></textarea>
                        <button type="submit" id="submitBtn" class="w-full bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700">
                            <span id="btnText">Send Message</span>
                            <i id="spinner" class="fas fa-spinner fa-spin hidden ml-2"></i>
                        </button>
                    </form>
                    <p id="successMessage" class="text-center text-green-600 hidden mt-3 opacity-0"></p>
                </div>
            </div>
        </section>

        <!-- WARRANTY SECTION -->
        <section class="py-20 bg-white">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-6">Warranty Claim Form</h2>
                <p class="text-center text-gray-600 max-w-2xl mx-auto mb-10">Submit your warranty claim by providing order details and proof of damage.</p>
                <form id="warrantyForm" class="bg-gray-100 p-8 rounded-2xl shadow-md space-y-4 max-w-3xl mx-auto">
                    <input id="wName" placeholder="Full Name" class="w-full p-3 border rounded-md" required>
                    <input id="wEmail" type="email" placeholder="Email Address" class="w-full p-3 border rounded-md" required>
                    <input id="orderNumber" placeholder="Order Number (e.g., LH-12345)" class="w-full p-3 border rounded-md" required>
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
                    <textarea id="issueDetails" rows="4" placeholder="Describe the issue..." class="w-full p-3 border rounded-md" required></textarea>
                    <label class="block font-medium">Upload Proof of Damage</label>
                    <input id="damageImage" type="file" accept="image/*" class="w-full p-3 border rounded-md bg-white" required>
                    <button type="submit" id="warrantyBtn" class="w-full bg-emerald-700 text-white p-3 rounded-md hover:bg-emerald-800">
                        Submit Warranty Claim
                    </button>
                    <p id="warrantyMessage" class="text-center mt-3 hidden opacity-0 font-medium"></p>
                </form>
            </div>
        </section>

    </main>

    <!-- CHATBOT BUTTON — uses luxeChatToggle(), not toggleChatbot() -->
    <div onclick="luxeChatToggle()" class="floating-chatbot">
        <i class="fas fa-robot"></i>
    </div>

    <!-- CHATBOT WINDOW — unique IDs prefixed with "luxeChat" -->
    <div id="luxeChatWindow"
        class="fixed bottom-28 right-6 w-80 bg-white rounded-xl shadow-xl border p-4 hidden transition-all duration-300 z-[9999]">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold text-emerald-700">LuxeHome Assistant</h3>
            <button onclick="luxeChatToggle()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>
        <div id="luxeChatContent" class="h-64 overflow-y-auto border rounded-md p-2 text-sm space-y-1">
            <p class="text-gray-500 text-center">👋 Hi! How can I help you today?</p>
        </div>
        <div class="mt-3 flex">
            <input id="luxeChatInput" type="text" placeholder="Type a message..."
                class="flex-grow p-2 border rounded-l-md outline-none">
            <button onclick="luxeChatSend()"
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
                        <div><h3 class="brand-name">LuxeHome</h3><p class="brand-tagline">Smart Living Elevated</p></div>
                    </div>
                    <p class="brand-description">Experience the pinnacle of intelligent living with our curated collection of premium smart home technology designed for modern lifestyles.</p>
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
                <p class="footeropyright">© 2023 LuxeHome. All rights reserved. |
                    <a href="#" class="footer-legal-link">Privacy Policy</a> |
                    <a href="#" class="footer-legal-link">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- External scripts load FIRST -->
    <script src="js/script.js"></script>
    <script src="js/accessibility.js"></script>

    <!-- Our scripts load LAST — after external JS has already run -->
    <script>
    // =============================================================
    // CONTACT FORM — wrapped in IIFE to avoid global name clashes
    // =============================================================
    (function () {
        var form    = document.getElementById("contactForm");
        var msg     = document.getElementById("successMessage");
        var spinner = document.getElementById("spinner");
        var btnTxt  = document.getElementById("btnText");
        if (!form) return;

        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            var email = document.getElementById("email").value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                msg.textContent = "⚠️ Invalid Email Address";
                msg.classList.remove("hidden");
                luxeFade(msg); return;
            }
            spinner.classList.remove("hidden");
            btnTxt.textContent = "Sending...";
            try {
                var res = await fetch("https://formspree.io/f/mzznvdon", {
                    method: "POST", body: new FormData(form),
                    headers: { "Accept": "application/json" }
                });
                msg.textContent = res.ok ? "✅ Message sent successfully!" : "❌ Error sending message.";
                if (res.ok) form.reset();
            } catch (err) {
                msg.textContent = "❌ Network error. Please check your connection.";
            }
            spinner.classList.add("hidden");
            btnTxt.textContent = "Send Message";
            msg.classList.remove("hidden");
            luxeFade(msg);
        });
    })();

    // =============================================================
    // WARRANTY FORM — wrapped in IIFE
    // =============================================================
    (function () {
        var form = document.getElementById("warrantyForm");
        var msg  = document.getElementById("warrantyMessage");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            var orderNum  = document.getElementById("orderNumber").value.trim();
            var fileCount = document.getElementById("damageImage").files.length;

            if (!/^LH-\d{5}$/.test(orderNum)) {
                msg.textContent = "❌ Invalid Order Number. Format must be LH-12345";
                msg.className = "text-center mt-3 text-red-600 font-medium";
                msg.classList.remove("hidden"); luxeFade(msg); return;
            }
            if (fileCount === 0) {
                msg.textContent = "❌ Please upload proof of damage.";
                msg.className = "text-center mt-3 text-red-600 font-medium";
                msg.classList.remove("hidden"); luxeFade(msg); return;
            }
            msg.textContent = "✅ Warranty claim submitted successfully!";
            msg.className = "text-center mt-3 text-green-600 font-medium";
            msg.classList.remove("hidden");
            form.reset(); luxeFade(msg);
        });
    })();

    // =============================================================
    // SHARED FADE HELPER
    // =============================================================
    function luxeFade(el) {
        el.style.opacity = "0";
        setTimeout(function () { el.style.opacity = "1"; }, 100);
        setTimeout(function () { el.style.opacity = "0"; }, 3500);
        setTimeout(function () { el.classList.add("hidden"); }, 4000);
    }

    // =============================================================
    // CHATBOT — fully isolated IIFE
    // IDs:      luxeChatWindow, luxeChatContent, luxeChatInput
    // Globals:  window.luxeChatToggle, window.luxeChatSend
    // Nothing inside can be touched by script.js or accessibility.js
    // =============================================================
    (function () {
        var win     = document.getElementById("luxeChatWindow");
        var content = document.getElementById("luxeChatContent");
        var input   = document.getElementById("luxeChatInput");
        if (!win || !content || !input) return;

        var rules = [
            {
                patterns: ["hi","hello","hey","good morning","good afternoon"],
                responses: [
                    "Hi there 👋 How can I help you with LuxeHome today?",
                    "Hello! Ask me about products, stock, orders or warranty."
                ]
            },
            {
                patterns: ["hours","opening","open","business hours","when are you"],
                responses: ["Our support hours are Mon–Fri, 9am–6pm (UK time)."]
            },
            {
                patterns: ["warranty","claim","guarantee","broken","faulty"],
                responses: [
                    "You can submit a warranty claim using the form on this page. Include your order number (format LH-12345) and a photo of the issue.",
                    "Fill in the Warranty Claim Form below — you'll need your order number (LH-12345) and a damage photo."
                ]
            },
            {
                patterns: ["order status","where is my order","track my order","where's my order","my order"],
                responses: [
                    "To check your order status, have your order number ready (format: LH-12345). Check your confirmation email for a tracking link, or email hello@luxehome.com.",
                    "Order updates are sent to your registered email. If you haven't received one, contact hello@luxehome.com with your order number."
                ]
            },
            {
                patterns: ["order","track","shipping","delivery","dispatch","posted"],
                responses: [
                    "For shipping or delivery questions, share your order number (LH-12345) and we'll look into it.",
                    "You can track your order via the link in your confirmation email, or email hello@luxehome.com."
                ]
            },
            {
                patterns: ["in stock","out of stock","stock","available","availability","restock","when back"],
                responses: [
                    "Live stock levels are shown on our Shop page — visit there to check current availability!",
                    "If a product is showing unavailable, email hello@luxehome.com and we'll let you know when it's back."
                ]
            },
            {
                patterns: ["price","cost","how much","pricing","discount","offer","sale"],
                responses: [
                    "All pricing is shown on our Shop page. We run regular promotions too — check the homepage for current deals!",
                    "Visit the Shop page for full pricing. Feel free to ask if you want help comparing products."
                ]
            },
            {
                patterns: ["return","refund","exchange","money back","send back","cancel"],
                responses: ["We offer a 30-day return policy. Email hello@luxehome.com with your order number and reason to get started."]
            },
            {
                patterns: ["contact","email","phone","call","speak to","human","agent"],
                responses: [
                    "Reach us at hello@luxehome.com or call +44 1234 567 890, Mon–Fri 9am–6pm (UK time).",
                    "Our team is available at hello@luxehome.com or +44 1234 567 890 during business hours."
                ]
            },
            {
                patterns: ["smart light","smart lights","lighting","bulb"],
                responses: ["Our Smart Light supports voice control, scheduling and millions of colours. Check the Shop for pricing and stock!"]
            },
            {
                patterns: ["garage","garage door"],
                responses: ["The Smart Garage Door Controller lets you open, close and monitor your garage remotely. Visit the Shop for details."]
            },
            {
                patterns: ["blinds","window blinds","automated blinds","smart blinds"],
                responses: ["Our Automated Window Blinds can be scheduled or voice-controlled. See the Shop for more info."]
            },
            {
                patterns: ["socket","smart socket","power socket","plug","smart plug"],
                responses: ["The Smart Power Socket lets you control devices remotely and monitor energy usage. Available in the Shop!"]
            },
            {
                patterns: ["sensor","sensor kit","motion sensor","door sensor"],
                responses: ["Our Sensor Kit includes motion, temperature and door/window sensors. Check the Shop for availability."]
            },
            {
                patterns: ["product","products","range","catalogue","what do you sell"],
                responses: [
                    "We offer Smart Lights, Garage Door Controllers, Window Blinds, Smart Sockets and Sensor Kits. Visit the Shop to browse!",
                    "Our range includes Smart Lights, Garage Controllers, Window Blinds, Smart Sockets and Sensor Kits — all on the Shop page."
                ]
            },
            {
                patterns: ["install","setup","set up","how to use","manual","instructions"],
                responses: ["Each product comes with a setup guide in the box. You can also email hello@luxehome.com for installation support."]
            },
            {
                patterns: ["thanks","thank you","thankyou","cheers","great","helpful"],
                responses: [
                    "You're welcome! 😊 Anything else I can help with?",
                    "Happy to help! Let me know if you have any other questions."
                ]
            },
            {
                patterns: ["bye","goodbye","see you","that's all","thats all"],
                responses: ["Goodbye! 👋 Have a great day.", "Take care! Come back anytime."]
            }
        ];

        var fallbacks = [
            "I'm not sure about that. Try asking about products, stock, orders, pricing or warranty.",
            "I can help with product info, stock, orders, pricing and warranty. For example: \"Is the Smart Light in stock?\"",
            "I didn't quite catch that. You can also reach our team at hello@luxehome.com."
        ];

        var badPhrases = [
            "not satisfied","not happy","unhappy","this is bad","bad bot",
            "useless","not helpful","this didn't help","this did not help",
            "disappointed","terrible","awful","rubbish","waste of time"
        ];

        function getReply(raw) {
            var text = raw.toLowerCase().trim();
            if (!text) return "Please type a message and I'll do my best to help!";

            for (var i = 0; i < badPhrases.length; i++) {
                if (text.indexOf(badPhrases[i]) !== -1)
                    return "I'm sorry to hear that! Our team can help — email hello@luxehome.com or call +44 1234 567 890 (Mon–Fri 9am–6pm).";
            }

            for (var r = 0; r < rules.length; r++) {
                for (var p = 0; p < rules[r].patterns.length; p++) {
                    if (text.indexOf(rules[r].patterns[p]) !== -1) {
                        var resp = rules[r].responses;
                        return resp[Math.floor(Math.random() * resp.length)];
                    }
                }
            }
            return fallbacks[Math.floor(Math.random() * fallbacks.length)];
        }

        function addMsg(text, fromBot) {
            var p = document.createElement("p");
            var span = document.createElement("span");
            p.className = "text-sm " + (fromBot ? "text-left" : "text-right");
            span.className = (fromBot ? "bg-gray-200 text-gray-800" : "bg-emerald-100 text-emerald-900")
                           + " px-3 py-1 rounded-lg inline-block max-w-xs break-words";
            span.textContent = text;
            p.appendChild(span);
            content.appendChild(p);
            content.scrollTop = content.scrollHeight;
        }

        function doSend() {
            var val = input.value.trim();
            if (!val) return;
            addMsg(val, false);
            var reply = getReply(val);  // capture BEFORE clearing
            input.value = "";
            setTimeout(function () { addMsg(reply, true); }, 450);
        }

        // Enter key bound here — no inline onkeydown needed in HTML
        input.addEventListener("keydown", function (e) {
            if (e.key === "Enter") { e.preventDefault(); doSend(); }
        });

        // Only these two names are exposed globally — unique enough to
        // never clash with anything in script.js or accessibility.js
        window.luxeChatToggle = function () { win.classList.toggle("hidden"); };
        window.luxeChatSend   = doSend;

    })();
    </script>

</body>
</html>
