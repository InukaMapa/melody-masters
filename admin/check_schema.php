<?php
require_once "../config/db.php";
$res = $conn->query("DESCRIBE products");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}
echo "\n--- Sample ---\n";
$res = $conn->query("SELECT id, name, category, category_id FROM products LIMIT 5");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
