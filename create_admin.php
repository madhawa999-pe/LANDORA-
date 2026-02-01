<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=realestate','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Change the username/password if you want
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    echo "Admin created successfully!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
