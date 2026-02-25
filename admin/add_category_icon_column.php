<?php
require_once "config/db.php";

// Add icon_class column if it doesn't exist
$sql = "ALTER TABLE categories ADD COLUMN icon_class VARCHAR(50) DEFAULT 'fa-music' AFTER name";
if ($conn->query($sql)) {
    echo "Column 'icon_class' added successfully.<br>";
} else {
    echo "Error adding column: " . $conn->error . "<br>";
}

// Update existing categories with suitable icons
$icons = [
    'Wind' => 'fa-wind',
    'Brass' => 'fa-drum', // Using drum as a fallback or if brass specific icon is needed
    'Guitars' => 'fa-guitar',
    'Keyboards' => 'fa-keyboard',
    'Drums' => 'fa-drum',
    'Acoustic Guitars' => 'fa-guitar',
    'Electric Guitars' => 'fa-plug-circle-bolt',
    'Pianos' => 'fa-music'
];

foreach ($icons as $name => $icon) {
    $stmt = $conn->prepare("UPDATE categories SET icon_class = ? WHERE name LIKE ?");
    $search = "%$name%";
    $stmt->bind_param("ss", $icon, $search);
    $stmt->execute();
    $stmt->close();
}

echo "Default icons assigned assigned.";
?>
