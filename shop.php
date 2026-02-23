<?php
require_once "config/db.php";
include "includes/header.php";

$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<section class="products">
    <h2>All Products</h2>

    <div class="product-grid">

        <?php if ($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                
                <div class="product-card">
                    <img src="assets/images/<?php echo $product['image']; ?>" alt="">

                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>

                    <p>$<?php echo number_format($product['price'], 2); ?></p>

                    <form action="add_to_cart.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <button type="submit" class="view-btn">Add to Cart</button>
</form>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>

    </div>
</section>

<?php include "includes/footer.php"; ?>