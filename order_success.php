<?php
session_start();
include "includes/header.php";

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<section class="cart-section">
    <h2>Order Placed Successfully 🎉</h2>
    <p>Your Order ID: <strong>#<?php echo $order_id; ?></strong></p>
    <p>Thank you for shopping with Melody Masters!</p>

    <a href="shop.php" class="continue-btn">Continue Shopping</a>
</section>

<?php include "includes/footer.php"; ?>