<?php
session_start();
require_once "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 🔥 Restrict account.php for Admins
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email, role, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<div class="account-page-wrapper">
    <div class="account-container">
        <div class="account-sidebar">
            <div class="user-profile-summary">
                <div class="profile-avatar">
                    <i class="fa fa-user"></i>
                </div>
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <nav class="account-nav">
                <a href="account.php" class="active"><i class="fa fa-user-circle"></i> Profile Details</a>
                <a href="my_orders.php"><i class="fa fa-shopping-bag"></i> My Orders</a>
                <a href="logout.php" class="logout-link-sidebar"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>

        <div class="account-content">
            <div class="content-card">
                <h2>Profile Information</h2>
                <p class="section-subtitle">Manage your personal information and account security.</p>
                <hr>
                
                <div class="profile-details-grid">
                    <div class="detail-item">
                        <label>Full Name</label>
                        <div class="detail-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Email Address</label>
                        <div class="detail-value"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Account Type</label>
                        <div class="detail-value badge-role"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Member Since</label>
                        <div class="detail-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></div>
                    </div>
                </div>

                <div class="account-actions">
                    <button class="btn-edit-profile"><i class="fa fa-edit"></i> Edit Profile</button>
                    <button class="btn-change-password"><i class="fa fa-lock"></i> Change Password</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.account-page-wrapper {
    background: #f8f9fa;
    padding: 60px 20px;
    min-height: 80vh;
}

.account-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
}

.account-sidebar {
    background: #fff;
    border-radius: 16px;
    padding: 40px 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    height: fit-content;
}

.user-profile-summary {
    text-align: center;
    padding: 0 20px 30px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 30px;
    color: #dc3545;
}

.user-profile-summary h3 {
    margin: 0;
    font-size: 18px;
    color: #111;
}

.user-profile-summary p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #888;
}

.account-nav {
    display: flex;
    flex-direction: column;
}

.account-nav a {
    padding: 15px 30px;
    text-decoration: none;
    color: #555;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: 0.3s;
}

.account-nav a i {
    width: 20px;
    color: #999;
}

.account-nav a:hover, .account-nav a.active {
    background: #fff5f5;
    color: #dc3545;
    border-left: 4px solid #dc3545;
}

.account-nav a:hover i, .account-nav a.active i {
    color: #dc3545;
}

.logout-link-sidebar:hover {
    background: #fff0f0 !important;
}

.account-content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.content-card {
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.content-card h2 {
    margin: 0;
    font-size: 24px;
    color: #111;
}

.section-subtitle {
    color: #666;
    margin: 10px 0 0;
    font-size: 15px;
}

.content-card hr {
    margin: 30px 0;
    border: 0;
    border-top: 1px solid #eee;
}

.profile-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

.detail-item label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #888;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #111;
}

.badge-role {
    display: inline-block;
    background: #eef2ff;
    color: #4f46e5;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
}

.account-actions {
    display: flex;
    gap: 15px;
}

.btn-edit-profile, .btn-change-password {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.btn-edit-profile {
    background: #111;
    color: #fff;
    border: none;
}

.btn-edit-profile:hover {
    background: #333;
}

.btn-change-password {
    background: white;
    color: #111;
    border: 1px solid #ddd;
}

.btn-change-password:hover {
    background: #f8f9fa;
    border-color: #bbb;
}

@media (max-width: 900px) {
    .account-container {
        grid-template-columns: 1fr;
    }
    .profile-details-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include "includes/footer.php"; ?>
