<?php
session_start();

$logged_in = false;
$username = "";

if (isset($_SESSION['username'])) {
  $logged_in = true;
  $username = $_SESSION['username'];
}

function getCartCount() {
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | LuxeHome</title>

    <link rel="icon" href="images/image.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accessibility.css">
    <link rel="stylesheet" href="css/login.css">

    <style>
        .logo-img {
            width: 48px;  
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>

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
        <!-- Visual -->
        <div class="accessibility-section">
            <h3 class="accessibility-section-title">
                <i class="fas fa-eye"></i> Visual Preferences
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
                    <div id="fontSizeDisplay">Normal</div>
                </div>
            </div>
        </div>

        <!-- Text -->
        <div class="accessibility-section">
            <h3 class="accessibility-section-title">
                <i class="fas fa-text-height"></i> Text & Reading
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

        <!-- Navigation -->
        <div class="accessibility-section">
            <h3 class="accessibility-section-title">
                <i class="fas fa-mouse-pointer"></i> Navigation
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

<main id="main-content">

         <a href="index.php" class="back-homepage">Back to Home</a>

          <section class="login-section">
            <div class="login-container">

              <div class="login-box">

          <h2 class="title">Welcome to LuxeHome</h2>
          <p class="subtitle">Log in to your account</p>


<form action="php_functions/user_login.php" method="POST">

<input type="email" name="email" placeholder="Email address" required>
<input type="password" name="password" placeholder="Password" required>

        <div class="login-choices"> 
             <label class="remember">
                 <input type="checkbox" name="remember"> Remember Me
                             </label>
                          <button type="button" class="forgot" onclick="openReset()">
                    Forgot Your Password?
                    </button>
                        </div>

                 <button type="submit" class="login-btn">Login</button>

                    </form>

               <div class="divider">
                 <span>OR</span>
                          </div>

             <p class="forgot-text">
              Don’t have an account?
               <a href="register.php">Register</a>
                               </p> 

              <p class="admin-text">
                 Are you an Admin?
                <a href="admin_login.php">Login as Admin</a>
                             </p>

           </div>
              </div>
         </section>

</main>

               <!-- Reset Password -->
              <div id="resetPopup" class="reset-overlay hidden">
                   <div class="reset-box">

                  <h3>Reset Password</h3>
                       <p>Click below to reset your password.</p>

      
        <button type="button" class="reset-btn" onclick="openForgetPassword()">Reset Your Password</button>

</form>

<p id="resetMessage" class="hidden">
Reset link sent to your email.
</p>

</div>
</div>

<script>
function openReset() {
    document.getElementById("resetPopup").classList.remove("hidden");
}

function closeReset() {
    document.getElementById("resetPopup").classList.add("hidden");
}

function showResetMessage() {
    document.getElementById("resetMessage").classList.remove("hidden");
}

function openForgetPassword() {
    window.location.href = "forget_password.php";
}

window.onclick = function(e) {
    const popup = document.getElementById("resetPopup");
    if (e.target === popup) {
        closeReset();
    }
}
</script>

<script src="js/script.js"></script>
<script src="js/accessibility.js"></script>

</body>
</html>