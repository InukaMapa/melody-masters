<?php
require_once "config/db.php";

// Remove Microphone image
$conn->query("UPDATE categories SET image_path = NULL WHERE name LIKE '%Microphone%' OR name LIKE '%Mic%'");

// Update Wind & Brass image path to a new filename for the user's uploaded image
$conn->query("UPDATE categories SET image_path = 'assets/images/wind_brass_icon.png' WHERE name LIKE '%Wind%' OR name LIKE '%Brass%'");

echo "Categories updated successfully.";
?>
