<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

if (!isset($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$id = intval($_GET['id']);

// Get customer details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'customer'");
$stmt->bind_param("i", $id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

if (!$customer) {
    header("Location: customers.php");
    exit();
}

// Get order history
$orders_q = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders_q->bind_param("i", $id);
$orders_q->execute();
$orders = $orders_q->get_result();

// Stats
$stats_q = $conn->query("SELECT COUNT(id) as count, SUM(total_amount) as spent FROM orders WHERE user_id = $id AND status != 'cancelled'");
$stats = $stats_q->fetch_assoc();
if (!$stats) {
    $stats = ['count' => 0, 'spent' => 0];
}
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Customer Details</h1>
        <p>Viewing profile and activity for <?php echo htmlspecialchars($customer['full_name']); ?></p>
    </div>
    <div class="header-actions">
        <a href="customers.php" class="btn-admin"><i class="fa fa-arrow-left"></i> Back to Directory</a>
    </div>
</header>

<div class="dashboard-grid">
    <!-- Profile Card -->
    <div class="column">
        <div class="card-block">
            <div class="block-header" style="margin-bottom: 30px; text-align: center;">
                <div style="width: 80px; height: 80px; background: rgba(0,0,0,0.05); color: #666; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                    <i class="fa fa-user"></i>
                </div>
                <h2 style="margin: 0; font-size: 24px;"><?php echo htmlspecialchars($customer['full_name']); ?></h2>
                <p style="margin: 5px 0 0; color: #888; font-size: 14px;"><?php echo htmlspecialchars($customer['email']); ?></p>
                <div style="margin-top: 15px;">
                    <span class="status-pills pills-success" style="background: #f0fdf4; color: #15803d;">Active Customer</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-top: 1px solid #eee; padding-top: 25px;">
                <div>
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Customer ID</label>
                    <div style="font-weight:600;">#<?php echo $customer['id']; ?></div>
                </div>
                <div>
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Member Since</label>
                    <div style="font-weight:600;"><?php echo date('d M Y', strtotime($customer['created_at'])); ?></div>
                </div>
                <div>
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Total Orders</label>
                    <div style="font-weight:600;"><?php echo $stats['count'] ?? 0; ?> Orders</div>
                </div>
                <div>
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Lifetime Value</label>
                    <div style="font-weight:700; color: var(--primary-color);">£<?php echo number_format($stats['spent'] ?? 0, 2); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="column" style="flex: 2;">
        <div class="card-block">
            <div class="block-header">
                <h3>Order History</h3>
            </div>
            
            <?php if ($orders->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <span class="status-pills pills-<?php echo strtolower($order['status']); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td style="font-weight: 600;">£<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>
                            <a href="view_order.php?id=<?php echo $order['id']; ?>" class="view-all">View</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #888;">
                    <i class="fa fa-shopping-bag" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.2;"></i>
                    No orders placed yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
