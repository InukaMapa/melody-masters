<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Melody Masters</title>
    <link rel="stylesheet" href="/melody-masters/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="main-header">
    <div class="logo">
        <h2>🎵 Melody Masters</h2>
    </div>

    <div class="search-bar">
    <span class="search-icon">🔍</span>
    <input type="text" placeholder="Search for products, brands and more...">
</div>

    <div class="header-right">
        <div class="header-right">

    <?php if(isset($_SESSION['user_id'])): ?>
        
        <a href="account.php" class="header-btn">
            <i class="fa fa-user"></i> My Account
        </a>

        <a href="logout.php" class="header-btn logout-btn">
            Logout
        </a>

    <?php else: ?>

        <a href="login.php" class="header-btn">
            Login
        </a>

        <a href="register.php" class="header-btn register-btn">
            Register
        </a>

    <?php endif; ?>

</div>
        <div class="phone">
            📞 011 2595608
        </div>

       <a href="cart.php" class="cart-link">
    <i class="fa fa-shopping-cart"></i>

    <?php
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    ?>

    <?php if ($count > 0): ?>
        <span class="cart-count"><?php echo $count; ?></span>
    <?php endif; ?>
</a>
    </div>
</header>

<nav class="main-nav">
    <a href="index.php">HOME</a>
    <a href="shop.php">SHOP</a>
    <a href="#">ABOUT US</a>
    <a href="#">CONTACT</a>
</nav>