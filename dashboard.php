<?php
session_start();
require_once '../config/database.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get statistics
$stats = [
    'total_users' => $db->querySingle("SELECT COUNT(*) FROM users"),
    'total_orders' => $db->querySingle("SELECT COUNT(*) FROM orders"),
    'pending_orders' => $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'pending'"),
    'completed_orders' => $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'completed'"),
    'total_revenue' => $db->querySingle("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'") ?? 0,
    'active_projects' => $db->querySingle("SELECT COUNT(*) FROM orders WHERE status IN ('in_progress', 'review')")
];

// Get recent orders
$recent_orders = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");

// Get recent users
$recent_users = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Zesigns Express</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
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
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* SIDEBAR */
        .admin-sidebar {
            width: 260px;
            background: var(--dark-card);
            border-right: 1px solid var(--border-dark);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-dark);
            text-align: center;
        }
        
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-light);
            justify-content: center;
        }
        
        .admin-logo i {
            color: var(--primary);
            font-size: 2rem;
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .nav-title {
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .nav-menu {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1rem 1.5rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background: rgba(109, 40, 217, 0.1);
            color: var(--text-light);
            border-left-color: var(--primary);
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-dark);
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
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
        
        .user-details h4 {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .user-details span {
            font-size: 0.8rem;
            color: var(--primary);
            background: rgba(109, 40, 217, 0.1);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }
        
        /* MAIN CONTENT */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            padding: 0;
        }
        
        .admin-header {
            background: rgba(15, 15, 30, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-dark);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 99;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title h1 {
            font-size: 1.8rem;
            color: var(--text-light);
        }
        
        .header-title p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-btn,
        .logout-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .notification-btn:hover,
        .logout-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-btn {
            position: relative;
        }
        
        /* DASHBOARD CONTENT */
        .dashboard-content {
            padding: 2rem;
        }
        
        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid var(--border-dark);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.users { background: rgba(109, 40, 217, 0.1); color: var(--primary); }
        .stat-icon.orders { background: rgba(6, 182, 212, 0.1); color: var(--secondary); }
        .stat-icon.revenue { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-icon.projects { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        
        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .stat-trend.down {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        /* CHARTS & TABLES */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .chart-card,
        .table-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid var(--border-dark);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-header h3 {
            font-size: 1.3rem;
            color: var(--text-light);
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        /* TABLE STYLES */
        .table-responsive {
            overflow-x: auto;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th {
            text-align: left;
            padding: 1rem;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-dark);
        }
        
        .admin-table td {
            padding: 1rem;
            color: var(--text-light);
            border-bottom: 1px solid var(--border-dark);
        }
        
        .admin-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .status-in-progress { background: rgba(6, 182, 212, 0.1); color: var(--secondary); }
        .status-completed { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .status-cancelled { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-view,
        .btn-edit,
        .btn-delete {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }
        
        .btn-view { background: var(--primary); }
        .btn-edit { background: var(--warning); }
        .btn-delete { background: var(--danger); }
        
        .btn-view:hover,
        .btn-edit:hover,
        .btn-delete:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
        
        /* CHART CONTAINER */
        .chart-container {
            height: 300px;
            margin-top: 1rem;
        }
        
        .recent-activity {
            list-style: none;
        }
        
        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-dark);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(109, 40, 217, 0.1);
            color: var(--primary);
        }
        
        .activity-content h4 {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.2rem;
        }
        
        .activity-content p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-left: auto;
        }
        
        /* QUICK ACTIONS */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .quick-action {
            background: var(--dark-card);
            border: 1px solid var(--border-dark);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.3s ease;
        }
        
        .quick-action:hover {
            border-color: var(--primary);
            background: rgba(109, 40, 217, 0.1);
            transform: translateY(-3px);
        }
        
        .quick-action i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .quick-action h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .quick-action p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 80px;
            }
            
            .admin-main {
                margin-left: 80px;
            }
            
            .nav-title,
            .nav-link span,
            .user-details,
            .admin-logo span {
                display: none;
            }
            
            .nav-link {
                justify-content: center;
                padding: 1rem;
            }
            
            .sidebar-header {
                padding: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .dashboard-content {
                padding: 1rem;
            }
            
            .admin-header {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="dashboard.php" class="admin-logo">
                    <i class="fas fa-crown"></i>
                    <span>Admin Panel</span>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-title">Main</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="orders.php" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                            <span class="notification-badge"><?php echo $stats['pending_orders']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                </ul>
                
                <div class="nav-title" style="margin-top: 1.5rem;">Management</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="services.php" class="nav-link">
                            <i class="fas fa-palette"></i>
                            <span>Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="portfolio.php" class="nav-link">
                            <i class="fas fa-images"></i>
                            <span>Portfolio</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="messages.php" class="nav-link">
                            <i class="fas fa-comments"></i>
                            <span>Messages</span>
                            <span class="notification-badge">3</span>
                        </a>
                    </li>
                </ul>
                
                <div class="nav-title" style="margin-top: 1.5rem;">Settings</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="settings.php" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="reports.php" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <h4><?php echo $_SESSION['user']['name']; ?></h4>
                        <span>Admin</span>
                    </div>
                </div>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="admin-main">
            <!-- HEADER -->
            <header class="admin-header">
                <div class="header-title">
                    <h1>Dashboard Overview</h1>
                    <p>Welcome back, <?php echo $_SESSION['user']['name']; ?>! Here's what's happening today.</p>
                </div>
                
                <div class="header-actions">
                    <a href="#" class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </a>
                    <a href="../logout.php" class="logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </header>
            
            <!-- DASHBOARD CONTENT -->
            <div class="dashboard-content">
                <!-- STATS GRID -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon users">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                12%
                            </div>
                        </div>
                        <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon orders">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                24%
                            </div>
                        </div>
                        <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon revenue">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                18%
                            </div>
                        </div>
                        <div class="stat-value">₦<?php echo number_format($stats['total_revenue'], 0); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon projects">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="stat-trend down">
                                <i class="fas fa-arrow-down"></i>
                                5%
                            </div>
                        </div>
                        <div class="stat-value"><?php echo $stats['active_projects']; ?></div>
                        <div class="stat-label">Active Projects</div>
                    </div>
                </div>
                
                <!-- MAIN GRID -->
                <div class="dashboard-grid">
                    <!-- RECENT ORDERS -->
                    <div class="chart-card">
                        <div class="card-header">
                            <h3>Recent Orders</h3>
                            <div class="card-actions">
                                <a href="orders.php" class="action-btn">View All</a>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $recent_orders->fetchArray(SQLITE3_ASSOC)): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                                        <td>₦<?php echo number_format($order['total_amount'], 0); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo str_replace(' ', '-', strtolower($order['status'])); ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="order_view.php?id=<?php echo $order['id']; ?>" class="btn-view" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="order_edit.php?id=<?php echo $order['id']; ?>" class="btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- RECENT USERS -->
                    <div class="table-card">
                        <div class="card-header">
                            <h3>Recent Users</h3>
                            <div class="card-actions">
                                <a href="users.php" class="action-btn">View All</a>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = $recent_users->fetchArray(SQLITE3_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $user['role'] == 'admin' ? 'status-completed' : 'status-pending'; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="user_view.php?id=<?php echo $user['id']; ?>" class="btn-view" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- QUICK ACTIONS -->
                <div class="quick-actions">
                    <a href="order_create.php" class="quick-action">
                        <i class="fas fa-plus-circle"></i>
                        <h4>Create New Order</h4>
                        <p>Add a new customer order</p>
                    </a>
                    
                    <a href="user_create.php" class="quick-action">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add New User</h4>
                        <p>Create a new user account</p>
                    </a>
                    
                    <a href="reports.php" class="quick-action">
                        <i class="fas fa-chart-pie"></i>
                        <h4>Generate Report</h4>
                        <p>View detailed reports</p>
                    </a>
                    
                    <a href="settings.php" class="quick-action">
                        <i class="fas fa-cogs"></i>
                        <h4>System Settings</h4>
                        <p>Configure system settings</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Simple chart using Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            // This would normally use Chart.js library
            // For simplicity, we'll just show a placeholder
            const chartContainer = document.querySelector('.chart-container');
            if (chartContainer) {
                chartContainer.innerHTML = `
                    <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                        <div style="text-align: center;">
                            <i class="fas fa-chart-line" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <p>Chart visualization would appear here</p>
                            <p style="font-size: 0.8rem;">(Requires Chart.js library)</p>
                        </div>
                    </div>
                `;
            }
            
            // Update notification badge
            setInterval(() => {
                const badge = document.querySelector('.notification-badge');
                if (badge && Math.random() > 0.7) {
                    const count = parseInt(badge.textContent) + 1;
                    badge.textContent = count;
                    badge.style.animation = 'pulse 0.5s';
                    setTimeout(() => badge.style.animation = '', 500);
                }
            }, 30000);
        });
        <!-- In dashboard.php or any protected page -->
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Your existing head content -->
    <style>
        /* Add this to your dashboard CSS */
        .logout-btn {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .logout-modal-content {
            background: rgba(26, 26, 46, 0.95);
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <!-- Your dashboard content -->
    
    <!-- Logout Button in Header/Navbar -->
    <div class="user-menu">
        <button onclick="showLogoutModal()" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-modal-content">
            <i class="fas fa-sign-out-alt" style="font-size: 3rem; color: #ef4444; margin-bottom: 1rem;"></i>
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to logout from your account?</p>
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button onclick="logout()" class="btn btn-primary" style="background: #ef4444;">
                    Yes, Logout
                </button>
                <button onclick="hideLogoutModal()" class="btn btn-secondary">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function logout() {
            window.location.href = '../auth/logout.php';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                hideLogoutModal();
            }
        });
    </script>
</body>
</html>
                                                'name' => 'Admin',
                                                'email' => $email,
                                                'role' => 'admin'
                                            ];
        header('Location: ../admin/dashboard.php');
        exit();
    }
    </script>
</body>
</html>