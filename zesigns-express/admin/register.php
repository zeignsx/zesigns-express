<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);
    
    // Validation
    if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!$terms) {
        $error = 'You must agree to the terms and conditions';
    } else {
        $db = db();
        
        // Check if username exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
        $result = $stmt->execute();
        if ($result->fetchArray()) {
            $error = 'Username already taken';
        } else {
            // Check if email exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email, SQLITE3_TEXT);
            $result = $stmt->execute();
            if ($result->fetchArray()) {
                $error = 'Email already registered';
            } else {
                // Create user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role = 'user'; // Default role
                
                $stmt = $db->prepare("INSERT INTO users (username, email, password, full_name, role) 
                    VALUES (:username, :email, :password, :full_name, :role)");
                $stmt->bindValue(':username', $username, SQLITE3_TEXT);
                $stmt->bindValue(':email', $email, SQLITE3_TEXT);
                $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
                $stmt->bindValue(':full_name', $full_name, SQLITE3_TEXT);
                $stmt->bindValue(':role', $role, SQLITE3_TEXT);
                
                if ($stmt->execute()) {
                    $user_id = $db->lastInsertRowID();
                    
                    // Log activity
                    $log = $db->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) 
                        VALUES (:user_id, 'register', 'New account created', :ip)");
                    $log->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                    $log->bindValue(':ip', $_SERVER['REMOTE_ADDR'], SQLITE3_TEXT);
                    $log->execute();
                    
                    $success = 'Account created successfully! You can now login.';
                    
                    // Clear form
                    $_POST = [];
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Admin Panel</title>
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
        .register-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
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
        .register-form {
            margin-top: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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

        /* Password Strength */
        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s, background 0.3s;
        }

        .strength-text {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Terms Checkbox */
        .terms-group {
            margin-bottom: 25px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            margin-top: 3px;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.5;
            cursor: pointer;
        }

        .checkbox-wrapper a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .checkbox-wrapper a:hover {
            text-decoration: underline;
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

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 25px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-link a:hover {
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
            .register-container {
                padding: 30px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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

        /* Validation Icons */
        .validation-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }

        .validation-icon.valid {
            color: var(--success);
            display: block;
        }

        .validation-icon.invalid {
            color: var(--danger);
            display: block;
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="bg-particles" id="particles"></div>

    <!-- Register Container -->
    <div class="register-container">
        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-user-plus"></i>
            <h2>Create Account</h2>
            <p>Join Zesigns Express Admin Panel</p>
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

        <!-- Register Form -->
        <form class="register-form" method="POST" action="" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               placeholder="John Doe" required
                               value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-at"></i>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="johndoe" required minlength="3"
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="john@example.com" required
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="••••••••" required minlength="8">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Enter password</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="confirm_password" 
                               name="confirm_password" placeholder="••••••••" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                    </div>
                    <div id="passwordMatch" style="font-size: 12px; margin-top: 5px;"></div>
                </div>
            </div>

            <div class="terms-group">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                        <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                <span>Create Account</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Login Link -->
        <div class="login-link">
            Already have an account? <a href="login.php">Sign In</a>
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
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 25;
            
            // Uppercase check
            if (/[A-Z]/.test(password)) strength += 25;
            
            // Lowercase check
            if (/[a-z]/.test(password)) strength += 25;
            
            // Number/Special char check
            if (/[0-9!@#$%^&*]/.test(password)) strength += 25;
            
            // Update UI
            strengthFill.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthFill.style.background = '#ef4444';
                strengthText.textContent = 'Weak password';
                strengthText.style.color = '#ef4444';
            } else if (strength <= 50) {
                strengthFill.style.background = '#f59e0b';
                strengthText.textContent = 'Fair password';
                strengthText.style.color = '#f59e0b';
            } else if (strength <= 75) {
                strengthFill.style.background = '#3b82f6';
                strengthText.textContent = 'Good password';
                strengthText.style.color = '#3b82f6';
            } else {
                strengthFill.style.background = '#22c55e';
                strengthText.textContent = 'Strong password';
                strengthText.style.color = '#22c55e';
            }
        });

        // Password match checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirm.length === 0) {
                matchDiv.innerHTML = '';
                matchDiv.style.color = '';
            } else if (password === confirm) {
                matchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
                matchDiv.style.color = '#22c55e';
            } else {
                matchDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
                matchDiv.style.color = '#ef4444';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('You must agree to the terms and conditions');
                return;
            }
            
            // Show loading state
            btn.innerHTML = '<span class="spinner"></span> Creating account...';
            btn.disabled = true;
        });

        // Username availability check (optional)
        let usernameTimeout;
        document.getElementById('username').addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            const username = this.value;
            
            if (username.length >= 3) {
                usernameTimeout = setTimeout(() => {
                    // You can implement AJAX check here
                    console.log('Check username:', username);
                }, 500);
            }
        });

        // Initialize particles
        createParticles();
    </script>
</body>
</html>