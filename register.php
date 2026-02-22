<?php
require_once "config/db.php";
include "includes/header.php";

$message = "";
$message_type = ""; // success or error

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $message = "All fields are required.";
        $message_type = "error";
    } else {

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
            $message_type = "success";
        } else {
            $message = "Email already exists.";
            $message_type = "error";
        }

        $stmt->close();
    }
}
?>

<div class="container">
    <h2>Create Account</h2>

    <?php if (!empty($message)): ?>
        <div class="<?php echo $message_type == 'success' ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Create Account</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>