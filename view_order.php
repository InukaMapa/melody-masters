<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$order_id = intval($_GET['id']);

$result = $conn->query("
    SELECT products.name, order_items.quantity, order_items.price
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = $order_id
");
?>

<h2>Order Details</h2>

<table>
<tr>
    <th>Product</th>
    <th>Quantity</th>
    <th>Price</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= $row['quantity']; ?></td>
    <td>$<?= number_format($row['price'],2); ?></td>
</tr>
<?php endwhile; ?>

</table>