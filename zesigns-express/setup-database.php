<?php
// setup-database.php - Complete Database Setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "zesigns_express";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Setting up Zesigns Express Database...</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #0f172a; color: white; }
    .success { color: #10b981; padding: 10px; background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; margin: 10px 0; }
    .error { color: #ef4444; padding: 10px; background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; margin: 10px 0; }
    .info { color: #3b82f6; padding: 10px; background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; margin: 10px 0; }
</style>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "<div class='success'>✓ Database '$database' created successfully</div>";
} else {
    echo "<div class='error'>Error creating database: " . $conn->error . "</div>";
}

// Select database
$conn->select_db($database);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role ENUM('client', 'designer', 'admin') DEFAULT 'client',
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255),
    bio TEXT,
    skills TEXT,
    experience VARCHAR(50),
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_projects INT DEFAULT 0,
    is_verified BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<div class='success'>✓ Table 'users' created successfully</div>";
} else {
    echo "<div class='error'>Error creating users table: " . $conn->error . "</div>";
}

// Create portfolio table
$sql = "CREATE TABLE IF NOT EXISTS portfolio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    image_url VARCHAR(255) NOT NULL,
    thumbnail_url VARCHAR(255),
    project_url VARCHAR(255),
    tags TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    status ENUM('published', 'draft', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<div class='success'>✓ Table 'portfolio' created successfully</div>";
} else {
    echo "<div class='error'>Error creating portfolio table: " . $conn->error . "</div>";
}

// Insert sample data
echo "<div class='info'>Inserting sample data...</div>";

// Insert sample designer
$hashed_password = password_hash('designer123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (role, name, email, password, bio, skills, experience, rating, total_projects, is_verified, is_featured) 
        VALUES ('designer', 'Alex Designer', 'alex@zesigns.com', '$hashed_password', 
        'Professional designer with 5+ years experience', '[\"Logo Design\", \"Branding\", \"UI/UX\"]', 
        '5 years', 4.8, 250, 1, 1)";

if ($conn->query($sql) === TRUE) {
    echo "<div class='success'>✓ Sample designer created (alex@zesigns.com / designer123)</div>";
    $designer_id = $conn->insert_id;
    
    // Insert portfolio items
    $portfolio_items = [
        "($designer_id, 'Coffee Shop Branding', 'Complete brand identity for Nova Coffee', 'branding', 
        'https://images.unsplash.com/photo-1514066558159-fc8c737ef259?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1514066558159-fc8c737ef259?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        '[\"branding\", \"logo\", \"coffee\"]', 1, 1200, 245, 'published')",
        
        "($designer_id, 'Financial Dashboard', 'Modern FinTech dashboard design', 'web', 
        'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        '[\"ui\", \"ux\", \"dashboard\", \"fintech\"]', 1, 980, 189, 'published')",
        
        "($designer_id, 'Fitness App Design', 'Health tracking mobile application', 'mobile', 
        'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        '[\"mobile\", \"app\", \"fitness\", \"ui\"]', 0, 2100, 421, 'published')"
    ];
    
    foreach ($portfolio_items as $item) {
        $sql = "INSERT IGNORE INTO portfolio (user_id, title, description, category, image_url, thumbnail_url, tags, is_featured, views, likes, status) VALUES $item";
        $conn->query($sql);
    }
    
    echo "<div class='success'>✓ Sample portfolio items created</div>";
}

// Insert admin user
$hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (role, name, email, password, is_verified) VALUES 
        ('admin', 'Admin User', 'admin@zesigns.com', '$hashed_password', 1)";
$conn->query($sql);

echo "<div class='success'>✓ Admin user created (admin@zesigns.com / admin123)</div>";

echo "<h3 style='color: #10b981; margin-top: 30px;'>✅ Database setup completed successfully!</h3>";
echo "<p><a href='index.php' style='color: #3b82