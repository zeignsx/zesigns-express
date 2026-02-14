<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'config/database.php';

$error = '';
$success = '';

// Check for logout message
if (isset($_GET['message']) && $_GET['message'] === 'logged_out') {
    $success = 'You have been successfully logged out.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username/email and password';
    } else {
        $db = db();
        
        // Check if input is email or username
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Use ? placeholder for MySQL
        $stmt = $db->prepare("SELECT * FROM users WHERE $field = ? AND is_active = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $update = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update->bind_param("i", $user['id']);
            $update->execute();
            
            // Log activity
            $log = $db->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
            $action = 'login';
            $details = 'Successful login';
            $ip = $_SERVER['REMOTE_ADDR'];
            $log->bind_param("isss", $user['id'], $action, $details, $ip);
            $log->execute();
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_image'] = $user['profile_image'];
            $_SESSION['login_time'] = time();
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                
                $stmt = $db->prepare("INSERT INTO sessions (user_id, session_token, ip_address, user_agent, expires_at) VALUES (?, ?, ?, ?, ?)");
                $agent = $_SERVER['HTTP_USER_AGENT'];
                $stmt->bind_param("issss", $user['id'], $token, $ip, $agent, $expires);
                $stmt->execute();
                
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
            }
            
            // Redirect to dashboard
            header('Location: index.php');
            exit();
        } else {
            $error = 'Invalid username/email or password';
            
            // Log failed attempt
            $log = $db->prepare("INSERT INTO activity_logs (action, details, ip_address) VALUES (?, ?, ?)");
            $action = 'failed_login';
            $details = 'Failed login attempt for: ' . $username;
            $ip = $_SERVER['REMOTE_ADDR'];
            $log->bind_param("sss", $action, $details, $ip);
            $log->execute();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Zesigns Express</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --dark: #0f172a;
            --dark-card: #1e293b;
            --dark-hover: #334155;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gradient: linear-gradient(135deg, #6366f1, #0ea5e9);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary-light);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Main Container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 48px;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 10px;
        }

        .logo h2 {
            font-size: 24px;
            font-weight: 700;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .logo p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 5px;
        }

        /* Messages */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: var(--success);
        }

        /* Form */
        .login-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
            transition: color 0.3s;
        }

        .input-group .toggle-password {
            left: auto;
            right: 15px;
            cursor: pointer;
            z-index: 10;
        }

        .input-group .toggle-password:hover {
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 14px 20px 14px 45px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-light);
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-me label {
            color: var(--text-muted);
            font-size: 14px;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--primary-light);
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 14px;
            background: var(--gradient);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px var(--primary);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 25px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: var(--primary-light);
        }

        /* Back to Home */
        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
        }

        .back-home a:hover {
            color: var(--text-light);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Demo Credentials */
        .demo-credentials {
            margin-top: 25px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-muted);
            border: 1px dashed var(--border);
        }

        .demo-credentials p {
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-light);
        }

        .demo-credentials .cred-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 0;
        }

        .demo-credentials .cred-item i {
            color: var(--success);
            font-size: 12px;
        }

        .demo-credentials .cred-item span {
            color: var(--primary);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="bg-particles" id="particles"></div>

    <!-- Login Container -->
    <div class="login-container">
        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-crown"></i>
            <h2>Admin Panel</h2>
            <p>Sign in to access your dashboard</p>
        </div>

        <!-- Messages -->
        <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form class="login-form" method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Enter your username or email" required autofocus
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Enter your password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Demo Credentials -->
        <div class="demo-credentials">
            <p><i class="fas fa-info-circle"></i> Demo Credentials</p>
            <div class="cred-item">
                <i class="fas fa-check"></i>
                <span>Admin:</span> admin@zesigns.com / Admin@123
            </div>
            <div class="cred-item">
                <i class="fas fa-check"></i>
                <span>User:</span> user@zesigns.com / User@123
            </div>
        </div>

        <!-- Register Link -->
        <div class="register-link">
            Don't have an account? <a href="register.php">Create Account</a>
        </div>

        <!-- Back to Home -->
        <div class="back-home">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i>
                Back to Website
            </a>
        </div>
    </div>

    <script>
        // Create particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random properties
                const size = Math.random() * 4 + 1;
                const posX = Math.random() * 100;
                const duration = Math.random() * 20 + 10;
                const delay = Math.random() * 10;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;
                
                particlesContainer.appendChild(particle);
            }
        }

        // Toggle password visibility
        function togglePassword() {
            const password = document.getElementById('password');
            const toggle = document.querySelector('.toggle-password');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }

        // Form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }
            
            // Show loading state
            btn.innerHTML = '<span class="spinner"></span> Signing in...';
            btn.disabled = true;
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + L to focus username
            if (e.ctrlKey && e.key === 'l') {
                e.preventDefault();
                document.getElementById('username').focus();
            }
        });

        // Initialize particles
        createParticles();
    </script>
</body>
</html>