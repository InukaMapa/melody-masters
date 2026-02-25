<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

// Handle Role Updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
    $action = $_GET['action'];
    
    // Prevent self-demotion
    if ($target_id == $_SESSION['user_id'] && $action == 'demote') {
        $message = "You cannot demote yourself!";
        $message_type = "error";
    } else {
        $new_role = ($action == 'promote') ? 'admin' : 'customer';
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $target_id);
        
        if ($stmt->execute()) {
            $message = "User role updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating role: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Fetch all Admins
$admins = $conn->query("SELECT * FROM users WHERE role = 'admin' ORDER BY full_name ASC");

// Fetch other users for "Promotion" list
$other_users = $conn->query("SELECT * FROM users WHERE role != 'admin' ORDER BY full_name ASC LIMIT 10");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Administrator Management</h1>
        <p>Control access levels and manage system administrators.</p>
    </div>
    <div class="header-actions">
        <a href="add_admin.php" class="btn-admin btn-primary-admin"><i class="fa fa-user-plus"></i> Add New Admin</a>
    </div>
</header>

<!-- Full Width Admin List -->
<div class="card-block">
    <div class="block-header">
        <h3>System Administrators</h3>
    </div>
    
    <?php if ($message): ?>
        <div class="form-message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Administrator</th>
                <th>Email</th>
                <th>Role</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($admin = $admins->fetch_assoc()): ?>
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="item-avatar" style="background: rgba(225, 29, 72, 0.1); color: var(--primary-color);">
                            <i class="fa fa-user-shield"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;"><?php echo htmlspecialchars($admin['full_name']); ?></div>
                            <div style="font-size:11px; color:#888;">ID: #<?php echo $admin['id']; ?></div>
                        </div>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                <td><span class="status-pills pills-success" style="background: #f0fdf4; color: #15803d;">Administrator</span></td>
                <td style="text-align:right;">
                    <?php if ($admin['id'] != $_SESSION['user_id']): ?>
                        <a href="?action=demote&id=<?php echo $admin['id']; ?>" 
                           class="btn-admin" 
                           style="color: var(--danger); border-color: #fee2e2; background: #fef2f2;"
                           onclick="return confirm('Are you sure you want to demote this admin?')">
                            <i class="fa fa-user-minus"></i> Revoke Access
                        </a>
                    <?php else: ?>
                        <span style="font-size:12px; color:#888; font-style:italic;">(You)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once "includes/footer.php"; ?>
