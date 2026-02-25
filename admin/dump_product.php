<?php
require_once "../config/db.php";
$res = $conn->query("SELECT * FROM products LIMIT 1");
$row = $res->fetch_assoc();
echo json_encode($row, JSON_PRETTY_PRINT);
?>
