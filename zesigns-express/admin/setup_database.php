<?php
// admin/setup_database.php
require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #fff;
            padding: 30px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #1e293b;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #334155;
        }
        h1 {
            color: #6366f1;
            margin-bottom: 20px;
        }
        .success {
            color: #22c55e;
            background: rgba(34, 197, 94, 0.1);
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #22c55e;
        }
        .error {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #ef4444;
        }
        .info {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #3b82f6;
        }
        .credentials {
            background: #0f172a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .cred-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .cred-item i {
            color: #f59e0b;
        }
        code {
            background: #334155;
            padding: 5px 10px;
            border-radius: 5px;
            color: #f59e0b;
        }
        .btn {
            display: inline-block;
            background: #6366f1;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Database Setup for Zesigns Express Admin</h1>";

try {
    $db = db();
    echo "<div class='success'>✅ Database connection successful!</div>";
    
    // Test tables
    $tables = ['users', 'sessions', 'activity_logs', 'orders'];
    foreach ($tables as $table) {
        $result = $db->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<div class='success'>✅ Table '$table' exists</div>";
        } else {
            echo "<div class='error'>❌ Table '$table' not found</div>";
        }
    }
    
    // Count users
    $result = $db->query("SELECT COUNT(*) as count FROM users");
    $count = $result->fetch_assoc()['count'];
    echo "<div class='info'>📊 Total users in database: $count</div>";
    
    // Show admin credentials
    echo "<div class='credentials'>
        <h3 style='color: #f59e0b; margin-bottom: 15px;'>🔑 Default Login Credentials</h3>
        <div class='cred-item'>
            <i class='fas fa-user'></i>
            <strong>Admin:</strong> admin@zesigns.com / <code>Admin@123</code>
        </div>
        <div class='cred-item'>
            <i class='fas fa-user'></i>
            <strong>Test User:</strong> user@zesigns.com / <code>User@123</code>
        </div>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Setup failed: " . $e->getMessage() . "</div>";
}

echo "<a href='login.php' class='btn'>
    <i class='fas fa-sign-in-alt'></i> Go to Login Page
</a>";

echo "</div></body></html>";
?>