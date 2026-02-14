<?php
session_start();

// Database connection (using SQLite for simplicity)
$db = new SQLite3('zexpress.db');

// Create users table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    phone TEXT,
    role TEXT DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Create admin user if not exists
$adminEmail = "Joshbawai48@gmail.com";
$adminPassword = password_hash("123456789", PASSWORD_DEFAULT);
$adminName = "Josh Bawai";

$stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
$stmt->bindValue(':email', $adminEmail, SQLITE3_TEXT);
$result = $stmt->execute();
$adminExists = $result->fetchArray();

if (!$adminExists) {
    $stmt = $db->prepare("INSERT INTO users (email, password, name, role) VALUES (:email, :password, :name, 'admin')");
    $stmt->bindValue(':email', $adminEmail, SQLITE3_TEXT);
    $stmt->bindValue(':password', $adminPassword, SQLITE3_TEXT);
    $stmt->bindValue(':name', $adminName, SQLITE3_TEXT);
    $stmt->execute();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role']
        ];
        
        // Redirect based on role
        if ($user['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zesigns Express</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-bg: #0f0f1e;
            --dark-card: #1a1a2e;
            --dark-card-hover: #22223a;
            --primary: #6d28d9;
            --primary-dark: #5b21b6;
            --primary-light: #8b5cf6;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-dark: #334155;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #0a0a15 0%, #151533 100%);
        }
        
        .login-container {
            background: var(--dark-card);
            border-radius: 25px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-dark);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .login-header p {
            color: var(--text-muted);
        }
        
        .admin-notice {
            background: rgba(109, 40, 217, 0.1);
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: var(--primary-light);
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.4);
        }
        
        .login-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .login-links a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-links a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }
        
        .back-to-home {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-to-home a {
            color: var(--text-muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .back-to-home a:hover {
            color: var(--text-light);
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: var(--danger);
            text-align: center;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
            }
            
            .login-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your account to continue</p>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <div class="admin-notice">
            <i class="fas fa-shield-alt"></i> Admin Login: Joshbawai48@gmail.com / 123456789
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo $adminEmail; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required value="123456789">
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <div class="login-links">
            <p>Don't have an account? <a href="register.php">Sign up here</a></p>
            <p><a href="forgot-password.php">Forgot your password?</a></p>
        </div>
        
        <div class="back-to-home">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Back to Home
                <?php
session_start();
$db = new SQLite3('zexpress.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check for admin login
    if ($email === 'Joshbawai48@gmail.com' && $password === '123456789') {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Josh Bawai',
            'email' => $email,
            'role' => 'admin',
            'profile_image' => 'admin-avatar.png'
        ];
        
        // Update last login
        $stmt = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->execute();
        
        header('Location: admin/dashboard.php');
        exit();
    }
    
    // Regular user login
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'phone' => $user['phone'],
            'address' => $user['address'],
            'profile_image' => $user['profile_image'] ?? 'default-avatar.jpg',
            'bio' => $user['bio']
        ];
        
        // Update last login
        $stmt = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindValue(':id', $user['id'], SQLITE3_INTEGER);
        $stmt->execute();
        
        // Redirect based on role
        if ($user['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: user/dashboard.php');
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zesigns Express</title>
    <!-- Your existing login page styles here -->
</head>
<body>
    <!-- Your existing login form -->
</body>
</html>
            </a>
        </div>
    </div>
</body>
</html>