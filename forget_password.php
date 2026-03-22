<?php
$servername = "localhost";
$db_username = "cs2team29";
$db_password = "eCDVXBXdLlV2mSauOg6fUiBZ9";
$db_name = "cs2team29_db";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    
$stmt->bind_param("s", $email);
       $stmt->execute();
         $stmt->store_result();

    if ($stmt->num_rows > 0) {
               // The Token
             $token = bin2hex(random_bytes(32));
             $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
             $insert = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $email, $token, $expires);
        $insert->execute();

        //Reset Link
        $reset_link =  "https://cs2team29.cs2410-web01pvm.aston.ac.uk/reset_password.php?token=" . $token;
       $message = "
             <p>Reset link generated successfully.</p>
              <a href='$reset_link' class='login-btn'>Go to Reset Password</a>";

       } else {
             $message = "No email account found with that email.";
    }
}
?>



<!Doctype html>   
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | LuxeHome</title>

    <link rel="icon" href="images/image.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">

    <body>
        <div class="login-section">
    <div class="login-container">
        <div class="login-box">

            <h2 class="title">Forgot your Password?</h2>
            <p class="subtitle">Enter your email to reset password.</p>

            <form method="POST">
                <input type="email" name="email" placeholder="Email address" required>
                <button type="submit" class="login-btn">Send Reset Link</button>
            </form>

             <p><?php echo $message; ?></p>

             <p class="back-link"><a href="login.php">Back to Login</a></p>
        </div>
    </div>
</div>
    </body>
</html>