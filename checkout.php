<?php
session_start();
include "includes/header.php";

if (empty($_SESSION['cart'])) {
    echo "<p style='padding:60px;'>Your cart is empty.</p>";
    include "includes/footer.php";
    exit();
}

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Shipping rule
$shipping = 0;
if ($subtotal <= 100) {
    $shipping = 10; // flat shipping cost
}

$total = $subtotal + $shipping;
?>

<section class="cart-section">
    <h2>Checkout</h2>

    <div class="cart-table-wrapper">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>
                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <div class="cart-total">
        <p>Subtotal: <strong>$<?php echo number_format($subtotal, 2); ?></strong></p>
        <p>Shipping: <strong>
            <?php echo $shipping > 0 ? "$" . number_format($shipping, 2) : "Free"; ?>
        </strong></p>
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
    </div>

    <div style="margin-top:20px;">
        <a href="place_order.php" class="continue-btn">Place Order</a>
    </div>

</section>

<?php include "includes/footer.php"; ?>