<?php
require_once "includes/header.php";

$message = "";
$message_type = "";

// Handle New Admin Creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_admin'])) {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $message = "Email already registered!";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $message = "New Administrator account created!";
            $message_type = "success";
        } else {
            $message = "Error creating account.";
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <header class="dashboard-header" style="margin-bottom: 40px; text-align: center;">
        <div class="greeting" style="width: 100%;">
            <h1>Add New Administrator</h1>
            <p>Create fresh credentials for system access.</p>
        </div>
        <div style="margin-top: 15px;">
            <a href="manage_admins.php" class="view-all" style="justify-content: center;"><i class="fa fa-arrow-left"></i> Back to Administrators</a>
        </div>
    </header>

    <?php if ($message): ?>
        <div class="form-message <?php echo $message_type; ?>" style="margin-bottom: 30px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Focused Creation Form -->
    <div class="card-block" style="border: none; background: #fff; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; padding: 40px;">
        <div class="block-header" style="margin-bottom: 35px; text-align: center;">
            <div style="width:60px; height:60px; background:rgba(59, 130, 246, 0.1); color:#3b82f6; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin: 0 auto 20px;">
                <i class="fa fa-user-shield"></i>
            </div>
            <h3 style="margin:0; font-size:22px;">Account Details</h3>
            <p style="margin:8px 0 0; font-size:14px; color:#888;">Complete the fields below to add a new admin.</p>
        </div>

        <form method="POST" autocomplete="off">
            <input type="hidden" name="create_admin" value="1">
            <div class="form-group">
                <label>Administrator Full Name</label>
                <div style="position:relative;">
                    <input type="text" name="full_name" class="form-control" placeholder="e.g. Alexandra Smith" required style="padding-left: 45px; border-radius:12px; height: 50px;">
                    <i class="fa fa-user" style="position:absolute; left:18px; top:17px; color:#cbd5e1;"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <div style="position:relative;">
                    <input type="email" name="email" class="form-control" placeholder="alex@melodymasters.com" required style="padding-left: 45px; border-radius:12px; height: 50px;">
                    <i class="fa fa-envelope" style="position:absolute; left:18px; top:17px; color:#cbd5e1;"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Secure Password</label>
                <div style="position:relative;">
                    <input type="password" name="password" class="form-control" placeholder="••••••••••••" required style="padding-left: 45px; border-radius:12px; height: 50px;" autocomplete="new-password">
                    <i class="fa fa-lock" style="position:absolute; left:18px; top:17px; color:#cbd5e1;"></i>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-primary-admin" style="width:100%; justify-content:center; padding:20px; font-size:16px; font-weight:800; border-radius:12px; margin-top:15px; box-shadow: 0 10px 20px rgba(225, 29, 72, 0.2); background: var(--primary-color);">
                <i class="fa fa-user-plus"></i> Initialize Admin Profile
            </button>
        </form>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
