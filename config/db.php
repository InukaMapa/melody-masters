<?php

$host = "localhost";
$dbname = "melody_masters";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Site-Wide Self-Healing: Ensure category icons and images exist
$check_col = $conn->query("SHOW COLUMNS FROM categories LIKE 'icon_class'");
if ($check_col && $check_col->num_rows == 0) {
    $conn->query("ALTER TABLE categories ADD COLUMN icon_class VARCHAR(50) DEFAULT 'fa-music' AFTER name");
    // Assign intelligent defaults for existing categories
    $conn->query("UPDATE categories SET icon_class = 'fa-wind' WHERE name LIKE '%Wind%'");
    $conn->query("UPDATE categories SET icon_class = 'fa-drum' WHERE name LIKE '%Brass%' OR name LIKE '%Drum%'");
    $conn->query("UPDATE categories SET icon_class = 'fa-guitar' WHERE name LIKE '%Guitar%'");
    $conn->query("UPDATE categories SET icon_class = 'fa-microphone' WHERE name LIKE '%Microphone%' OR name LIKE '%Mic%'");
    $conn->query("UPDATE categories SET icon_class = 'fa-keyboard' WHERE name LIKE '%Keyboard%' OR name LIKE '%Piano%'");
}

$check_img = $conn->query("SHOW COLUMNS FROM categories LIKE 'image_path'");
if ($check_img && $check_img->num_rows == 0) {
    $conn->query("ALTER TABLE categories ADD COLUMN image_path VARCHAR(255) DEFAULT NULL AFTER icon_class");
    // Set specific image for Wind & Brass
    $conn->query("UPDATE categories SET image_path = 'assets/images/wind_category.jpg' WHERE name LIKE '%Wind%' OR name LIKE '%Brass%'");
    $conn->query("UPDATE categories SET image_path = 'assets/images/mic.jpg' WHERE name LIKE '%Microphone%' OR name LIKE '%Mic%'");
}

?>