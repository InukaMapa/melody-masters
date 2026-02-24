<?php
session_start();
require_once "config/db.php";

// Must login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cart empty check
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

$subtotal = 0;

// Calculate subtotal
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Shipping rule
$shipping = 0;
if ($subtotal <= 100) {
    $shipping = 10;
}

$total = $subtotal + $shipping;

// 🔥 Start transaction (very important)
$conn->begin_transaction();

try {

    // 1️⃣ Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 2️⃣ Insert order items
    foreach ($cart as $item) {

        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt->execute();
        $stmt->close();

        // 3️⃣ Reduce stock
        $update = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update->bind_param("ii", $quantity, $product_id);
        $update->execute();
        $update->close();
    }

    // Commit transaction
    $conn->commit();

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: my_orders.php?success=1");
    exit();

} catch (Exception $e) {

    $conn->rollback();
    echo "Order failed: " . $e->getMessage();
}
?>