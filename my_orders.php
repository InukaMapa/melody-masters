<?php
session_start();
require_once "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user info for sidebar
$stmt_user = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_info = $stmt_user->get_result()->fetch_assoc();
?>

<div class="account-page-wrapper">
    <div class="account-container">
        <div class="account-sidebar">
            <div class="user-profile-summary">
                <div class="profile-avatar">
                    <i class="fa fa-user"></i>
                </div>
                <h3><?php echo htmlspecialchars($user_info['full_name']); ?></h3>
                <p><?php echo htmlspecialchars($user_info['email']); ?></p>
            </div>
            <nav class="account-nav">
                <a href="account.php"><i class="fa fa-user-circle"></i> Profile Details</a>
                <a href="my_orders.php" class="active"><i class="fa fa-shopping-bag"></i> My Orders</a>
                <a href="logout.php" class="logout-link-sidebar"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>

        <div class="account-content">
            <div class="content-card">
                <h2>My Orders</h2>
                <p class="section-subtitle">View and track your previous purchases.</p>
                <hr>
                
                <?php if ($result->num_rows > 0): ?>
                    <div class="orders-list">
                        <?php while ($order = $result->fetch_assoc()): ?>
                            <div class="order-card-item">
                                <div class="order-main-info">
                                    <div class="order-meta">
                                        <span class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                                        <span class="order-number">Order #<?php echo $order['id']; ?></span>
                                    </div>
                                    <div class="order-status-badge status-<?php echo strtolower($order['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                                    </div>
                                </div>
                                <div class="order-details-summary">
                                    <div class="summary-col">
                                        <label>Total Amount</label>
                                        <div class="val text-red">£<?php echo number_format($order['total_amount'], 2); ?></div>
                                    </div>
                                    <div class="summary-col">
                                        <label>Items</label>
                                        <div class="val">
                                            <?php 
                                            // Optional: Fetch item count
                                            $o_id = $order['id'];
                                            $item_stmt = $conn->prepare("SELECT COUNT(*) as cmd FROM order_items WHERE order_id = ?");
                                            $item_stmt->bind_param("i", $o_id);
                                            $item_stmt->execute();
                                            $res_items = $item_stmt->get_result()->fetch_assoc();
                                            echo $res_items['cmd'];
                                            ?> Items
                                        </div>
                                    </div>
                                    <div class="summary-col">
                                        <label>Payment</label>
                                        <div class="val"><?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="summary-actions">
                                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn-track">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-orders">
                        <i class="fa fa-box-open"></i>
                        <p>You haven't placed any orders yet.</p>
                        <a href="shop.php" class="btn-shop-now">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Reusing general account wrapper styles */
.account-page-wrapper {
    background: #f8f9fa;
    padding: 60px 20px;
    min-height: 80vh;
}

.account-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
}

.account-sidebar {
    background: #fff;
    border-radius: 16px;
    padding: 40px 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    height: fit-content;
}

.user-profile-summary {
    text-align: center;
    padding: 0 20px 30px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 30px;
    color: #dc3545;
}

.user-profile-summary h3 {
    margin: 0;
    font-size: 18px;
    color: #111;
}

.user-profile-summary p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #888;
}

.account-nav {
    display: flex;
    flex-direction: column;
}

.account-nav a {
    padding: 15px 30px;
    text-decoration: none;
    color: #555;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: 0.3s;
}

.account-nav a i {
    width: 20px;
    color: #999;
}

.account-nav a:hover, .account-nav a.active {
    background: #fff5f5;
    color: #dc3545;
    border-left: 4px solid #dc3545;
}

.account-nav a:hover i, .account-nav a.active i {
    color: #dc3545;
}

.content-card {
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.content-card h2 { margin: 0; font-size: 24px; color: #111; }
.section-subtitle { color: #666; margin: 10px 0 0; font-size: 15px; }
.content-card hr { margin: 30px 0; border: 0; border-top: 1px solid #eee; }

/* Orders Specific Styles */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card-item {
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
    transition: 0.3s;
}

.order-card-item:hover {
    border-color: #dc354544;
    box-shadow: 0 5px 15px rgba(0,0,0,0.02);
}

.order-main-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.order-meta {
    display: flex;
    flex-direction: column;
}

.order-date {
    font-size: 13px;
    color: #888;
}

.order-number {
    font-weight: 700;
    color: #111;
    font-size: 16px;
}

.order-status-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.status-pending { background: #fff7ed; color: #c2410c; }
.status-completed { background: #f0fdf4; color: #15803d; }
.status-confirmed { background: #f0fdf4; color: #15803d; }
.status-cancelled { background: #fef2f2; color: #b91c1c; }

.order-details-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    background: #fafafa;
    padding: 20px;
    border-radius: 8px;
    align-items: center;
}

.summary-col {
    flex: 1;
    min-width: 100px;
}

.summary-col label {
    display: block;
    font-size: 11px;
    text-transform: uppercase;
    color: #999;
    font-weight: 700;
    margin-bottom: 5px;
}

.summary-col .val {
    font-weight: 700;
    color: #444;
}

.text-red { color: #dc3545 !important; }

.btn-track {
    background: #111;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: 0.3s;
}

.btn-track:hover {
    background: #333;
}

.empty-orders {
    text-align: center;
    padding: 40px 0;
}

.empty-orders i {
    font-size: 60px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-orders p {
    color: #888;
    margin-bottom: 20px;
}

.btn-shop-now {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
}

@media (max-width: 768px) {
    .order-details-summary {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    .summary-actions {
        width: 100%;
    }
    .btn-track {
        display: block;
        text-align: center;
    }
}
</style>

<?php include "includes/footer.php"; ?>