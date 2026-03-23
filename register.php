<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$success_message = "";

// Database connection
$servername = "localhost";
$db_username = "cs2team29";
$db_password = "eCDVXBXdLlV2mSauOg6fUiBZ9";
$db_name = "cs2team29_db";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $success_message = "Passwords do not match!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username_input, $email, $password_hash);

        if ($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit();
        } else {
            $success_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

            <title>Register | LuxeHome</title>

          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
          <link rel="stylesheet" href="css/login.css">
          <link rel="stylesheet" href="css/accessibility.css">
          <link rel="stylesheet" href="css/style.css">
          <script src="https://cdn.tailwindcss.com"></script>

           <link rel="icon" href="images/image.png">
                  </head>

       <body class="bg-gray-50">

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

<!-- Accessibility Panel Overlay -->
<div id="panelOverlay" class="panel-overlay"></div>

<!-- Accessibility Toggle Button -->
<button id="togglePanel" class="accessibility-toggle">
  <i class="fas fa-universal-access"></i>
</button>


    <main id="main-content">

          <a href="index.php" class="back-homepage">Back to Home</a>

           <section class="login-section">
              <div class="login-container">
               <div class="login-box">

              <h2 class="title">Register Here</h2>
                <p class="subtitle">Become a part of the LuxeHome community</p>


            <?php if (!empty($success_message)) : ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                <?php echo htmlspecialchars($success_message); ?>
              </div>
                <?php endif; ?>

          <form action="register.php" method="POST">

      <input type="text" name="username" placeholder="Full Name"
        value="<?php echo $_POST['username'] ?? ''; ?>" required>

            <input type="email" name="email" placeholder="Email address"
              value="<?php echo $_POST['email'] ?? ''; ?>" required>

         <input type="password" name="password" placeholder="Password" required>
         <input type="password" name="confirm_password" placeholder="Confirm Password" required>

         <button type="submit" class="login-btn">
              Create LuxeHome Account
                      </button>

</form>

<div class="divider">
  <span>OR</span>
</div>

      <p class="forgot-text">
        Already have an account?
         <a href="login.php">Login here</a>
              </p>

      </div>
   </div>
</section>

</main>

<script src="js/script.js"></script>
<script src="js/accessibility.js"></script>

</body>
</html>