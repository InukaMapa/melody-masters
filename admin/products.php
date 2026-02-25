<?php
require_once "includes/header.php";

$result = $conn->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Inventory Management</h1>
        <p>Manage your product catalog, stock levels, and categories.</p>
    </div>
    <div class="header-actions">
        <a href="add_product.php" class="btn-admin btn-primary-admin"><i class="fa fa-plus"></i> Add New Product</a>
    </div>
</header>

<div class="card-block">
    <div class="block-header">
        <h3>All Products</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" style="width:40px; height:40px; border-radius:6px; object-fit:cover;">
                </td>
                <td>
                    <div style="font-weight:600;"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div style="font-size:11px; color:#888;">ID: #<?php echo $row['id']; ?></div>
                </td>
                <td><?php echo htmlspecialchars($row['category_name'] ?: 'Uncategorized'); ?></td>
                <td>£<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <div style="font-weight:600; color:<?php echo $row['stock'] < 5 ? 'var(--danger)' : 'inherit'; ?>">
                        <?php echo $row['stock']; ?>
                    </div>
                </td>
                <td>
                    <?php if($row['stock'] > 0): ?>
                        <span class="status-pills pills-success">In Stock</span>
                    <?php else: ?>
                        <span class="status-pills pills-pending" style="background:#fef2f2; color:#ef4444;">Out of Stock</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display:flex; gap:8px;">
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-admin" style="padding: 6px 12px; font-size:12px; background: #e0e7ff; color: #4338ca; border: none;" title="Edit">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn-admin" style="padding: 6px 12px; font-size:12px; background: #fef2f2; color: #b91c1c; border: none;" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once "includes/footer.php"; ?>