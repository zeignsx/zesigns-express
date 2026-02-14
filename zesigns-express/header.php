<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize user session if not set
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 0,
        'name' => 'Guest',
        'email' => 'guest@zeesigns.com',
        'role' => 'guest'
    ];
}

// Initialize orders array if not set
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

// Get order count
$order_count = count($_SESSION['orders']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zesigns Express - Professional Design Services</title>
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
            padding-top: 80px; /* Space for fixed header */
        }
        
        .main-header {
            background: rgba(15, 15, 30, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-dark);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 1rem 0;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-light);
        }
        
        .logo i {
            color: var(--primary);
            font-size: 2rem;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2.5rem;
            margin: 0;
            padding: 0;
        }
        
        .nav-menu a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }
        
        .nav-menu a:hover,
        .nav-menu a.active {
            color: var(--text-light);
        }
        
        .nav-menu a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary);
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .nav-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
        }
        
        .nav-user i {
            color: var(--primary);
        }
        
        .btn-login,
        .btn-signup {
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-login {
            color: var(--text-light);
            border: 1px solid var(--border-dark);
        }
        
        .btn-login:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
        }
        
        .btn-signup {
            background: var(--primary);
            color: white;
        }
        
        .btn-signup:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .order-count {
            background: var(--primary);
            color: white;
            font-size: 0.8rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 5px;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .mobile-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: var(--dark-card);
            padding: 1rem;
            border-top: 1px solid var(--border-dark);
            z-index: 1000;
        }
        
        .mobile-nav.active {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .mobile-nav a {
            color: var(--text-muted);
            text-decoration: none;
            padding: 0.8rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .mobile-nav a:hover,
        .mobile-nav a.active {
            color: var(--text-light);
            background: rgba(255, 255, 255, 0.05);
        }
        
        @media (max-width: 992px) {
            .nav-menu {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-actions {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 15px;
            }
            
            .logo span {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-palette"></i>
                <span>Zesigns Express</span>
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-menu">
                <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Home</a></li>
                <li><a href="services.php" <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'class="active"' : ''; ?>>Services</a></li>
                <li><a href="portfolio.php" <?php echo basename($_SERVER['PHP_SELF']) == 'portfolio.php' ? 'class="active"' : ''; ?>>Portfolio</a></li>
                <li><a href="about.php" <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'class="active"' : ''; ?>>About</a></li>
                <li><a href="contact.php" <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'class="active"' : ''; ?>>Contact</a></li>
                <li>
                    <a href="my_orders.php" <?php echo basename($_SERVER['PHP_SELF']) == 'my_orders.php' ? 'class="active"' : ''; ?>>My Orders</a>
                    <?php if ($order_count > 0): ?>
                    <span class="order-count"><?php echo $order_count; ?></span>
                    <?php endif; ?>
                </li>
            </ul>
            
            <div class="nav-actions">
                <?php if ($_SESSION['user']['role'] !== 'guest'): ?>
                <a href="dashboard.php" class="nav-user">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                </a>
                <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                <a href="admin/dashboard.php" class="btn-signup">
                    <i class="fas fa-crown"></i> Admin
                </a>
                <?php endif; ?>
                <a href="logout.php" class="btn-login">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <?php else: ?>
                <a href="login.php" class="btn-login">Login</a>
                <a href="register.php" class="btn-signup">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
        
        <!-- Mobile Navigation -->
        <div class="mobile-nav" id="mobileNav">
            <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Home</a>
            <a href="services.php" <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'class="active"' : ''; ?>>Services</a>
            <a href="portfolio.php" <?php echo basename($_SERVER['PHP_SELF']) == 'portfolio.php' ? 'class="active"' : ''; ?>>Portfolio</a>
            <a href="about.php" <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'class="active"' : ''; ?>>About</a>
            <a href="contact.php" <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'class="active"' : ''; ?>>Contact</a>
            <a href="my_orders.php" <?php echo basename($_SERVER['PHP_SELF']) == 'my_orders.php' ? 'class="active"' : ''; ?>>
                My Orders <?php if ($order_count > 0): ?><span class="order-count"><?php echo $order_count; ?></span><?php endif; ?>
            </a>
            
            <?php if ($_SESSION['user']['role'] !== 'guest'): ?>
            <a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>
                <i class="fas fa-user"></i> Dashboard
            </a>
            <?php if ($_SESSION['user']['role'] == 'admin'): ?>
            <a href="admin/dashboard.php">
                <i class="fas fa-crown"></i> Admin Panel
            </a>
            <?php endif; ?>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const mobileNav = document.getElementById('mobileNav');
            mobileNav.classList.toggle('active');
            this.innerHTML = mobileNav.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileNav = document.getElementById('mobileNav');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            
            if (mobileNav.classList.contains('active') && 
                !mobileNav.contains(event.target) && 
                !mobileBtn.contains(event.target)) {
                mobileNav.classList.remove('active');
                mobileBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    </script>