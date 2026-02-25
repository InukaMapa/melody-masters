<?php
session_start();
require_once "config/db.php";

if (!isset($_POST['product_id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = intval($_POST['product_id']);

$stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: shop.php");
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product already in cart
$found = false;

foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product['id']) {
        $item['quantity'] += 1;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

// Redirect to cart page
header("Location: cart.php");
exit();