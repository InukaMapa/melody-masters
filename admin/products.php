<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<?php include "../includes/header.php"; ?>

<section class="cart-section">
    <h2>Manage Products</h2>

    <a href="add_product.php" class="continue-btn" style="margin-bottom:20px; display:inline-block;">
        + Add New Product
    </a>

    <div class="cart-table-wrapper">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo ucfirst($row['product_type']); ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="view-btn">Edit</a>
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="remove-btn">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>
        </table>
    </div>
</section>

<?php include "../includes/footer.php"; ?>