<?php
session_start();
require_once "config/db.php";

$message = "";
$message_type = "";

if (isset($_GET['registered']) && $_GET['registered'] == 'success') {
    $message = $_SESSION['reg_success'] ?? "Account created successfully! Please log in.";
    $message_type = "success";
    unset($_SESSION['reg_success']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "All fields are required.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $message = "Invalid password.";
                $message_type = "error";
            }
        } else {
            $message = "Email not found.";
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Melody Masters</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3.8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="auth-body">

<div class="auth-portal">
    <div class="auth-container">
        <!-- Left Side: Visual -->
        <div class="auth-visual login-bg">
            <div class="auth-overlay">
                <a href="index.php" class="auth-back-home">
                    <i class="fa fa-arrow-left"></i> Back to Home
                </a>
                <div class="auth-welcome">
                    <div class="auth-logo">
                        <i class="fa fa-music"></i>
                        <div class="brand-text">
                            <div class="brand-name">
                                <span class="melody">Melody</span><span class="masters">Masters</span>
                            </div>
                            <span class="tagline">Excellence in Sound</span>
                        </div>
                    </div>
                    <h1>Welcome Back</h1>
                    <p>Unlock your musical potential with Melody Masters. Your journey continues here.</p>
                </div>
                <div class="auth-stats">
                    <div class="auth-stat">
                        <span>50k+</span>
                        <p>Musicians</p>
                    </div>
                    <div class="divider"></div>
                    <div class="auth-stat">
                        <span>4.9/5</span>
                        <p>Rating</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="auth-content">
            <div class="auth-form-wrapper">
                <div class="auth-header">
                    <h2>Sign In</h2>
                    <p>Please enter your details to access your account</p>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="auth-alert <?php echo $message_type == 'success' ? 'alert-success' : 'alert-error'; ?>">
                        <i class="fa <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form" autocomplete="off">
                    <div class="form-group">
                        <label><i class="fa fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-lock"></i> Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="loginPassword" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        <span>Sign In</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="register.php">Create Account</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>