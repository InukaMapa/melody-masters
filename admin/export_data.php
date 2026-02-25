<?php
require_once "../config/db.php";

$data = [
    'users' => [],
    'orders' => []
];

$res = $conn->query("SELECT * FROM users");
while($row = $res->fetch_assoc()) {
    $data['users'][] = $row;
}

$res = $conn->query("SELECT * FROM orders");
while($row = $res->fetch_assoc()) {
    $data['orders'][] = $row;
}

file_put_contents('raw_data.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Data exported to raw_data.json\n";
?>
