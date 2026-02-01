<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) header("Location: admin_login.php");

if(isset($_GET['id'], $_GET['action'])){
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    $pdo = new PDO('mysql:host=localhost;dbname=realestate','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($action === 'block') $pdo->prepare("UPDATE users SET status='blocked' WHERE id=?")->execute([$id]);
    if($action === 'unblock') $pdo->prepare("UPDATE users SET status='active' WHERE id=?")->execute([$id]);

    header("Location: admin_dashboard.php");
    exit;
}
?>
