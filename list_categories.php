<?php
require_once "config/db.php";
$r = $conn->query("SELECT name, image_path, icon_class FROM categories");
while($row = $r->fetch_assoc()) {
    echo $row['name'] . " | " . $row['image_path'] . " | " . $row['icon_class'] . PHP_EOL;
}
?>
