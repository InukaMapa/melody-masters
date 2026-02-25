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

<div class="checkout-wrapper">
    <div class="checkout-container-v2">
        <div class="checkout-header">
            <h2>Checkout</h2>
            <p>Please fill in your details to complete the order</p>
        </div>

        <form action="place_order.php" method="POST" class="checkout-grid-v2">
            <!-- Left Column: Billing -->
            <div class="billing-card">
                <div class="card-title">Billing Details</div>
                
                <div class="input-row">
                    <div class="input-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required placeholder="Enter full name">
                    </div>
                </div>

                <div class="input-grid">
                    <div class="input-field">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="Email address">
                    </div>
                    <div class="input-field">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required placeholder="Phone">
                    </div>
                </div>

                <div class="input-grid">
                    <div class="input-field">
                        <label>Address</label>
                        <input type="text" name="address_line_1" required placeholder="Street address">
                    </div>
                    <div class="input-field">
                        <label>City</label>
                        <input type="text" name="city" required placeholder="City">
                    </div>
                </div>

                <div class="input-grid">
                    <div class="input-field">
                        <label>Postcode</label>
                        <input type="text" name="postcode" required placeholder="Postcode">
                    </div>
                    <div class="input-field">
                        <label>Country</label>
                        <select name="country" required>
                            <option value="UK">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="LK">Sri Lanka</option>
                        </select>
                    </div>
                </div>

                <div class="input-field">
                    <label>Order Notes (optional)</label>
                    <textarea name="order_note" placeholder="Instructions for delivery..."></textarea>
                </div>
            </div>

            <!-- Right Column: Summary & Payment -->
            <div class="summary-card-v2">
                <div class="card-title">Order Summary</div>
                
                <div class="summary-items-list">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="s-item">
                            <span class="s-name"><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="s-qty">x<?php echo $item['quantity']; ?></span>
                            <span class="s-price">£<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-totals-v2">
                    <div class="s-total-row">
                        <span>Grand Total</span>
                        <span class="final-price">£<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <div class="payment-card-v2">
                    <div class="card-title-small">Payment Method</div>
                    <div class="payment-options">
                        <label class="p-option">
                            <input type="radio" name="payment_method" value="COD" checked>
                            <span><i class="fas fa-money-bill-wave"></i> Cash on Delivery</span>
                        </label>
                        <label class="p-option">
                            <input type="radio" name="payment_method" value="Bank">
                            <span><i class="fas fa-university"></i> Bank Transfer</span>
                        </label>
                        <label class="p-option">
                            <input type="radio" name="payment_method" value="PayPal">
                            <span><i class="fab fa-paypal"></i> PayPal</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="place-order-btn-final">PLACE ORDER</button>
                <p class="secure-checkout-text"><i class="fas fa-lock"></i> Secure SSL Encryption</p>
            </div>
        </form>
    </div>
</div>

<style>
.checkout-wrapper {
    flex: 1;
    background: #f8f9fa;
    padding: 40px 20px;
    display: flex;
    justify-content: center;
}

.checkout-container-v2 {
    width: 100%;
    max-width: 1000px;
}

.checkout-header {
    margin-bottom: 25px;
}

.checkout-header h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 5px;
}

.checkout-header p {
    color: #666;
    font-size: 15px;
}

.checkout-grid-v2 {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 30px;
    align-items: start;
}

.billing-card, .summary-card-v2 {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: 1px solid #eee;
}

.card-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 25px;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 12px;
}

.input-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.input-row {
    margin-bottom: 20px;
}

.input-field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.input-field input, .input-field select, .input-field textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    background: #fafafa;
    transition: all 0.2s;
}

.input-field input:focus, .input-field select:focus, .input-field textarea:focus {
    border-color: #dc3545;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.05);
}

.input-field textarea {
    height: 80px;
    resize: none;
}

/* Summary Styles */
.summary-items-list {
    margin-bottom: 25px;
    max-height: 250px;
    overflow-y: auto;
    padding-right: 10px;
}

.s-item {
    display: flex;
    justify-content: space-between;
    font-size: 15px;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #eee;
}

.s-name {
    flex: 1;
    color: #444;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 15px;
}

.s-qty {
    color: #888;
    margin-right: 15px;
}

.s-price {
    font-weight: 700;
    color: #111;
}

.summary-totals-v2 {
    padding: 20px 0;
    border-top: 2px solid #f0f0f0;
    margin-bottom: 25px;
}

.s-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.s-total-row span:first-child {
    font-weight: 600;
    color: #222;
    font-size: 16px;
}

.final-price {
    font-size: 1.6rem;
    font-weight: 800;
    color: #dc3545;
}

.payment-card-v2 {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
}

.card-title-small {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 15px;
}

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.p-option {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-size: 15px;
    color: #444;
}

.p-option input {
    accent-color: #dc3545;
    width: 18px;
    height: 18px;
}

.place-order-btn-final {
    width: 100%;
    background: #dc3545;
    color: white;
    padding: 20px;
    border: none;
    border-radius: 10px;
    font-weight: 800;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
}

.place-order-btn-final:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
}

.secure-checkout-text {
    text-align: center;
    font-size: 13px;
    color: #aaa;
    margin-top: 20px;
}

@media (max-width: 900px) {
    .checkout-grid-v2 {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include "includes/footer.php"; ?>
