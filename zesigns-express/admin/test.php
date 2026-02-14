<?php
// admin/test.php
session_start();
require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Admin Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #fff;
            padding: 30px;
        }
        .card {
            background: #1e293b;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #334155;
        }
        .success { color: #22c55e; }
        .error { color: #ef4444; }
    </style>
</head>
<body>
    <h1>Admin System Test</h1>";

try {
    $db = db();
    echo "<div class='card'>";
    echo "<h2 class='success'>✓ Database Connection: Working</h2>";
    
    // Check PHP version
    echo "<p>✓ PHP Version: " . phpversion() . "</p>";
    
    // Check MySQL version
    $result = $db->query("SELECT VERSION() as version");
    $row = $result->fetch_assoc();
    echo "<p>✓ MySQL Version: " . $row['version'] . "</p>";
    
    // Check tables
    $tables = $db->query("SHOW TABLES");
    echo "<p>✓ Available Tables:</p><ul>";
    while ($table = $tables->fetch_array()) {
        echo "<li>" . $table[0] . "</li>";
    }
    echo "</ul>";
    
    echo "</div>";
    
    // Test login
    echo "<div class='card'>
        <h2>Login Test</h2>
        <p>Try logging in at <a href='login.php' style='color: #6366f1;'>login.php</a></p>
        <p>Default credentials: <strong>admin@zesigns.com</strong> / <strong>Admin@123</strong></p>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='card'>";
    echo "<h2 class='error'>✗ Error: " . $e->getMessage() . "</h2>";
    echo "</div>";
}

echo "</body></html>";
?>