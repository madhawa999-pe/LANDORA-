<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) header("Location: admin_login.php");

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $pdo = new PDO('mysql:host=localhost;dbname=realestate','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete user and their properties
    $pdo->prepare("DELETE FROM properties WHERE user_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);

    header("Location: admin_dashboard.php");
    exit;
}
?>
