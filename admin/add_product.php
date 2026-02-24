<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $type = $_POST['product_type'];

    // Image upload
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

        $stmt = $conn->prepare("INSERT INTO products (name, price, stock, product_type, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $name, $price, $stock, $type, $image);

        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $message = "Database error!";
        }

        $stmt->close();
    } else {
        $message = "Image upload failed!";
    }
}
?>

<?php include "../includes/header.php"; ?>

<h2>Add Product</h2>

<?php if ($message) echo "<p>$message</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Name</label>
    <input type="text" name="name" required>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required>

    <label>Stock</label>
    <input type="number" name="stock" required>

    <label>Type</label>
    <select name="product_type">
        <option value="instrument">Instrument</option>
        <option value="album">Album</option>
        <option value="accessory">Accessory</option>
    </select>

    <label>Image</label>
    <input type="file" name="image" required>

    <button type="submit">Add Product</button>
</form>

<?php include "../includes/footer.php"; ?>