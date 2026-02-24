<?php

include "includes/header.php";
?>

<section class="cart-section">
    <h2>Your Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>

        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php 
                $grand_total = 0;
                foreach ($_SESSION['cart'] as $id => $item): 
                    $total = $item['price'] * $item['quantity'];
                    $grand_total += $total;
                ?>

                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                        <td>
                            <a href="remove_from_cart.php?id=<?php echo $id; ?>" class="remove-btn">
                                Remove
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <div class="cart-total">
            <h3>Grand Total: $<?php echo number_format($grand_total, 2); ?></h3>
            <div style="margin-top:20px; text-align:right;">
    <a href="checkout.php" class="continue-btn">
        Proceed to Checkout
    </a>
</div>
        </div>

    <?php else: ?>

        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="shop.php" class="continue-btn">Continue Shopping</a>
        </div>

    <?php endif; ?>

</section>

<?php include "includes/footer.php"; ?>