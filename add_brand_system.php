<?php
require_once "config/db.php";

echo "<h2>Database Migration: Setting up Brand System</h2>";

// 1. Create brands table
$sql = "CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql)) {
    echo "<p style='color:green;'>Success: 'brands' table created or already exists.</p>";
} else {
    echo "<p style='color:red;'>Error creating brands table: " . $conn->error . "</p>";
}

// 2. Add brand_id column to products table
$sql = "ALTER TABLE products ADD COLUMN IF NOT EXISTS brand_id INT DEFAULT NULL AFTER category";
if ($conn->query($sql)) {
    echo "<p style='color:green;'>Success: 'brand_id' column added to 'products' table.</p>";
} else {
    echo "<p style='color:red;'>Error adding brand_id column: " . $conn->error . "</p>";
}

echo "<p>Migration completed. Please run this script once and then delete it.</p>";
?>
