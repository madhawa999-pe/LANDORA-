<?php
// select_user.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User Type</title>
    <link rel="stylesheet" href="select_user.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Back Button -->
    <div class="back-button">
        <button onclick="window.history.back()">← Back</button>
    </div>

    <!-- Main Container -->
    <div class="container">
        <h2>Select User Type</h2>

        <div class="options">
            <!-- Buyer -->
            <a href="buyer_login.php" class="box">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Buyer</h3>
                <p>Find and purchase land easily.</p>
            </a>

            <!-- Seller -->
            <a href="login.php" class="box">
                <i class="fa-solid fa-store"></i>
                <h3>Seller</h3>
                <p>Sell your land with better visibility.</p>
            </a>
        </div>
    </div>

    <!-- JS Animation -->
    <script>
        window.onload = () => {
            document.querySelectorAll(".box").forEach((box, i) => {// Select all elements that have the class "box"
                setTimeout(() => {
                    box.style.opacity = "1";//make visible
                    box.style.transform = "translateY(0)";// Move the box to its original position 
                }, i * 150);// Each box appears 150ms after the previous one
            });
        };
    </script>

</body>
</html>
