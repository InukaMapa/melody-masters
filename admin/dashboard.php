<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<?php include "../includes/header.php"; ?>

<section class="cart-section">
    <h2>Admin Dashboard</h2>

    <div class="product-grid">
        <div class="product-card">
            <h4>Manage Products</h4>
            <a href="products.php" class="view-btn">Go</a>
        </div>

        <div class="product-card">
            <h4>Manage Orders</h4>
            <a href="orders.php" class="view-btn">Go</a>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>