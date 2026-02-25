<?php 
require_once "config/db.php";
include "includes/header.php"; 

// Fetch 4 recent products for the Best Sellers section
$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($query);
?>

<!-- MAIN BANNER SECTION -->
<div class="main-banner">
    <div class="banner-wrapper">
        <img src="assets/images/banner.jpg" alt="Music Store Banner" class="banner-image">
        <div class="banner-overlay">
            <div class="banner-content fade-in-up">
                <h1 class="banner-title">Discover Your Sound</h1>
                <p class="banner-subtitle">Premium musical instruments for every musician. Experience the difference with our hand-picked collection.</p>
                <a href="shop.php" class="banner-btn">Explore Collection <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- CATEGORIES SECTION -->
<section class="modern-categories">
    <div class="section-header text-center">
        <h2 class="section-title">Shop by Category</h2>
    </div>
    <div class="category-grid">
        <a href="shop.php?category=Guitar" class="category-item glass-card">
            <div class="category-img-wrapper">
                <i class="fa-solid fa-guitar"></i>
            </div>
            <p>Guitar</p>
        </a>
        <a href="shop.php?category=Drums" class="category-item glass-card">
            <div class="category-img-wrapper">
                <i class="fa-solid fa-drum"></i>
            </div>
            <p>Drums</p>
        </a>
        <a href="shop.php?category=Keyboards" class="category-item glass-card">
            <div class="category-img-wrapper">
                <i class="fa-solid fa-keyboard"></i>
            </div>
            <p>Keyboards</p>
        </a>
        <a href="shop.php?category=Wind & Brass" class="category-item glass-card">
            <div class="category-img-wrapper">
                <i class="fa-solid fa-trumpet"></i>
            </div>
            <p>Wind & Brass</p>
        </a>
        <a href="shop.php?category=Microphones" class="category-item glass-card">
            <div class="category-img-wrapper">
                <i class="fa-solid fa-microphone"></i>
            </div>
            <p>Microphones</p>
        </a>
        <a href="shop.php?category=Sheet Music PDFs" class="category-item glass-card">
            <div class="category-img-wrapper">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <p>Sheet Music PDFs</p>
        </a>
    </div>
</section>

<!-- PRODUCT SECTION -->
<section class="products modern-products">
    <div class="section-header text-center">
        <h2 class="section-title">New Arrivals</h2>
        <a href="shop.php" class="view-all-link">View All Products <i class="fa fa-chevron-right"></i></a>
    </div>

    <div class="product-grid horizontal-row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card modern-card inline-card">
                    <div class="product-img-box">
                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-badge">New</div>
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
            <p class="no-products">No products found currently. Check back soon!</p>
        <?php endif; ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>