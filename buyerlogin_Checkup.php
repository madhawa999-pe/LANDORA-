<?php
session_start();

// Connect database
$pdo = new PDO('mysql:host=localhost;dbname=realestate', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Check user
$stmt = $pdo->prepare("SELECT * FROM buyers WHERE email=? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {

    // Save user to session
    $_SESSION['user_id'] = $user['id'];

    // Redirect seller
    header("Location: buyer.php");
    exit;
} else {
    echo "Invalid email or password!";
}
?>
