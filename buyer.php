<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// DB Connection
$pdo = new PDO("mysql:host=localhost;dbname=realestate", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = "";

// SEARCH logic
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    $stmt = $pdo->prepare("
        SELECT properties.*, users.name AS seller_name
        FROM properties
        JOIN users ON properties.user_id = users.id
        WHERE title LIKE ? OR address LIKE ? OR price LIKE ?
        ORDER BY properties.id DESC
    ");
    $stmt->execute([
        "%$search%", "%$search%", "%$search%"
    ]);
} else {
    // Fetch all properties
    $stmt = $pdo->query("
        SELECT properties.*, users.name AS seller_name
        FROM properties
        JOIN users ON properties.user_id = users.id
        ORDER BY properties.id DESC
    ");
}

$properties = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Buyer Dashboard</title>
    <link rel="stylesheet" href="newbuyer.css">
</head>
<body>

<div class="topbar">
    <h2>Buyer Dashboard</h2>
    <a href="index.php"><button>Logout</button></a>
</div>

<h3>Find Your Dream Property 🏠</h3>

<!-- SEARCH BAR -->
<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search by title, address or price"
           value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<div class="property-list">

<?php if (count($properties) == 0): ?>
    <p>No properties found.</p>
<?php endif; ?>

<?php foreach ($properties as $p): ?>

    <?php
    // Format phone number for WhatsApp (Sri Lanka)
    $phone = preg_replace('/\D/', '', $p['phone']);

    if (substr($phone, 0, 1) === '0') {
        $phone = '94' . substr($phone, 1);
    }

    $message = urlencode("Hello, I'm interested in your property: " . $p['title']);
    ?>

    <div class="property-card">
        <h4><?= htmlspecialchars($p['title']) ?> - Rs <?= number_format($p['price']) ?></h4>

        <p><?= htmlspecialchars(substr($p['description'], 0, 120)) ?>...</p>
        <p><b>Address:</b> <?= htmlspecialchars($p['address']) ?></p>
        <p><b>Seller:</b> <?= htmlspecialchars($p['seller_name']) ?></p>

        <!-- PROPERTY IMAGE -->
        <div class="property-images">
            <?php
            $imgStmt = $pdo->prepare("SELECT filename FROM property_images WHERE property_id=? LIMIT 1");
            $imgStmt->execute([$p['id']]);
            $img = $imgStmt->fetch();
            ?>
            <img src="uploads/<?= $img['filename'] ?? 'no-image.png' ?>">
        </div>

        <!-- WHATSAPP BUTTON ONLY -->
        <?php if (!empty($phone)): ?>
            <a href="https://wa.me/<?= $phone ?>?text=<?= $message ?>" target="_blank">
                <button class="btn-contact">💬 Contact on WhatsApp</button>
            </a>
        <?php endif; ?>

    </div>

<?php endforeach; ?>

</div>

</body>
</html>
