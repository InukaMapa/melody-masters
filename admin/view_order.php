<?php
require_once "includes/header.php";

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

// Get order details
$order_query = $conn->prepare("SELECT o.*, u.full_name as user_fullname FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order = $order_query->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='card-block'><h2>Order not found.</h2></div>";
    require_once "includes/footer.php";
    exit();
}

// Get order items
$items_query = $conn->prepare("
    SELECT products.name, products.image, order_items.quantity, order_items.price
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");
$items_query->bind_param("i", $order_id);
$items_query->execute();
$items = $items_query->get_result();

// Update status if posted
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if($stmt->execute()) {
        $order['status'] = $new_status;
        echo "<div class='form-message success' style='margin-bottom:20px;'>Order status updated to $new_status</div>";
    }
}
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Order #<?php echo $order['id']; ?> Details</h1>
        <p>Placed on <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
    </div>
    <div class="header-actions">
        <a href="orders.php" class="btn-admin"><i class="fa fa-arrow-left"></i> Back to Orders</a>
    </div>
</header>

<div class="dashboard-grid">
    <!-- Left: Order Items -->
    <div class="column">
        <div class="card-block">
            <div class="block-header">
                <h3>Customer Information</h3>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                <div class="detail-item">
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Customer Name</label>
                    <div style="font-weight:600;"><?php echo htmlspecialchars($order['full_name'] ?: $order['user_fullname']); ?></div>
                </div>
                <div class="detail-item">
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Email</label>
                    <div><?php echo htmlspecialchars($order['email']); ?></div>
                </div>
                <div class="detail-item">
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Phone</label>
                    <div><?php echo htmlspecialchars($order['phone']); ?></div>
                </div>
                <div class="detail-item">
                    <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Payment Method</label>
                    <div style="font-weight:700;"><?php echo htmlspecialchars($order['payment_method']); ?></div>
                </div>
            </div>
            
            <div style="margin-top:25px;">
                <label style="display:block; font-size:11px; text-transform:uppercase; color:#888; font-weight:700; margin-bottom:5px;">Shipping Address</label>
                <div style="line-height:1.6; color:#444;">
                    <?php echo htmlspecialchars($order['address_line_1']); ?><br>
                    <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['postcode']); ?><br>
                    <?php echo htmlspecialchars($order['country']); ?>
                </div>
            </div>
        </div>

        <div class="card-block" style="margin-top:30px;">
            <div class="block-header">
                <h3>Order Items</h3>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    while($item = $items->fetch_assoc()): 
                        $item_total = $item['price'] * $item['quantity'];
                        $subtotal += $item_total;
                    ?>
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <img src="../assets/images/<?php echo $item['image']; ?>" style="width:40px; height:40px; border-radius:6px; object-fit:cover;">
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </div>
                        </td>
                        <td>£<?php echo number_format($item['price'], 2); ?></td>
                        <td>x<?php echo $item['quantity']; ?></td>
                        <td style="font-weight:600;">£<?php echo number_format($item_total, 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div style="margin-top:20px; border-top:1px solid #eee; padding-top:20px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:14px;">
                    <span style="color:#666;">Subtotal</span>
                    <span>£<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:14px;">
                    <span style="color:#666;">Shipping</span>
                    <span>£<?php echo number_format($order['shipping_cost'], 2); ?></span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:20px; font-weight:800; color:var(--primary-color);">
                    <span>Total</span>
                    <span>£<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Status Control -->
    <div class="column">
        <div class="card-block">
            <div class="block-header">
                <h3>Order Status</h3>
            </div>
            <div style="margin-bottom:20px;">
                Current Status: <span class="status-pills pills-<?php echo strtolower($order['status']); ?>"><?php echo ucfirst($order['status']); ?></span>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label>Update Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $order['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <button type="submit" name="update_status" class="btn-admin btn-primary-admin" style="width:100%; justify-content:center;">Update Status</button>
            </form>
        </div>

        <div class="card-block" style="margin-top:30px; background:#fff7ed; border:1px solid #fdba74;">
            <div class="block-header">
                <h3>Order Note</h3>
            </div>
            <p style="font-size:14px; line-height:1.6; color:#7c2d12;">
                <?php echo !empty($order['order_note']) ? nl2br(htmlspecialchars($order['order_note'])) : "No notes provided by the customer."; ?>
            </p>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
