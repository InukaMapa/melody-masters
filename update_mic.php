<?php
require_once "config/db.php";

// Update icon to fa-microphone-lines for better visual suitabiliy
$conn->query("UPDATE categories SET icon_class = 'fa-microphone-lines' WHERE name LIKE '%Microphone%' OR name LIKE '%Mic%'");

// Also ensure the image is set if it wasn't
$conn->query("UPDATE categories SET image_path = 'assets/images/mic.jpg' WHERE (name LIKE '%Microphone%' OR name LIKE '%Mic%') AND (image_path IS NULL OR image_path = '')");

echo "Microphone category updated correctly.";
?>
