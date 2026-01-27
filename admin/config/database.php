<?php
// Database configuration
$db = new SQLite3('zexpress.db');

// Create tables if they don't exist
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

$db->exec("CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    subject TEXT,
    message TEXT NOT NULL,
    status TEXT DEFAULT 'unread',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Helper functions
function getStats($db) {
    return [
        'total_users' => $db->querySingle("SELECT COUNT(*) FROM users"),
        'total_orders' => $db->querySingle("SELECT COUNT(*) FROM orders"),
        'pending_orders' => $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'pending'"),
        'completed_orders' => $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'completed'"),
        'total_revenue' => $db->querySingle("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'") ?? 0,
    ];
}
?>