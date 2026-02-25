<?php
require_once "../config/db.php";
$conn->query("UPDATE users SET role = 'admin' WHERE id = 3");
$conn->query("UPDATE users SET role = 'customer' WHERE id = 4");
echo "Roles Synchronized";
?>
