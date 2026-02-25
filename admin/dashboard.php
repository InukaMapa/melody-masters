<?php
require_once "includes/header.php";

// Fetch Stats
// 1. Revenue
$revenue_query = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
$total_revenue = $revenue_query->fetch_assoc()['total'] ?? 0;

$today_revenue_query = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'");
$today_revenue = $today_revenue_query->fetch_assoc()['total'] ?? 0;

// 2. Orders
$orders_count_query = $conn->query("SELECT COUNT(*) as count FROM orders");
$total_orders = $orders_count_query->fetch_assoc()['count'] ?? 0;

$pending_orders_query = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
$pending_orders = $pending_orders_query->fetch_assoc()['count'] ?? 0;

$today_orders_query = $conn->query("SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()");
$today_orders = $today_orders_query->fetch_assoc()['count'] ?? 0;

// 3. Products
$products_count_query = $conn->query("SELECT COUNT(*) as count FROM products");
$total_products = $products_count_query->fetch_assoc()['count'] ?? 0;

$low_stock_query = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock < 5");
$low_stock_count = $low_stock_query->fetch_assoc()['count'] ?? 0;

// 4. Customers
$customers_count_query = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
$total_customers = $customers_count_query->fetch_assoc()['count'] ?? 0;

$new_customers_query = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer' AND DATE(created_at) = CURDATE()");
$new_customers_today = $new_customers_query->fetch_assoc()['count'] ?? 0;

// Recent Orders
$recent_orders = $conn->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 7");

// Top Selling (Calculated from order_items)
$top_selling = $conn->query("SELECT p.name, p.price, p.image, p.stock, SUM(oi.quantity) as sold 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             GROUP BY oi.product_id 
                             ORDER BY sold DESC LIMIT 6");

// Low Stock Items
$low_stock_items = $conn->query("SELECT name, stock FROM products WHERE stock < 10 ORDER BY stock ASC LIMIT 5");

// New Customers List
$recent_users = $conn->query("SELECT full_name, email, created_at FROM users WHERE role = 'customer' ORDER BY created_at DESC LIMIT 5");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1> Hi! <?php echo explode(' ', $_SESSION['user_name'])[0]; ?></h1>
        <p><?php echo date('l, d F Y'); ?> · <?php echo date('h:i A'); ?></p>
    </div>
    <div class="header-actions">
        <a href="add_product.php" class="btn-admin btn-primary-admin"><i class="fa fa-plus"></i> Add Product</a>
        <a href="orders.php" class="btn-admin"><i class="fa fa-shopping-bag"></i> Orders</a>
        <a href="products.php" class="btn-admin"><i class="fa fa-box"></i> Products</a>
    </div>
</header>



<!-- Detailed Stats Row -->
<div class="stats-grid-large">
    <div class="stat-card-detailed" style="border-left: 4px solid #ef4444;">
        <div class="icon" style="color: #ef4444;"><i class="fa fa-wallet"></i></div>
        <h4>Total Revenue</h4>
        <div class="value">£<?php echo number_format($total_revenue, 2); ?></div>
        <div class="footer">Total Lifetime Revenue</div>
    </div>
    <div class="stat-card-detailed" style="border-left: 4px solid #3b82f6;">
        <div class="icon"><i class="fa fa-file-invoice"></i></div>
        <h4>Total Orders</h4>
        <div class="value"><?php echo $total_orders; ?></div>
        <div class="footer <?php echo $pending_orders > 0 ? 'low' : ''; ?>"><?php echo $pending_orders; ?> pending attention</div>
    </div>
    <div class="stat-card-detailed" style="border-left: 4px solid #8b5cf6;">
        <div class="icon"><i class="fa fa-box-open"></i></div>
        <h4>Total Products</h4>
        <div class="value"><?php echo $total_products; ?></div>
        <div class="footer <?php echo $low_stock_count > 0 ? 'low' : ''; ?>"><?php echo $low_stock_count; ?> running low</div>
    </div>
    <div class="stat-card-detailed" style="border-left: 4px solid #10b981;">
        <div class="icon"><i class="fa fa-users"></i></div>
        <h4>Total Customers</h4>
        <div class="value"><?php echo $total_customers; ?></div>
        <div class="footer high">+<?php echo $new_customers_today; ?> joined today</div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Recent Orders -->
    <div class="card-block">
        <div class="block-header">
            <h3>Recent Orders</h3>
            <a href="orders.php" class="view-all">View all →</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while($order = $recent_orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                    <td>£<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <span class="status-pills pills-<?php echo strtolower($order['status']); ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                    <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                    <td><a href="view_order.php?id=<?php echo $order['id']; ?>" class="view-all">View</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Right Column Blocks -->
    <div class="column-blocks" style="display:flex; flex-direction:column; gap:30px;">
        <!-- Order Status Summary -->
        <div class="card-block">
            <div class="block-header">
                <h3>Order Status</h3>
                <a href="orders.php" class="view-all">Manage →</a>
            </div>
            <div class="status-breakdown">
                <?php
                $status_types = ['Pending', 'Confirmed', 'Shipped', 'Completed', 'Cancelled'];
                foreach($status_types as $st):
                    $st_low = strtolower($st);
                    $count_q = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status = '$st_low'");
                    $count = $count_q->fetch_assoc()['c'] ?? 0;
                    $percent = $total_orders > 0 ? ($count / $total_orders) * 100 : 0;
                ?>
                <div style="margin-bottom:15px;">
                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:5px;">
                        <span style="font-weight:600;"><?php echo $st; ?></span>
                        <span style="color:#888;"><?php echo $count; ?> (<?php echo round($percent); ?>%)</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width:<?php echo $percent; ?>%; background: <?php 
                            if($st == 'Pending') echo '#f59e0b';
                            elseif($st == 'Cancelled') echo '#ef4444';
                            else echo '#10b981';
                        ?>;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="card-block">
            <div class="block-header">
                <h3>Low Stock <span style="background:var(--primary-color); color:white; padding:2px 8px; border-radius:10px; font-size:11px;"><?php echo $low_stock_count; ?></span></h3>
                <a href="products.php" class="view-all">Manage →</a>
            </div>
            <?php while($item = $low_stock_items->fetch_assoc()): ?>
            <div class="list-item">
                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="item-val warning"><?php echo $item['stock']; ?> left</div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- New Customers -->
        <div class="card-block">
            <div class="block-header">
                <h3>New Customers</h3>
                <a href="customers.php" class="view-all">View all →</a>
            </div>
            <?php while($user = $recent_users->fetch_assoc()): ?>
            <div class="list-item">
                <div class="item-info">
                    <div class="item-avatar"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
                    <div>
                        <div class="item-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        <div class="item-meta"><?php echo $user['email']; ?></div>
                    </div>
                </div>
                <div class="item-meta"><?php echo date('d M', strtotime($user['created_at'])); ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Top Selling Products -->
<div class="card-block" style="margin-top:30px;">
    <div class="block-header">
        <h3>Top Selling Products</h3>
        <a href="products.php" class="view-all">View all →</a>
    </div>
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px;">
        <?php while($top = $top_selling->fetch_assoc()): ?>
        <div class="list-item" style="border: 1px solid #f0f0f0; border-radius:12px; padding:15px;">
            <div class="item-info">
                <img src="../assets/images/<?php echo $top['image']; ?>" style="width:50px; height:50px; border-radius:8px; object-fit:cover;">
                <div>
                    <div class="item-name"><?php echo htmlspecialchars($top['name']); ?></div>
                    <div class="item-meta"><?php echo $top['sold']; ?> sold · <span style="color:<?php echo $top['stock'] < 5 ? 'var(--danger)' : 'var(--success)'; ?>"><?php echo $top['stock']; ?> in stock</span></div>
                </div>
            </div>
            <div class="item-val">£<?php echo number_format($top['price'], 2); ?></div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php 
require_once "includes/footer.php"; 
?>