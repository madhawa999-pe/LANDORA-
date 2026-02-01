<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="userlogin.css">
</head>
<body>
<div class="container">
    <div class="signin-box">
        <h2>Sign In</h2>

        <div class="social-icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
            <i class="fab fa-linkedin"></i>
        </div>

        <p>or use your email</p>
        <!--login check with php for the login-->
        <form action="buyerlogin_Checkup.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <a href="#" class="forgot">Forgot your password?</a>
        <button type="submit" class="btn">SIGN IN</button>
        </form>

    </div>
    <!--signup panel-->
    <div class="signup-panel">
        <h2>Welcome to Landora!</h2>
        <p>Sign up to find your dream land easily.</p>

        <a href="buyer_signup.php">
            <button class="btn-outline">SIGN UP</button>
        </a>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

</body>
</html>