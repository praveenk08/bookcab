<?php
// Test script to check PHP functionality
echo '<h1>PHP Test</h1>';
echo '<p>PHP is working correctly!</p>';
echo '<h2>Server Information</h2>';
echo '<pre>';
print_r($_SERVER);
echo '</pre>';

echo '<h2>Database Connection Test</h2>';
try {
    $conn = new mysqli('localhost', 'root', '', 'carbooking');
    if ($conn->connect_error) {
        echo '<p style="color: red;">Database connection failed: ' . $conn->connect_error . '</p>';
    } else {
        echo '<p style="color: green;">Database connection successful!</p>';
        $conn->close();
    }
} catch (Exception $e) {
    echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
}
?>