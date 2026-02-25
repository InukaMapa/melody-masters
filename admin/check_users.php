<?php
require_once "../config/db.php";
$res = $conn->query("SELECT id, email, role FROM users");
while($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Email: {$row['email']} | Role: {$row['role']}\n";
}
?>
