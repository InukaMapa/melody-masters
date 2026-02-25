<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);
$product_q = $conn->prepare("SELECT * FROM products WHERE id = ?");
$product_q->bind_param("i", $id);
$product_q->execute();
$product = $product_q->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);
    
    $image_name = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category_id = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdisssi", $name, $price, $stock, $category_id, $description, $image_name, $id);
    
    if ($stmt->execute()) {
        $message = "Product updated successfully!";
        $message_type = "success";
        // Refresh product data
        $product['name'] = $name;
        $product['price'] = $price;
        $product['stock'] = $stock;
        $product['category_id'] = $category_id;
        $product['description'] = $description;
        $product['image'] = $image_name;
    } else {
        $message = "Error updating product.";
        $message_type = "error";
    }
}
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Edit Product</h1>
        <p>Modify details for "<?php echo htmlspecialchars($product['name']); ?>"</p>
    </div>
    <div class="header-actions">
        <a href="products.php" class="view-all"><i class="fa fa-arrow-left"></i> Back to Products</a>
    </div>
</header>

<?php if ($message): ?>
    <div class="form-message <?php echo $message_type; ?>" style="margin-bottom: 30px;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="admin-layout-grid" style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 30px;">
        <!-- Left Section -->
        <div class="card-block">
            <div class="block-header" style="margin-bottom: 25px;">
                <h3>Product Details</h3>
            </div>
            
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" style="height: 150px; resize: none;"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="grid-cols-2">
                <div class="form-group">
                    <label>Price (£)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="column-blocks" style="display:flex; flex-direction:column; gap:30px;">
            <div class="card-block">
                <div class="block-header" style="margin-bottom: 20px;">
                    <h3>Organization</h3>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <?php 
                        $cats = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                        while($cat = $cats->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="card-block">
                <div class="block-header" style="margin-bottom: 20px;">
                    <h3>Product Media</h3>
                </div>
                <div style="text-align:center; padding: 20px; border: 2px dashed #eee; border-radius: 12px; background: #fafafa;">
                    <img src="../assets/images/<?php echo $product['image']; ?>" style="max-width: 100%; height: 150px; border-radius: 8px; object-fit: cover; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <input type="file" name="image" class="form-control" style="margin-top: 10px;">
                    <p style="font-size: 11px; color:#888; margin-top: 10px;">Upload new image to replace current one.</p>
                </div>
            </div>

            <button type="submit" name="update_product" class="btn-admin btn-primary-admin" style="width: 100%; padding: 18px; justify-content: center; font-size: 16px; font-weight: 700; border-radius: 12px; box-shadow: 0 10px 20px rgba(225, 29, 72, 0.15);">
                <i class="fa fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<?php require_once "includes/footer.php"; ?>
