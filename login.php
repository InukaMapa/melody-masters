<?php
session_start();
require_once "config/db.php";
include "includes/header.php";

$message = "";
$message_type = "";

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

                // Store session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $user['role'];

                // 🔥 Role-based redirect
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

<div class="register-page">

    <div class="register-left">
        <div class="overlay-text">
            <h1>Welcome Back </h1>
            <p>Login to continue your musical journey.</p>
        </div>
    </div>

    <div class="register-right">
        <div class="register-card">
            <h2>Login</h2>

            <?php if (!empty($message)): ?>
                <div class="<?php echo $message_type == 'success' ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <label>Email Address</label>
                <input type="email" name="email" autocomplete="off" required>

                <label>Password</label>
                <input type="password" name="password" autocomplete="off" required>

                <button type="submit">Login</button>
            </form>

            <p class="login-link">
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>
    </div>

</div>

<?php include "includes/footer.php"; ?>