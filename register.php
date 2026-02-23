<?php
require_once "config/db.php";
include "includes/header.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = "error";
    } else {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists.";
            $message_type = "error";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now login.";
                $message_type = "success";
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

<div class="register-page">

    <div class="register-left">
        <div class="overlay-text">
            <h1>Join Melody Masters</h1>
            <p>Start your musical journey today 🎵</p>
        </div>
    </div>

    <div class="register-right">
        <div class="register-card">
            <h2>Create Account</h2>

            <?php if (!empty($message)): ?>
                <div class="<?php echo $message_type == 'success' ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <label>Full Name</label>
                <input type="text" name="full_name" required>

                <label>Email Address</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Create Account</button>
            </form>

            <p class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </div>
    </div>

</div>

<?php include "includes/footer.php"; ?>