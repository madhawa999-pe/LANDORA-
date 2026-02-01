<?php
session_start();

// Connect database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=realestate', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: login.php?error=empty");
    exit;
}

// Check user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

    // Check if user is blocked
    if ($user['status'] !== 'active') {
        header("Location: login.php?error=blocked");
        exit;
    }

    // Save user info to session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    // Redirect to seller dashboard
    header("Location: seller.php");
    exit;

} else {
    // Invalid login
    header("Location: login.php?error=invalid");
    exit;
}
?>
