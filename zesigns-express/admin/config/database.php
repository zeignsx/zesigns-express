<?php
// Database configuration using MySQL (works with XAMPP)
class Database {
    private static $instance = null;
    private $conn;
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'zesigns_admin';
    
    private function __construct() {
        try {
            // Create connection
            $this->conn = new mysqli($this->host, $this->user, $this->pass);
            
            // Check connection
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            // Create database if not exists
            $this->conn->query("CREATE DATABASE IF NOT EXISTS $this->name");
            $this->conn->select_db($this->name);
            
            // Set charset
            $this->conn->set_charset("utf8mb4");
            
            // Initialize tables
            $this->initializeTables();
            
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
    
    private function initializeTables() {
        // Users table
        $this->conn->query("CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
            role ENUM('admin', 'user') DEFAULT 'user',
            is_active BOOLEAN DEFAULT 1,
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Sessions table
        $this->conn->query("CREATE TABLE IF NOT EXISTS sessions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            session_token VARCHAR(255) UNIQUE NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            expires_at DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_token (session_token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Activity logs table
        $this->conn->query("CREATE TABLE IF NOT EXISTS activity_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            action VARCHAR(100) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_user (user_id),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Orders table (if needed)
        $this->conn->query("CREATE TABLE IF NOT EXISTS orders (
            id INT PRIMARY KEY AUTO_INCREMENT,
            order_id VARCHAR(50) UNIQUE NOT NULL,
            user_id INT,
            customer_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            service_name VARCHAR(200) NOT NULL,
            total_amount DECIMAL(10,2) DEFAULT 0,
            status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_status (status),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Create default admin if not exists
        $this->createDefaultAdmin();
    }
    
    private function createDefaultAdmin() {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            $hashed_password = password_hash('Admin@123', PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
            $username = 'admin';
            $email = 'admin@zesigns.com';
            $full_name = 'Administrator';
            $role = 'admin';
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $role);
            $stmt->execute();
        }
    }
}

// Helper function
function db() {
    return Database::getInstance();
}

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>