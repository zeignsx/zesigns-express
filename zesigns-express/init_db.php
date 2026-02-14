<?php
// Initialize database
$db = new SQLite3('zexpress.db');

// Create tables
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    phone TEXT,
    role TEXT DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id TEXT UNIQUE NOT NULL,
    user_id INTEGER,
    customer_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    company TEXT,
    service_name TEXT NOT NULL,
    service_type TEXT NOT NULL,
    description TEXT,
    deadline TEXT,
    budget TEXT,
    colors TEXT,
    inspiration TEXT,
    files TEXT,
    additional_info TEXT,
    total_amount REAL DEFAULT 0,
    status TEXT DEFAULT 'pending',
    assigned_to INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

$db->exec("CREATE TABLE IF NOT EXISTS services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    category TEXT,
    price_min REAL DEFAULT 0,
    price_max REAL DEFAULT 0,
    timeline TEXT,
    features TEXT,
    badge TEXT,
    badge_class TEXT,
    rating REAL DEFAULT 0,
    orders_count INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Insert admin user
$adminEmail = "Joshbawai48@gmail.com";
$adminPassword = password_hash("123456789", PASSWORD_DEFAULT);
$adminName = "Josh Bawai";

$stmt = $db->prepare("INSERT OR IGNORE INTO users (email, password, name, role) VALUES (:email, :password, :name, 'admin')");
$stmt->bindValue(':email', $adminEmail, SQLITE3_TEXT);
$stmt->bindValue(':password', $adminPassword, SQLITE3_TEXT);
$stmt->bindValue(':name', $adminName, SQLITE3_TEXT);
$stmt->execute();

echo "Database initialized successfully!<br>";
echo "Admin Login: $adminEmail / 123456789";
<?php
// update_database.php
$db = new SQLite3('zexpress.db');

// Add columns if they don't exist
try {
    $db->exec("ALTER TABLE users ADD COLUMN phone TEXT");
    $db->exec("ALTER TABLE users ADD COLUMN address TEXT");
    $db->exec("ALTER TABLE users ADD COLUMN profile_image TEXT DEFAULT 'default-avatar.jpg'");
    $db->exec("ALTER TABLE users ADD COLUMN bio TEXT");
    $db->exec("ALTER TABLE users ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP");
    $db->exec("ALTER TABLE users ADD COLUMN last_login DATETIME");
    
    echo "Database updated successfully!";
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
?>