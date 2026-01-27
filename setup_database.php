<?php
// Database configuration
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

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($database);

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS service_categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) UNIQUE NOT NULL,
        description TEXT,
        icon VARCHAR(50) DEFAULT 'fas fa-palette',
        display_order INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS services (
        id INT PRIMARY KEY AUTO_INCREMENT,
        category_id INT,
        title VARCHAR(200) NOT NULL,
        slug VARCHAR(200) UNIQUE NOT NULL,
        short_description TEXT,
        full_description LONGTEXT,
        features TEXT,
        price_range VARCHAR(100),
        base_price DECIMAL(10,2),
        delivery_days VARCHAR(50),
        is_popular BOOLEAN DEFAULT FALSE,
        is_featured BOOLEAN DEFAULT FALSE,
        badge_text VARCHAR(50),
        badge_color VARCHAR(20),
        display_order INT DEFAULT 0,
        status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS service_images (
        id INT PRIMARY KEY AUTO_INCREMENT,
        service_id INT,
        image_type ENUM('thumbnail', 'gallery', 'featured', 'banner') DEFAULT 'gallery',
        image_path VARCHAR(255) NOT NULL,
        image_alt VARCHAR(200),
        display_order INT DEFAULT 0,
        is_default BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Insert sample data
$sample_categories = [
    "('Brand Identity', 'brand-identity', 'Complete brand identity packages', 'fas fa-palette', 1)",
    "('UI/UX Design', 'ui-ux-design', 'Website and app design', 'fas fa-laptop-code', 2)",
    "('Print Design', 'print-design', 'Business materials design', 'fas fa-print', 3)",
    "('Motion Graphics', 'motion-graphics', 'Animated designs and videos', 'fas fa-film', 4)",
    "('Social Media', 'social-media', 'Social media graphics', 'fas fa-hashtag', 5)"
];

foreach ($sample_categories as $category) {
    $sql = "INSERT IGNORE INTO service_categories (name, slug, description, icon, display_order) VALUES $category";
    if ($conn->query($sql) === TRUE) {
        echo "Category inserted: $category<br>";
    }
}

// Insert sample services
$sample_services = [
    "(1, 'Logo Design', 'logo-design', 'Professional logo design service', '[\"3 Concepts\", \"Unlimited Revisions\", \"Vector Files\"]', '$149 - $499', 149, '3-7 Days', 1, 1, 'Popular', 'primary')",
    "(1, 'Brand Identity', 'brand-identity', 'Complete brand package', '[\"Logo\", \"Colors\", \"Typography\", \"Guidelines\"]', '$299 - $899', 299, '5-10 Days', 0, 1, 'Premium', 'success')",
    "(2, 'Website Design', 'website-design', 'Modern website UI/UX', '[\"Wireframing\", \"UI Design\", \"Responsive\", \"Prototype\"]', '$499 - $2,499', 499, '7-14 Days', 1, 1, 'Trending', 'warning')",
    "(3, 'Business Cards', 'business-cards', 'Professional business cards', '[\"Front & Back\", \"Print Ready\", \"Multiple Designs\"]', '$99 - $299', 99, '2-4 Days', 1, 0, 'Fast', 'info')"
];

foreach ($sample_services as $service) {
    $sql = "INSERT IGNORE INTO services (category_id, title, slug, short_description, features, price_range, base_price, delivery_days, is_popular, is_featured, badge_text, badge_color) VALUES $service";
    if ($conn->query($sql) === TRUE) {
        echo "Service inserted: $service<br>";
    }
}

echo "<h2>Database setup complete!</h2>";
echo "<p><a href='services.php'>Go to Services Page</a></p>";

$conn->close();
?>                       placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn primary btn-block">Register</button>
        </form>
        
        <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
    </div>