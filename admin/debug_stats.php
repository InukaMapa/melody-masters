<?php
require_once "../config/db.php";

// Fix roles
$conn->query("UPDATE users SET role = 'customer' WHERE role = 'user'");
echo "Roles updated: " . $conn->affected_rows . "\n";

echo "--- Final Stats Verification ---\n";
$query = "
    SELECT 
        u.id, u.full_name,
        COUNT(o.id) as order_count, 
        SUM(o.total_amount) as total_spent 
    FROM users u
    LEFT JOIN orders o ON u.id = o.user_id AND o.status != 'cancelled'
    WHERE u.role = 'customer'
    GROUP BY u.id";
$res = $conn->query($query);
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | " . $row['full_name'] . " | Orders: " . $row['order_count'] . " | Spent: £" . number_format($row['total_spent'] ?: 0, 2) . "\n";
}
?>
