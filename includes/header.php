<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melody Masters | Premium Musical Instruments</title>
    <link rel="stylesheet" href="/melody-masters/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<header class="main-header vibrant-header">
    <div class="header-container">
        <div class="logo-section">
            <a href="index.php" class="logo-link">
                <span class="logo-icon"><i class="fa fa-music"></i></span>
                <div class="logo-text">
                    <h2 class="brand-name">Melody<span>Masters</span></h2>
                    <p class="brand-tagline">Excellence in Sound</p>
                </div>
            </a>
        </div>

        <div class="search-section">
            <form action="shop.php" method="GET" class="header-search-form">
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon-left"></i>
                    <input type="text" name="q" placeholder="Search instruments, brands..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit" class="search-submit-btn">Search</button>
                </div>
            </form>
        </div>

        <div class="header-actions">
            <div class="contact-info-mini">
                <i class="fa fa-phone-alt"></i>
                <span>011 2595608</span>
            </div>

            <div class="action-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="action-btn user-toggle">
                            <i class="fa fa-user-circle"></i>
                            <span class="btn-label"><?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?></span>
                            <i class="fa fa-chevron-down arrow"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="user-meta">
                                <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                                <span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                            </div>
                            <hr>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <a href="admin/dashboard.php"><i class="fa fa-chart-pie"></i> Admin Panel</a>
                            <?php else: ?>
                                <a href="account.php"><i class="fa fa-user"></i> My Profile</a>
                                <a href="my_orders.php"><i class="fa fa-shopping-bag"></i> My Orders</a>
                            <?php endif; ?>
                            <hr>
                            <a href="logout.php" class="logout-link"><i class="fa fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="login-link-simple">Login</a>
                    <a href="register.php" class="btn-premium-sm">Join Now</a>
                <?php endif; ?>

                <a href="cart.php" class="cart-btn-premium">
                    <div class="cart-icon-wrapper">
                        <i class="fa fa-shopping-basket"></i>
                        <?php
                        $count = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $count += $item['quantity'];
                            }
                        }
                        if ($count > 0): ?>
                            <span class="cart-badge-new"><?php echo $count; ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </div>
    </div>
</header>

<nav class="vibrant-nav">
    <div class="nav-container">
        <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="fa fa-home"></i> HOME
        </a>
        <a href="shop.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">
            <i class="fa fa-store"></i> SHOP
        </a>
        <div class="nav-divider"></div>
        <div class="trending-tags">
            <span>Trending:</span>
            <a href="shop.php?q=Guitar">Guitars</a>
            <a href="shop.php?q=Piano">Pianos</a>
            <a href="shop.php?q=Drums">Drums</a>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userToggle = document.querySelector('.user-toggle');
    const dropdown = document.querySelector('.user-dropdown');
    
    if (userToggle) {
        userToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    }
});
</script>