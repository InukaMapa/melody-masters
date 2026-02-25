<?php
require_once "includes/header.php";

$query = "
    SELECT 
        u.*, 
        COUNT(o.id) as order_count, 
        SUM(o.total_amount) as total_spent 
    FROM users u
    LEFT JOIN orders o ON u.id = o.user_id AND o.status != 'cancelled'
    WHERE u.role = 'customer'
    GROUP BY u.id
    ORDER BY u.created_at DESC";
$result = $conn->query($query);
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Customer Directory</h1>
        <p>View and manage all registered customers of Melody Masters.</p>
    </div>
</header>

<div class="card-block">
    <div class="block-header">
        <h3>Active Customers</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Email</th>
                <th>Join Date</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="item-avatar" style="background: rgba(0,0,0,0.05); color: #666;"><i class="fa fa-user"></i></div>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($row['full_name']); ?></div>
                    </div>
                </td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td><?php echo $row['order_count'] ?: 0; ?> Orders</td>
                <td style="font-weight:700;">£<?php echo number_format($row['total_spent'] ?: 0, 2); ?></td>
                <td>
                    <a href="view_customer.php?id=<?php echo $row['id']; ?>" class="view-all">View Details</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once "includes/footer.php"; ?>
