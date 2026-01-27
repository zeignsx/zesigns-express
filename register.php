<?php
session_start();
include '../includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'client';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (role, name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $role, $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! Redirecting to login...';
                header("refresh:2; url=login.php");
            } else {
                $error = 'Registration failed. Please try again.';
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
    <title>Register - Zesigns Express</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            background: #0f0f1e;
            color: white;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }
        .login-container {
            background: #1a1a2e;
            padding: 3rem;
            border-radius: 20px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #cbd5e1;
            font-size: 0.9rem;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #334155;
            border-radius: 10px;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            box-sizing: border-box;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #6d28d9;
            box-shadow: 0 0 0 2px rgba(109, 40, 217, 0.2);
        }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            padding-right: 2.5rem;
        }
        button {
            width: 100%;
            padding: 1rem;
            background: #6d28d9;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        button:hover {
            background: #5b21b6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(109, 40, 217, 0.3);
        }
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .form-footer a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .terms-checkbox input {
            width: auto;
            margin-top: 0.3rem;
        }
        .terms-checkbox label {
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.4;
            color: #94a3b8;
        }
        .terms-checkbox a {
            color: #8b5cf6;
            text-decoration: none;
        }
        .terms-checkbox a:hover {
            text-decoration: underline;
        }
        .icon {
            margin-right: 0.5rem;
        }
        ::placeholder {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="form-title">Join Zesigns Express</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle icon"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle icon"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="role">I want to:</label>
                <select name="role" id="role" required>
                    <option value="client" <?= ($_POST['role'] ?? 'client') == 'client' ? 'selected' : '' ?>>Hire Designers</option>
                    <option value="designer" <?= ($_POST['role'] ?? '') == 'designer' ? 'selected' : '' ?>>Work as Designer</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" 
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                       placeholder="Enter your full name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                       placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="At least 8 characters" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Confirm your password" required>
            </div>
            
            <div class="terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    I agree to the <a href="terms.php">Terms of Service</a> 
                    and <a href="privacy.php">Privacy Policy</a>
                </label>
            </div>
            
            <button type="submit">
                <i class="fas fa-user-plus icon"></i> Create Account
            </button>
        </form>
        
        <div class="form-footer">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>

    <script>
        // Password validation feedback
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const requirements = document.createElement('div');
            
            if (password.length < 8) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#10b981';
            }
        });

        // Confirm password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#ef4444';
            } else if (confirmPassword) {
                this.style.borderColor = '#10b981';
            } else {
                this.style.borderColor = '#334155';
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
            if (!terms) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
        });
    </script>
</body>
</html>