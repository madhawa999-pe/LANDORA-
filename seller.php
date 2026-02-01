<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect database  to the form
$pdo = new PDO('mysql:host=localhost;dbname=realestate', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//folder "upload for storing images"
$upload_dir = "uploads/";

//fetcheing user 
$user = $pdo->prepare("SELECT * FROM users WHERE id=?");
$user->execute([$user_id]);
$user = $user->fetch();

//update the profile
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $avatar = $user['avatar']; // keep existing avatar

    if (!empty($_FILES['avatar']['name'])) {
        $avatar = time() . "_" . $_FILES['avatar']['name'];
        move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $avatar);
    }

    $stmt = $pdo->prepare("UPDATE users SET name=?, avatar=? WHERE id=?");
    $stmt->execute([$name, $avatar, $user_id]);

    header("Location: seller.php");
    exit;
}

//landora total properties count
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM properties");
$tot_properties = $stmt->fetch()['total'];


//add a new property
if (isset($_POST['add_property'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
   

    $pdo->prepare("INSERT INTO properties (user_id, title, description, price, address, phone) VALUES (?, ?, ?, ?, ?, ?)")
        ->execute([$user_id, $title, $description, $price, $address, $phone]);

    $property_id = $pdo->lastInsertId();

    for ($i = 0; $i < 4; $i++) {
        if (!empty($_FILES['images']['name'][$i])) {
            $img = time() . "_" . $_FILES['images']['name'][$i];
            move_uploaded_file($_FILES['images']['tmp_name'][$i], $upload_dir . $img);

            $pdo->prepare("INSERT INTO property_images (property_id, filename) VALUES (?, ?)")
                ->execute([$property_id, $img]);
        }
    }

    header("Location: seller.php");
    exit;
}

//delete property.
if (isset($_GET['delete_property'])) {
    $id = $_GET['delete_property'];

    $pdo->prepare("DELETE FROM properties WHERE id=? AND user_id=?")
        ->execute([$id, $user_id]);

    $pdo->prepare("DELETE FROM property_images WHERE property_id=?")
        ->execute([$id]);

    header("Location: seller.php");
    exit;
}

//fetching propeties for seller 
$properties = $pdo->prepare("SELECT * FROM properties WHERE user_id=? ORDER BY id DESC");
$properties->execute([$user_id]);
$properties = $properties->fetchAll();

// Count for logged-in user
$stmt_user = $pdo->prepare("SELECT COUNT(*) AS total FROM properties WHERE user_id=?");
$stmt_user->execute([$user_id]);
$tot_properties_user = $stmt_user->fetch()['total'];

// Count for all properties
$stmt_total = $pdo->query("SELECT COUNT(*) AS total FROM properties");
$tot_properties = $stmt_total->fetch()['total'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="newseller.css">
</head>
<body>
<div class="back-button">
    <button onclick="window.history.back()">← Back</button>
    </div>
<div class="topbar">
    <h2>Seller Dashboard</h2>

    <div class="topbar-info">
    | Total Properties: <span class="total-properties"><?= $tot_properties ?></span>
</div>


    <div class="profile">
        <img src="uploads/<?= $user['avatar'] ?: 'default.png' ?>" onclick="toggleProfile()">
        <div class="profile-menu" id="profileMenu">
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                <input type="file" name="avatar">
                <button name="update_profile">Save</button>
                <a href="logout.php"><button type="button">Logout</button></a>
            </form>
        </div>
    </div>
</div>

<h3>Welcome, <?= htmlspecialchars($user['name']) ?>!</h3>
<p>Manage your properties easily. Upload images, update details, and get more buyers.</p>

<div class="form-card">
    <h3>Add New Property</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Property Title" required>
        <input type="number" name="price" placeholder="Price (Rs.)" required>
        <textarea name="description" placeholder="Description" rows="4"></textarea>
        <input type="text" name="address" placeholder="Address">
        <input type="text" name="phone" placeholder="Phone Number" required>
        <label>Upload up to 4 images:</label>
        <input type="file" name="images[]" multiple accept="image/*">
        <input type="text" name="location" placeholder="Location" required>
        <button name="add_property">Add Property</button>
    </form>
</div>
<!-- property list -->
<div class="property-list">
    <h3>Your Properties</h3>
    <?php foreach ($properties as $p): ?>
        <div class="property-card">
            <h4><?= htmlspecialchars($p['title']) ?> - Rs <?= number_format($p['price']) ?></h4>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <p><b>Address:</b> <?= htmlspecialchars($p['address']) ?></p>
            <p><b>Phone:</b> <?= htmlspecialchars($p['phone']) ?></p>
            <div class="property-images">
                <?php
                // Fetch property images safely
                $imgs = $pdo->prepare("SELECT * FROM property_images WHERE property_id=?");
                $imgs->execute([$p['id']]);
                $propertyImages = $imgs->fetchAll(PDO::FETCH_ASSOC);

                if ($propertyImages) {
                    foreach ($propertyImages as $img):
                ?>
                        <img src="uploads/<?= htmlspecialchars($img['filename']) ?>">
                <?php
                    endforeach;
                } else {
                    echo "<p>No images uploaded.</p>";
                }
                ?>
            </div>
            <a href="edit_property.php?id=<?= $p['id'] ?>"><button class="btn-edit">Edit</button></a>
            <a href="seller.php?delete_property=<?= $p['id'] ?>"><button class="btn-delete">Delete</button></a>
        </div>
    <?php endforeach; ?>
</div>


<script>
function toggleProfile() {
    const menu = document.getElementById("profileMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";// Toggle the menu visibility
}
</script>

</body>
</html>
