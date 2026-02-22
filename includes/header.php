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

<header>
    <h1>🎵 Melody Masters</h1>
    <nav>
        <a href="/melody-masters/index.php">Home</a>
        <a href="/melody-masters/shop.php">Shop</a>
        <a href="/melody-masters/cart.php">Cart</a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="/melody-masters/account.php">My Account</a>
            <a href="/melody-masters/logout.php">Logout</a>
        <?php else: ?>
            <a href="/melody-masters/login.php">Login</a>
            <a href="/melody-masters/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<hr>