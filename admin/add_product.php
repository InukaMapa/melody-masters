<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

// Fetch brands for the dropdown
$brands_res = $conn->query("SELECT id, name FROM brands ORDER BY name ASC");
// Fetch categories for the dropdown
$categories_res = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $type = $_POST['product_type'];
    $category_id = intval($_POST['category_id']);
    $brand_id = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $description = trim($_POST['description'] ?? '');

    // Image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../assets/images/" . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, stock, product_type, category_id, brand_id, image, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdisiiss", $name, $price, $stock, $type, $category_id, $brand_id, $image, $description);

            if ($stmt->execute()) {
                $message = "Product added successfully!";
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
        $message = "Please select an image.";
        $message_type = "error";
    }
}
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Add New Product</h1>
        <p>Introduce new musical instruments or accessories to your catalog.</p>
    </div>
    <div class="header-actions">
        <a href="products.php" class="btn-admin"><i class="fa fa-arrow-left"></i> Back to Products</a>
    </div>
</header>

<div class="admin-form-container" style="max-width: 100%; background: transparent; padding: 0; box-shadow: none;">
    <?php if ($message): ?>
        <div class="form-message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
            
            <!-- Left Column: Details -->
            <div style="display: flex; flex-direction: column; gap: 30px;">
                <div class="card-block">
                    <div class="block-header">
                        <h3><i class="fa fa-info-circle" style="color: var(--primary-color);"></i> General Information</h3>
                    </div>
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Fender Stratocaster" required>
                    </div>
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label>Price (£)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                </div>

                <div class="card-block">
                    <div class="block-header">
                        <h3><i class="fa fa-tags" style="color: var(--primary-color);"></i> Classification</h3>
                    </div>
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label>Product Type</label>
                            <select name="product_type" class="form-control">
                                <option value="physical">Physical Product</option>
                                <option value="digital">Digital (PDF/Audio)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <?php while($cat_row = $categories_res->fetch_assoc()): ?>
                                    <option value="<?php echo $cat_row['id']; ?>"><?php echo htmlspecialchars($cat_row['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Brand (Optional)</label>
                        <select name="brand_id" class="form-control">
                            <option value="">-- No Specific Brand --</option>
                            <?php while($brand_row = $brands_res->fetch_assoc()): ?>
                                <option value="<?php echo $brand_row['id']; ?>"><?php echo htmlspecialchars($brand_row['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Right Column: Media -->
            <div style="display: flex; flex-direction: column; gap: 30px;">
                <div class="card-block">
                    <div class="block-header">
                        <h3><i class="fa fa-image" style="color: var(--primary-color);"></i> Media & Display</h3>
                    </div>
                    <div class="form-group">
                        <label>Product Image</label>
                        <div class="image-upload-wrapper" style="border: 2px dashed var(--border-color); border-radius: 12px; padding: 40px; text-align: center; background: #fafafa; transition: 0.2s; cursor: pointer;" onclick="document.getElementById('product-image-input').click();">
                            <div id="image-preview-container" style="display: none; margin-bottom: 20px;">
                                <img id="image-preview" src="#" alt="Preview" style="max-width: 100%; max-height: 250px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            </div>
                            <div id="upload-placeholder">
                                <i class="fa fa-cloud-upload-alt" style="font-size: 40px; color: var(--text-muted); margin-bottom: 15px; display: block;"></i>
                                <p style="margin: 0; font-weight: 700; font-size: 15px; color: var(--text-main);">Click to upload or drag & drop</p>
                                <p style="margin: 8px 0 0; font-size: 12px; color: var(--text-muted);">Recommended: 800x800px. JPG, PNG supported.</p>
                            </div>
                            <input type="file" name="image" id="product-image-input" class="form-control" style="display: none;" required onchange="previewImage(this);">
                        </div>
                    </div>
                </div>

                <div class="card-block">
                    <div class="block-header">
                        <h3><i class="fa fa-align-left" style="color: var(--primary-color);"></i> Description</h3>
                    </div>
                    <div class="form-group">
                        <label>Product features & specifications</label>
                        <textarea name="description" class="form-control" style="height:130px; resize: none;" placeholder="Provide a detailed description of the product..."></textarea>
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <button type="submit" align="center" class="btn-admin btn-primary-admin" style="width:100%; justify-content:center; padding:18px; font-size:16px; font-weight: 800; border-radius: 12px; box-shadow: 0 10px 20px rgba(225, 29, 72, 0.2); transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 30px rgba(225, 29, 72, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(225, 29, 72, 0.2)';">
                        <i class="fa fa-check-circle"></i> Create Product 
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const placeholder = document.getElementById('upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            placeholder.style.display = 'none';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Add drag and drop functionality
const uploadWrapper = document.querySelector('.image-upload-wrapper');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadWrapper.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadWrapper.addEventListener(eventName, () => {
        uploadWrapper.style.borderColor = 'var(--primary-color)';
        uploadWrapper.style.background = 'rgba(225, 29, 72, 0.02)';
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadWrapper.addEventListener(eventName, () => {
        uploadWrapper.style.borderColor = 'var(--border-color)';
        uploadWrapper.style.background = '#fafafa';
    }, false);
});

uploadWrapper.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('product-image-input').files = files;
    previewImage(document.getElementById('product-image-input'));
}, false);
</script>

<?php 
require_once "includes/footer.php"; 
?>