<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];
$user_orders = $db->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Zesigns Express</title>
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
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
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
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }
        
        .dashboard-hero {
            background: linear-gradient(135deg, #0a0a15 0%, #151533 100%);
            padding: 140px 0 80px;
            margin-top: 0;
        }
        
        .welcome-card {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            border: 1px solid var(--border-dark);
            margin-bottom: 3rem;
        }
        
        .welcome-card h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid var(--border-dark);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-light);
            margin-bottom: 0.5rem;
        }
        
        .orders-table {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid var(--border-dark);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            text-align: left;
            padding: 1rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-dark);
        }
        
        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-dark);
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    <i class="fas fa-palette"></i>
                    <span>Zesigns Express</span>
                </a>
                
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="my_orders.php">My Orders</a></li>
                </ul>
                
                <div class="nav-actions">
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 1)); ?>
                        </div>
                        <span><?php echo $_SESSION['user']['name']; ?></span>
                    </div>
                    <a href="logout.php" class="btn-primary">Logout</a>
                </div>
            </div>
        </nav>
    </header>
    
    <section class="dashboard-hero">
        <div class="container">
            <div class="welcome-card">
                <h1>Welcome back, <?php echo $_SESSION['user']['name']; ?>!</h1>
                <p style="color: var(--text-muted);">Here's your design service dashboard</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $db->querySingle("SELECT COUNT(*) FROM orders WHERE user_id = $user_id"); ?></div>
                    <div style="color: var(--text-muted);">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $db->querySingle("SELECT COUNT(*) FROM orders WHERE user_id = $user_id AND status = 'completed'"); ?></div>
                    <div style="color: var(--text-muted);">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $db->querySingle("SELECT COUNT(*) FROM orders WHERE user_id = $user_id AND status = 'in_progress'"); ?></div>
                    <div style="color: var(--text-muted);">In Progress</div>
                </div>
            </div>
            
            <div class="orders-table">
                <h2 style="margin-bottom: 1.5rem;">Recent Orders</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $user_orders->fetchArray(SQLITE3_ASSOC)): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['service_name']; ?></td>
                            <td>
                                <span style="
                                    padding: 0.3rem 0.8rem;
                                    border-radius: 20px;
                                    background: rgba(109, 40, 217, 0.1);
                                    color: var(--primary);
                                    font-size: 0.8rem;
                                ">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>₦<?php echo number_format($order['total_amount'], 0); ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="order_view.php?id=<?php echo $order['id']; ?>" class="btn-primary">View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>