<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Melody Masters</title>
    <link rel="stylesheet" href="/melody-masters/assets/css/style.css">
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

        <div class="cart">
            🛒 Cart (0)
        </div>
    </div>
</header>

<nav class="main-nav">
    <a href="index.php">HOME</a>
    <a href="shop.php">SHOP</a>
    <a href="#">ABOUT US</a>
    <a href="#">CONTACT</a>
</nav>