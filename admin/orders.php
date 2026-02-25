<?php
require_once "includes/header.php";

$result = $conn->query("
    SELECT o.*, u.full_name as customer_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Order Management</h1>
        <p>Track, update, and manage all customer orders.</p>
    </div>
</header>

<div class="card-block">
    <div class="block-header">
        <h3>All Orders</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Payment</th>
                <th>Total Amout</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><strong>#<?php echo $row['id']; ?></strong></td>
                <td>
                    <div style="font-weight:600;"><?php echo htmlspecialchars($row['full_name'] ?: $row['customer_name']); ?></div>
                </td>
                <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method'] ?? 'COD'); ?></td>
                <td style="font-weight:700; color:var(--primary-color);">£<?php echo number_format($row['total_amount'], 2); ?></td>
                <td>
                    <span class="status-pills pills-<?php echo strtolower($row['status']); ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
                <td>
                    <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn-admin" style="padding: 6px 12px; font-size:12px;">View Details</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once "includes/footer.php"; ?>