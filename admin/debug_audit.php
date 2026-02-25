<?php
require_once "../config/db.php";

echo "--- Full User Audit ---\n";
$res = $conn->query("SELECT id, full_name, email, role FROM users");
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Name: " . $row['full_name'] . " | Role: " . $row['role'] . "\n";
}

echo "\n--- Order Links ---\n";
$res = $conn->query("SELECT id, user_id, total_amount, status FROM orders");
while($row = $res->fetch_assoc()) {
    echo "Order: " . $row['id'] . " | User: " . $row['user_id'] . " | Amt: " . $row['total_amount'] . " | Status: " . $row['status'] . "\n";
}
?>
