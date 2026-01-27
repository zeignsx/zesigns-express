<?php
// setup.php - Run this once to setup the includes folder
echo "<h2>Creating Required Files...</h2>";

// Create includes directory if it doesn't exist
if (!file_exists('includes')) {
    mkdir('includes', 0777, true);
    echo "<p style='color: green;'>✓ Created includes/ directory</p>";
}

// Create db.php
$db_content = '<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "zesigns_express";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>';

if (file_put_contents('includes/db.php', $db_content)) {
    echo "<p style='color: green;'>✓ Created includes/db.php</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to create includes/db.php</p>";
}

// Create header.php (simplified version)
$header_content = '<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zesigns Express</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">
                <i class="fas fa-pen-nib"></i> Zesigns Express
            </a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="designers.php">Designers</a>
                <a href="services.php">Services</a>
                <a href="contact.php">Contact</a>
            </div>
        </nav>
    </header>
    <main>';

if (file_put_contents('includes/header.php', $header_content)) {
    echo "<p style='color: green;'>✓ Created includes/header.php</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to create includes/header.php</p>";
}

// Create footer.php
$footer_content = '<?php ?>
    </main>
    <footer>
        <p>&copy; ' . date('Y') . ' Zesigns Express</p>
    </footer>
</body>
</html>';

if (file_put_contents('includes/footer.php', $footer_content)) {
    echo "<p style='color: green;'>✓ Created includes/footer.php</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to create includes/footer.php</p>";
}

echo "<h3 style='color: green;'>Setup Complete!</h3>";
echo "<p>Now run the database setup:</p>";
echo "<a href='dp.php' style='color: blue;'>Run Database Setup (dp.php)</a>";
?>