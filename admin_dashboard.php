<?php
session_start();

// Check admin login
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit;
}

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=realestate','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Summary counts
$totalProperties = $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Users
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Properties with seller info
$properties = $pdo->query("
    SELECT p.*, u.name as seller_name, u.avatar as seller_avatar
    FROM properties p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Landora Admin Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="adminstyle.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo">Landora</div>
    <ul>
        <li><a href="#"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
</div>

<!-- Main content -->
<div class="main-content">
    <div class="top-nav">
        <div class="system-name">Landora Admin</div>
        <div class="profile">
            <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
        </div>
    </div>

    <div class="dashboard-content">

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card"><h3><?= $totalProperties ?></h3><p>Total Properties</p></div>
            <div class="card"><h3><?= $totalUsers ?></h3><p>Total Users</p></div>
            

        <!-- Properties Table -->
        <div class="property-table">
            <h4 style="padding: 20px; color: #001f3f;">Property Overview</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Title</th><th>Description</th><th>Price</th>
                        <th>Address</th><th>Phone</th><th>Seller</th><th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($properties as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td>$<?= number_format($p['price']) ?></td>
                        <td><?= htmlspecialchars($p['address']) ?></td>
                        <td><?= htmlspecialchars($p['phone']) ?></td>
                        <td>
                            <img src="../avatars/<?= $p['seller_avatar'] ?? 'default.png' ?>" style="width:30px; height:30px; border-radius:50%; margin-right:5px;">
                            <?= htmlspecialchars($p['seller_name']) ?>
                        </td>
                        <td><?= htmlspecialchars($p['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Users Table -->
        <div class="user-table">
            <h4 style="padding:20px; color:#001f3f;">Seller Overview</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Avatar</th><th>Name</th><th>Email</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><img src="../avatars/<?= $u['avatar'] ?? 'default.png' ?>" style="width:30px;height:30px;border-radius:50%;"></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <!--<td><?= htmlspecialchars($u['role']) ?></td>-->
                    <td>
                        <!-- Block/Unblock button -->
                        <?php if($u['status'] === 'active'): ?>
                            <a class="btn block" href="block_user.php?id=<?= $u['id'] ?>&action=block" onclick="return confirm('Are you sure you want to block this user?')">Block</a>
                        <?php else: ?>
                            <a class="btn unblock" href="block_user.php?id=<?= $u['id'] ?>&action=unblock" onclick="return confirm('Are you sure you want to unblock this user?')">Unblock</a>
                        <?php endif; ?>

                        <!-- Delete button -->
                        <a class="btn delete" href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>
