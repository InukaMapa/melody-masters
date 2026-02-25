<?php
require_once "config/db.php";

echo "<h2>Database Migration: Adding Category Column</h2>";

// 1. Add category column if it doesn't exist
$sql = "ALTER TABLE products ADD COLUMN IF NOT EXISTS category VARCHAR(100) DEFAULT 'General' AFTER product_type";
if ($conn->query($sql)) {
    echo "<p style='color:green;'>Success: 'category' column added or already exists.</p>";
} else {
    echo "<p style='color:red;'>Error updating table: " . $conn->error . "</p>";
}

// 2. Optional: Seed some initial categories based on name for demonstration
$updates = [
    'Guitar' => ['guitar', 'fender', 'gibson'],
    'Drums' => ['drum', 'percussion', 'yamaha'],
    'Keyboards' => ['piano', 'keyboard', 'casio'],
    'Wind & Brass' => ['sax', 'trumpet', 'flute'],
    'Microphones' => ['mic', 'micro', 'shure'],
    'Sheet Music PDFs' => ['sheet', 'pdf', 'score']
];

foreach ($updates as $category => $keywords) {
    foreach ($keywords as $keyword) {
        $sql = "UPDATE products SET category = '$category' WHERE name LIKE '%$keyword%' AND category = 'General'";
        $conn->query($sql);
    }
}

echo "<p>Migration completed. You can now delete this file.</p>";
?>
