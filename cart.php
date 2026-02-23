<?php
session_start();
include "includes/header.php";
?>

<section class="products">
    <h2>Your Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>

        <table style="width:100%; background:white; padding:20px; border-collapse: collapse;">
            <tr style="border-bottom:1px solid #ddd;">
                <th align="left">Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php 
            $grand_total = 0;
            foreach ($_SESSION['cart'] as $id => $item): 
                $total = $item['price'] * $item['quantity'];
                $grand_total += $total;
            ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                    <td>
                        <a href="remove_from_cart.php?id=<?php echo $id; ?>" style="color:red;">
                            Remove
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="3" align="right"><strong>Grand Total:</strong></td>
                <td><strong>$<?php echo number_format($grand_total, 2); ?></strong></td>
                <td></td>
            </tr>
        </table>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

</section>

<?php include "includes/footer.php"; ?>