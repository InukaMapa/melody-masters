<?php
require_once "../config/db.php";

echo "--- Categories Table ---\n";
$res = $conn->query("SELECT * FROM categories");
while($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n--- Products Table Sample ---\n";
$res = $conn->query("SELECT id, name, category, category_id FROM products LIMIT 5");
while($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n--- Products Schema ---\n";
$res = $conn->query("DESCRIBE products");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
