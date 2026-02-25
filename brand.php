<?php
require_once "config/db.php";
include "includes/header.php";

if (!isset($_GET['brand_id'])) {
    header("Location: index.php");
    exit();
}

$brand_id = intval($_GET['brand_id']);

// Fetch brand details
$brand_stmt = $conn->prepare("SELECT * FROM brands WHERE id = ?");
$brand_stmt->bind_param("i", $brand_id);
$brand_stmt->execute();
$brand_res = $brand_stmt->get_result();

if ($brand_res->num_rows !== 1) {
    echo "<h2>Brand not found.</h2>";
    include "includes/footer.php";
    exit();
}

$brand = $brand_res->fetch_assoc();
$brand_stmt->close();

// Fetch products for this brand
$prod_stmt = $conn->prepare("SELECT * FROM products WHERE brand_id = ? ORDER BY created_at DESC");
$prod_stmt->bind_param("i", $brand_id);
$prod_stmt->execute();
$result = $prod_stmt->get_result();
?>

<section class="products modern-products">
    <div class="section-header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="uploads/<?php echo $brand['image']; ?>" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e11d48;">
            <h2 class="section-title"><?php echo htmlspecialchars($brand['name']); ?> Products</h2>
        </div>
        <a href="shop.php" class="view-all-link">Back to Shop <i class="fa fa-arrow-left"></i></a>
    </div>

    <div class="product-grid horizontal-row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card modern-card inline-card">
                    <div class="product-img-box">
                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    
                    <div class="product-info">
                        <h4 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h4>
                        <div class="product-price-row">
                            <span class="product-price"> £ <?php echo number_format($product['price'], 2); ?></span>
                        </div>
                        
                        <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn-addToCart"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-products">No products found for this brand.</p>
        <?php endif; ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>
