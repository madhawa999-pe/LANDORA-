<?php

//database connection
$pdo = new PDO('mysql:host=localhost;dbname=realestate', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//registration logic
$message = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $message = "Email already registered!";
    } else {
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        // Redirect to login
        header("Location: login.php?registered=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="signupnew.css">

    <!--Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="container">

    <!-- LEFT SIDE (Registration Form) -->
    <div class="left-box">
        <h1>Registration</h1>

        <?php if ($message != ""): ?>
            <p style="color: red; font-weight: bold;"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" action="signup.php">

            <div class="input-group">
                <input type="text" name="name" placeholder="Your Name" required>
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="abc@gmail.com" required>
                <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <button class="btn-register" name="register">Register</button>
        </form>

        <p class="small-text">or register with social platforms</p>

        <div class="social-icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
            <i class="fab fa-linkedin"></i>
        </div>
    </div>

    <!-- RIGHT SIDE (Welcome Back Panel) -->
    <div class="right-box">
        <h1>Welcome Back!</h1>
        <p>Already have an account?</p>

        <a href="login.php">
            <button class="btn-login">Login</button>
        </a>
    </div>

</div>

</body>
</html>
