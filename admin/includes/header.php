<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../config/db.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Melody Masters</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            MelodyMasters
        </div>

        <nav class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Overview</div>
                <a href="dashboard.php" class="menu-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fa fa-th-large"></i> Dashboard
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Catalogue</div>
                <a href="products.php" class="menu-item <?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                    <i class="fa fa-box"></i> Products
                </a>
                <a href="add_product.php" class="menu-item <?php echo $current_page == 'add_product.php' ? 'active' : ''; ?>">
                    <i class="fa fa-plus-circle"></i> Add Product
                </a>
                <a href="manage_categories.php" class="menu-item <?php echo $current_page == 'manage_categories.php' ? 'active' : ''; ?>">
                    <i class="fa fa-tag"></i> Categories
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Sales</div>
                <a href="orders.php" class="menu-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                    <i class="fa fa-shopping-cart"></i> Orders
                </a>
                <a href="customers.php" class="menu-item <?php echo $current_page == 'customers.php' ? 'active' : ''; ?>">
                    <i class="fa fa-users"></i> Customers
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Administration</div>
                <a href="manage_admins.php" class="menu-item <?php echo $current_page == 'manage_admins.php' ? 'active' : ''; ?>">
                    <i class="fa fa-user-shield"></i> Manage Admins
                </a>
                <a href="manage_staff.php" class="menu-item <?php echo $current_page == 'manage_staff.php' ? 'active' : ''; ?>">
                    <i class="fa fa-id-badge"></i> Manage Staff
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="../index.php" class="menu-item">
                <i class="fa fa-external-link-alt"></i> View Store
            </a>
            <a href="../logout.php" class="menu-item">
                <i class="fa fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
