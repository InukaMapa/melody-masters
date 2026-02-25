<?php
session_start();
require_once "config/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = "error";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists.";
            $message_type = "error";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'customer')");
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['reg_success'] = "Registration successful! You can now login.";
                header("Location: login.php?registered=success");
                exit();
            } else {
                $message = "Something went wrong. Try again.";
                $message_type = "error";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Melody Masters</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3.8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="auth-body">

<div class="auth-portal">
    <div class="auth-container">
        <!-- Left Side: Visual -->
        <div class="auth-visual register-bg">
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
                    <h1>Create Account</h1>
                    <p>Join our community of musical enthusiasts today. Start your masterpiece with us.</p>
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
                    <h2>Join Us</h2>
                    <p>Fill in your details to create your musical profile</p>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="auth-alert <?php echo $message_type == 'success' ? 'alert-success' : 'alert-error'; ?>">
                        <i class="fa <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form" autocomplete="off">
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> Full Name</label>
                        <input type="text" name="full_name" placeholder="John Doe" value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>

                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label><i class="fa fa-lock"></i> Password</label>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-shield-alt"></i> Confirm</label>
                            <input type="password" name="confirm_password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-options terms">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span>I agree to the <a href="#">Terms & Conditions</a></span>
                        </label>
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        <span>Create Account</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account? <a href="login.php">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>