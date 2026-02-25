<?php
session_start();
require_once "config/db.php";
include "includes/header.php";

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch order details for the summary
$total_amount = 0;
if ($order_id > 0) {
    $stmt = $conn->prepare("SELECT total_amount FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $total_amount = $row['total_amount'];
    }
}
?>

<div class="success-page-container">
    <div class="success-card">
        <div class="success-icon-wrapper">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="success-glow"></div>
        </div>

        <h1 class="success-title">Order Confirmed!</h1>
        <p class="success-subtitle">Hooray! Your order has been placed successfully.</p>
        
        <div class="order-id-badge">
            <span>Order ID: #<?php echo $order_id; ?></span>
        </div>

        <div class="success-details">
            <div class="detail-row">
                <span class="detail-label">Total Payment</span>
                <span class="detail-value">£ <?php echo number_format($total_amount, 2); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value status-confirmed">Confirmed</span>
            </div>
        </div>

        <div class="success-message">
            <p>We've sent a confirmation email to you. Your melody journey starts now! We'll notify you once your items are on their way.</p>
        </div>

        <div class="success-actions">
            <a href="shop.php" class="btn-primary-success">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
            <a href="my_orders.php" class="btn-secondary-success">
                <i class="fas fa-list-alt"></i> View My Orders
            </a>
        </div>
    </div>
</div>

<style>
.success-page-container {
    min-height: 105vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 20px;
    background: #fdfdfd;
    position:relative;
}

.success-card {
    background: #ffffff;
    max-width: 550px;
    width: 80%;
    height: 100%;
    padding: 50px 40px;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.06);
    text-align: center;
    border: 1px solid #f0f0f0;
    position: relative;
    overflow: hidden;
    animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.success-icon-wrapper {
    position: relative;
    width: 80px;
    height: 100px;
    margin: 0 auto 30px;
}

.success-icon {
    width: 100px;
    height: 100px;
    background: #28a745;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 45px;
    position: relative;
    z-index: 2;
    animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
}

@keyframes scaleIn {
    from { transform: scale(0); }
    to { transform: scale(1); }
}

.success-glow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(40, 167, 69, 0.2);
    border-radius: 50%;
    filter: blur(15px);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.4); opacity: 0; }
    100% { transform: scale(1); opacity: 0.5; }
}

.success-title {
    font-size: 32px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.success-subtitle {
    color: #666;
    font-size: 16px;
    margin-bottom: 25px;
}

.order-id-badge {
    display: inline-block;
    background: #f8f9fa;
    padding: 8px 20px;
    border-radius: 50px;
    color: #444;
    font-weight: 600;
    font-size: 14px;
    border: 1px solid #eee;
    margin-bottom: 30px;
}

.success-details {
    background: #fafafa;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 30px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
}

.detail-row:not(:last-child) {
    border-bottom: 1px solid #efefef;
}

.detail-label {
    color: #888;
    font-size: 14px;
}

.detail-value {
    font-weight: 700;
    color: #222;
}

.status-confirmed {
    color: #28a745;
}

.success-message {
    color: #777;
    line-height: 1.6;
    font-size: 15px;
    margin-bottom: 35px;
}

.success-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-primary-success {
    display: block;
    background: #dc3545;
    color: white;
    padding: 16px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
}

.btn-primary-success:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
    color: white;
}

.btn-secondary-success {
    display: block;
    background: transparent;
    color: #555;
    padding: 16px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
}

.btn-secondary-success:hover {
    background: #f8f9fa;
    border-color: #ccc;
    color: #222;
}

.btn-primary-success i, .btn-secondary-success i {
    margin-right: 8px;
}

</style>

<?php include "includes/footer.php"; ?>