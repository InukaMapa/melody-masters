<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

// Handle Category Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if(!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $message = "Category '$name' added successfully!";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Handle Category Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_category'])) {
    $id = intval($_POST['cat_id']);
    $name = trim($_POST['name']);
    if(!empty($name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $message = "Category updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check if category is in use
    $check = $conn->query("SELECT id FROM products WHERE category_id = $id");
    if($check->num_rows > 0) {
        $message = "Cannot delete category: It is currently assigned to products.";
        $message_type = "error";
    } else {
        $conn->query("DELETE FROM categories WHERE id = $id");
        $message = "Category deleted successfully.";
        $message_type = "success";
    }
}

// Fetch category for editing
$edit_cat = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM categories WHERE id = $edit_id");
    $edit_cat = $res->fetch_assoc();
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Product Categories</h1>
        <p>Organize your products into meaningful groups for easier browsing.</p>
    </div>
</header>

<div class="dashboard-grid">
    <!-- List of Categories -->
    <div class="card-block">
        <div class="block-header">
            <h3>Registered Categories</h3>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Product Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $categories->fetch_assoc()): ?>
                <?php 
                    $cat_id = $row['id'];
                    $count_q = $conn->query("SELECT COUNT(*) as c FROM products WHERE category_id = $cat_id");
                    $prod_count = $count_q->fetch_assoc()['c'];
                ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><strong style="font-size:15px;"><?php echo htmlspecialchars($row['name']); ?></strong></td>
                    <td><span style="color:#666;"><?php echo $prod_count; ?> Products</span></td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a href="manage_categories.php?edit=<?php echo $row['id']; ?>" class="btn-admin" style="padding: 6px 12px; font-size:12px; background: #e0e7ff; color: #4338ca; border: none;" title="Edit">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="manage_categories.php?delete=<?php echo $row['id']; ?>" class="btn-admin" style="padding: 6px 12px; font-size:12px; background: #fef2f2; color: #b91c1c; border: none;" title="Delete" onclick="return confirm('Are you sure you want to delete this category?')">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Category Form -->
    <div class="card-block">
        <div class="block-header">
            <h3><?php echo $edit_cat ? 'Edit Category' : 'Add Category'; ?></h3>
        </div>
        <?php if ($message): ?>
            <div class="form-message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <?php if ($edit_cat): ?>
                <input type="hidden" name="cat_id" value="<?php echo $edit_cat['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Electric Guitars" value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['name']) : ''; ?>" required>
            </div>
            <button type="submit" name="<?php echo $edit_cat ? 'update_category' : 'add_category'; ?>" class="btn-admin btn-primary-admin" style="width:100%; justify-content:center;">
                <i class="fa <?php echo $edit_cat ? 'fa-save' : 'fa-plus'; ?>"></i> <?php echo $edit_cat ? 'Update Category' : 'Add Category'; ?>
            </button>
            <?php if ($edit_cat): ?>
                <a href="manage_categories.php" class="btn-admin" style="margin-top: 10px; width: 100%; justify-content: center; background: #f3f4f6; color: #374151; border: none;">
                    Cancel Edit
                </a>
            <?php endif; ?>
        </form>
        
        <div style="margin-top:30px; padding:20px; background:#f0f9ff; border-radius:12px; font-size:13px; color:#0369a1;">
            <i class="fa fa-info-circle"></i> Tip: Categories are shown in the main shop filter. Clear, concise names work best.
        </div>
    </div>
</div>

<?php 
require_once "includes/footer.php"; 
?>
