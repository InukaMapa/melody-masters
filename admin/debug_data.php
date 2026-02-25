<?php
require_once "../config/db.php";

echo "--- Orders for User 4 ---\n";
$res = $conn->query("SELECT id, user_id, total_amount, status FROM orders WHERE user_id = 4");
while($row = $res->fetch_assoc()) {
    echo "Order #" . $row['id'] . " | User: " . $row['user_id'] . " | Amount: " . $row['total_amount'] . " | Status: " . $row['status'] . "\n";
}
?>
