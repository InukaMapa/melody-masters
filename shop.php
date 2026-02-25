<?php
require_once "config/db.php";
include "includes/header.php";

// Filter by category or search query
$category_name = isset($_GET['category']) ? trim($_GET['category']) : '';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$title = "All Items";

if ($search_query && $category_name) {
    $search_term = "%$search_query%";
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE (p.name LIKE ? OR c.name LIKE ?) AND c.name = ?
                            ORDER BY p.created_at DESC");
    $stmt->bind_param("sss", $search_term, $search_term, $category_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $title = "Results for '" . htmlspecialchars($search_query) . "' in " . htmlspecialchars($category_name);
} elseif ($search_query) {
    $search_term = "%$search_query%";
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE p.name LIKE ? OR c.name LIKE ? 
                            ORDER BY p.created_at DESC");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    $title = "Results for '" . htmlspecialchars($search_query) . "'";
} elseif ($category_name) {
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE c.name = ? 
                            ORDER BY p.created_at DESC");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $title = htmlspecialchars($category_name) . " Collection";
} else {
    $query = "SELECT * FROM products ORDER BY created_at DESC";
    $result = $conn->query($query);
}
?>

<section class="products modern-products">
    <div class="section-header">
        <h2 class="section-title"><?php echo $title; ?></h2>
        <?php if ($category_name || $search_query): ?>
            <a href="shop.php" class="view-all-link" title="Clear Filters"><i class="fa fa-times-circle"></i> View All</a>
        <?php endif; ?>
    </div>

    <div class="product-grid shop-grid">

        <?php if ($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card modern-card inline-card">
                    <a href="product_details.php?id=<?php echo $product['id']; ?>" class="product-img-box">
                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>

                    <div class="product-info">
                        <h4 class="product-name">
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" style="text-decoration:none; color:inherit;">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h4>
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
            <p class="no-products">No products found.</p>
        <?php endif; ?>

    </div>
</section>

<?php include "includes/footer.php"; ?>