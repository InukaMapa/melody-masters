<?php
session_start();
require_once "config/db.php";
include "includes/header.php";

if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p>Product not found.</p>";
    include "includes/footer.php";
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();
?>

<style>
.product-container {
    display: flex;
    gap: 40px;
    padding: 50px;
    max-width: 1200px;
    margin: 0 auto;
}
.product-image {
    flex: 1;
}
.product-image img {
    width: 100%;
    max-width: 400px;
}
.product-info {
    flex: 1;
}
.product-info h2 {
    margin-bottom: 15px;
}
.product-info p {
    margin-bottom: 15px;
}
.quantity-box {
    width: 80px;
    padding: 8px;
}
.add-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #000;
    color: #fff;
    border: none;
    cursor: pointer;
}
</style>

<section class="product-container">

    <div class="product-image">
        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="">
    </div>

    <div class="product-info">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p><strong>Price:</strong> £ <?php echo number_format($product['price'],2); ?></p>
        <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>

        <p>
            <?php 
            echo !empty($product['description']) 
                ? htmlspecialchars($product['description']) 
                : "No description available.";
            ?>
        </p>

        <?php if ($product['stock'] > 0): ?>
        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">

            <label>Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-box">

            <br><br>
            <button type="submit" class="add-btn">Add to Cart</button>
        </form>
        <?php else: ?>
            <p style="color:red;">Out of Stock</p>
        <?php endif; ?>
    </div>

</section>

<?php include "includes/footer.php"; ?>