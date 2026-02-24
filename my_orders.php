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
?>

<section class="cart-section">
    <h2>My Orders</h2>

    <?php if ($result->num_rows > 0): ?>

        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Shipping</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>$<?php echo number_format($order['shipping_cost'], 2); ?></td>
                        <td><?php echo ucfirst($order['status']); ?></td>
                        <td><?php echo $order['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>

</section>

<?php include "includes/footer.php"; ?>