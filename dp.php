<?php
// includes/db.php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'zesigns_express';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>