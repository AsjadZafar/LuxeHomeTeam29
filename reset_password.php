<?php
$servername = "localhost";
$db_username = "cs2team29";
$db_password = "eCDVXBXdLlV2mSauOg6fUiBZ9";
$db_name = "cs2team29_db";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

$message = "";
$token_valid = false;
$token = "";


        if (isset($_GET['token'])) {
           $token = $_GET['token'];

            $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
   
            $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

              if (strtotime($row['expires_at']) > time()) {
                     $token_valid = true;
            } else {
                     $message = "This reset link has expired.";
                  }
             } else {
               $message = "Invalid reset link.";
                  }
}


       if ($_SERVER["REQUEST_METHOD"] === "POST") {

       $token = $_POST['token'];
       $password = $_POST['password'];
       $confirm = $_POST['confirm_password'];

              if ($password !== $confirm) {
                 $message = "Passwords don't match.";
       
        $token_valid = true; 

    } else {

        $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
       
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            if (strtotime($row['expires_at']) > time()) {

                $email = $row['email'];
                $new_password = password_hash($password, PASSWORD_DEFAULT);

                // Update pass
                $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");


                $update->bind_param("ss", $new_password, $email);
                $update->execute();

                // Deletion
                $delete = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $delete->bind_param("s", $token);
                $delete->execute();

                    header("Location: login.php");
                    exit();

               } else {
                  $message = "This reset link has expired.";
            }

      
      
      
            } else {
            $message = "Invalid request.";
        }



    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | LuxeHome</title>

    <link rel="icon" href="images/image.png">
<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">

   
<style>

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="login-section">
    <div class="login-container">
        <div class="login-box">

            <h2 class="title">Reset Password</h2>

  <?php if (!empty($message)) : ?>
         <p class="error"><?php echo $message; ?></p>
         <?php endif; ?>

            <?php if ($token_valid) : ?>
                <form method="POST">
                    
     <input type="password"  name="password"  placeholder="New Password"  required
    class="w-4/5 max-w-sm mx-auto block mb-4 px-3 py-3 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-600"
/>

  <input  type="password"  name="confirm_password"  placeholder="Confirm Password"  required
    class="w-4/5 max-w-sm mx-auto block mb-4 px-3 py-3 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-600"
/>
            
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
                    

     <button type="submit" class="login-btn">
                        Update Your Password
                    </button>
                </form>
            <?php endif; ?>

            <p class="backto-link"><a href="login.php">Back to Login</a></p>

        </div>
    </div>
</div>

</body>
</html>