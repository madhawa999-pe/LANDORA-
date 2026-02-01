<?php
session_start();

//check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect database
$pdo = new PDO("mysql:host=localhost;dbname=realestate", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$upload_dir = "uploads/";

//check is the propoety is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h3 style='color:red'>Invalid property ID.</h3>");
}

$property_id = intval($_GET['id']);

//fetching property and check ownership
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id=? AND user_id=?");
$stmt->execute([$property_id, $user_id]);
$property = $stmt->fetch();

if (!$property) {
    die("<h3 style='color:red'>❌ Property not found OR you do not own this property.</h3>");
}

//fetch existing images
$img_stmt = $pdo->prepare("SELECT * FROM property_images WHERE property_id=?");
$img_stmt->execute([$property_id]);
$images = $img_stmt->fetchAll();

//update property
if (isset($_POST['update_property'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $update = $pdo->prepare("
        UPDATE properties 
        SET title=?, description=?, price=?, address=?, phone=?
        WHERE id=? AND user_id=?
    ");
    $update->execute([$title, $description, $price, $address, $phone, $property_id, $user_id]);

    //upload new images
    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
        if (!empty($_FILES['images']['name'][$i])) {
            
            $img_name = time() . "_" . basename($_FILES['images']['name'][$i]);//returns the file name only,
            $target_path = $upload_dir . $img_name;

            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) {//full path where PHP will move the uploaded file
                $pdo->prepare("INSERT INTO property_images (property_id, filename) VALUES (?, ?)")
                    ->execute([$property_id, $img_name]);
            }
        }
    }

    header("Location: seller.php");
    exit;
}

//delete image
if (isset($_GET['delete_image'])) {

    $img_id = intval($_GET['delete_image']);

    $check_img = $pdo->prepare("SELECT filename FROM property_images WHERE id=? AND property_id=?");
    $check_img->execute([$img_id, $property_id]);
    $img = $check_img->fetch();

    if ($img) {
        @unlink($upload_dir . $img['filename']);
        $pdo->prepare("DELETE FROM property_images WHERE id=?")->execute([$img_id]);
    }

    header("Location: edit_property.php?id=" . $property_id);
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Property</title>
    <link rel="stylesheet" href="newseller.css">
</head>
<body>

<div class="back-button">
    <button onclick="window.history.back()">← Back</button>
</div>

<div class="form-card">
    <h3>Edit Property</h3>
    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="title" value="<?= htmlspecialchars($property['title']) ?>" required>

        <input type="number" name="price" value="<?= htmlspecialchars($property['price']) ?>" required>

        <textarea name="description" rows="4"><?= htmlspecialchars($property['description']) ?></textarea>

        <input type="text" name="address" value="<?= htmlspecialchars($property['address']) ?>">
            
        <input type="text" name="phone" value="<?= htmlspecialchars($property['phone']) ?>" required>

        <label>Upload more images</label>
        <input type="file" name="images[]" multiple>

        <button name="update_property">Update Property</button>
    </form>
</div>

<div class="property-list">
    <h3>Existing Images</h3>

    <?php foreach ($images as $img): ?>
        <div class="property-card">
            <img src="uploads/<?= htmlspecialchars($img['filename']) ?>" 
                 style="width:120px;height:100px;border-radius:6px;">
            <a href="edit_property.php?id=<?= $property_id ?>&delete_image=<?= $img['id'] ?>">
                <button class="btn-delete">Delete Image</button>
            </a>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
