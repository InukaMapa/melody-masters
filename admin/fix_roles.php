<?php
require_once "../config/db.php";

$conn->query("UPDATE users SET role = 'admin' WHERE id = 3");
echo "User 3 updated: " . $conn->affected_rows . "\n";

$conn->query("UPDATE users SET role = 'customer' WHERE id = 4");
echo "User 4 updated: " . $conn->affected_rows . "\n";

echo "--- Verification ---\n";
$res = $conn->query("SELECT id, full_name, role FROM users");
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | " . $row['full_name'] . " | Role: " . $row['role'] . "\n";
}
?>
