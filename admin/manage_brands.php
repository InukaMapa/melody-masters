<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

// Handle brand addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_brand'])) {
    $name = trim($_POST['name']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO brands (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $image);
            if ($stmt->execute()) {
                $message = "Brand '$name' added successfully!";
                $message_type = "success";
            } else {
                $message = "Database error: " . $conn->error;
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "Image upload failed!";
            $message_type = "error";
        }
    } else {
        $message = "Please select a valid logo image.";
        $message_type = "error";
    }
}

// Handle brand deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check if brand is in use (optional but good)
    $stmt = $conn->prepare("DELETE FROM brands WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Brand deleted successfully.";
        $message_type = "success";
    } else {
        $message = "Error deleting brand.";
        $message_type = "error";
    }
    $stmt->close();
}

$brands = $conn->query("SELECT * FROM brands ORDER BY id DESC");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Brand Management</h1>
        <p>Maintain your partnerships and store brand identifiers.</p>
    </div>
</header>

<div class="dashboard-grid">
    <!-- Brand List -->
    <div class="card-block">
        <div class="block-header">
            <h3>Registered Brands</h3>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Brand Name</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($brands->num_rows > 0): ?>
                    <?php while ($row = $brands->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid #eee;">
                        </td>
                        <td><strong style="font-size: 15px;"><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td style="text-align:right;">
                            <a href="manage_brands.php?delete=<?php echo $row['id']; ?>" class="view-all" style="color:var(--danger);" onclick="return confirm('Are you sure you want to delete this brand?')">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center; padding:40px; color:#888;">No brands found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Brand form -->
    <div class="card-block">
        <div class="block-header">
            <h3>Add New Brand</h3>
        </div>
        
        <?php if ($message): ?>
            <div class="form-message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Brand Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Fender, Gibson" required>
            </div>
            <div class="form-group">
                <label>Brand Logo</label>
                <div style="padding: 20px; border: 2px dashed #eee; border-radius: 12px; background: #fafafa; text-align:center;">
                    <i class="fa fa-image" style="font-size: 30px; color: #ccc; display:block; margin-bottom:10px;"></i>
                    <input type="file" name="image" required style="font-size: 12px;">
                </div>
            </div>
            <button type="submit" name="add_brand" class="btn-admin btn-primary-admin" style="width:100%; justify-content:center; padding:15px; font-weight:700; border-radius:10px;">
                <i class="fa fa-plus"></i> Register Brand
            </button>
        </form>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
