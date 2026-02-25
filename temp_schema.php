<?php
require_once 'c:\Xampp\htdocs\melody-masters\config\db.php';
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    echo $row[0] . "\n";
    $res2 = $conn->query("SHOW COLUMNS FROM " . $row[0]);
    while ($r = $res2->fetch_assoc()) {
        echo "  " . $r['Field'] . " - " . $r['Type'] . "\n";
    }
}
?>
