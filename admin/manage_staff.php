<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

// Handle Role Updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
    $action = $_GET['action'];
    
    // Prevent self-role-change if admin (safety, though staff shouldn't reach here easily)
    if ($target_id == $_SESSION['user_id'] && $_SESSION['user_role'] == 'admin') {
        // Allow admin to demote themselves if there's another admin? 
        // For staff page, we only care about 'staff' role.
    }
    
    $new_role = ($action == 'promote') ? 'staff' : 'customer';
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $target_id);
    
    if ($stmt->execute()) {
        $message = "Staff status updated!";
        $message_type = "success";
    } else {
        $message = "Error: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Fetch Staff Members
$staff_members = $conn->query("SELECT * FROM users WHERE role = 'staff' ORDER BY full_name ASC");

// Fetch others for promotion
$other_users = $conn->query("SELECT * FROM users WHERE role = 'customer' ORDER BY full_name ASC LIMIT 10");
?>

<header class="dashboard-header">
    <div class="greeting">
        <h1>Staff Directory</h1>
        <p>Manage store staff accounts and permissions.</p>
    </div>
</header>

<div class="admin-layout-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Staff List -->
    <div class="card-block">
        <div class="block-header">
            <h3>Registered Staff</h3>
        </div>
        
        <?php if ($message): ?>
            <div class="form-message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Staff Member</th>
                    <th>Email</th>
                    <th>Join Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($staff_members->num_rows > 0): ?>
                    <?php while ($staff = $staff_members->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div class="item-avatar" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    <i class="fa fa-user-tie"></i>
                                </div>
                                <div style="font-weight:600;"><?php echo htmlspecialchars($staff['full_name']); ?></div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($staff['email']); ?></td>
                        <td><?php echo date('d M Y', strtotime($staff['created_at'])); ?></td>
                        <td style="text-align:right;">
                            <a href="?action=demote&id=<?php echo $staff['id']; ?>" 
                               class="btn-admin" 
                               style="color: var(--danger); border-color: #fee2e2;"
                               onclick="return confirm('Remove staff privileges for this user?')">
                                <i class="fa fa-user-times"></i> Remove
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; padding: 40px; color: #888;">No staff members registered.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Sidebar -->
    <div class="column-blocks" style="display:flex; flex-direction:column; gap:30px;">
        <div class="card-block">
            <div class="block-header">
                <h3>Assign New Staff</h3>
            </div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">
                Select a customer to grant them staff-level management access.
            </p>
            
            <?php if ($other_users->num_rows > 0): ?>
                <?php while ($user = $other_users->fetch_assoc()): ?>
                <div class="list-item">
                    <div class="item-info">
                        <div class="item-avatar" style="background: rgba(0,0,0,0.05); color: #666;">
                            <i class="fa fa-user"></i>
                        </div>
                        <div style="margin-left: 12px;">
                            <div class="item-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                            <div class="item-meta"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                    </div>
                    <a href="?action=promote&id=<?php echo $user['id']; ?>" class="view-all">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="font-size:12px; color:#888;">No customers available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
