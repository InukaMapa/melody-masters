<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$result = $conn->query("
    SELECT orders.id, users.full_name, orders.total_amount, orders.created_at
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
");
?>

<?php include "../includes/header.php"; ?>

<h2>All Orders</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Total</th>
    <th>Date</th>
    <th>View</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['full_name']); ?></td>
    <td>$<?= number_format($row['total_amount'],2); ?></td>
    <td><?= $row['created_at']; ?></td>
    <td>
        <a href="view_order.php?id=<?= $row['id']; ?>">View</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<?php include "../includes/footer.php"; ?>