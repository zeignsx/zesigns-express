<?php
session_start();
require_once 'config/database.php';

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = db();
$user_id = $_SESSION['user_id'];

// Get user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Get statistics
$stats = [
    'total_users' => $db->querySingle("SELECT COUNT(*) FROM users"),
    'total_orders' => $db->querySingle("SELECT COUNT(*) FROM orders"),
    'pending_orders' => $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'pending'"),
    'total_revenue' => $db->querySingle("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status = 'completed'")
];

// Get recent activities
$activities = $db->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10");

// Get recent users
$recent_users = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");

// Get recent orders
$recent_orders = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Zesigns Express Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --dark-card: #1e293b;
            --dark-hover: #334155;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --gradient: linear-gradient(135deg, #6366f1, #0ea5e9);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text-light);
            min-height: 100vh;
        }

        /* Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: var(--dark-card);
            border-right: 1px solid var(--border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 25px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-header .logo i {
            font-size: 32px;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .sidebar-header .logo span {
            font-size: 20px;
            font-weight: 700;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .sidebar-header p {
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 5px;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-title {
            padding: 15px 25px 5px;
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: var(--dark-hover);
            color: var(--text-light);
            border-left-color: var(--primary);
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .nav-link .badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 11px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px 25px;
            border-top: 1px solid var(--border);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: var(--gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
            color: white;
        }

        .user-details h4 {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .user-details span {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            padding: 0;
        }

        /* Header */
        .admin-header {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-search {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 8px 15px;
            width: 300px;
        }

        .header-search i {
            color: var(--text-muted);
            margin-right: 10px;
        }

        .header-search input {
            background: none;
            border: none;
            color: var(--text-light);
            width: 100%;
            outline: none;
        }

        .header-search input::placeholder {
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-action {
            position: relative;
            cursor: pointer;
        }

        .header-action i {
            font-size: 20px;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .header-action:hover i {
            color: var(--text-light);
        }

        .action-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 10px;
        }

        /* Content */
        .admin-content {
            padding: 30px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 0;
        }

        .stat-card:hover::before {
            opacity: 0.05;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.users {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .stat-icon.orders {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
        }

        .stat-icon.pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-icon.revenue {
            background: rgba(14, 165, 233, 0.1);
            color: var(--secondary);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        .stat-change {
            position: absolute;
            top: 25px;
            right: 25px;
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .card-header select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            color: var(--text-light);
            outline: none;
            cursor: pointer;
        }

        /* Tables */
        .tables-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .table-card {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            text-align: left;
            padding: 12px;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 13px;
            border-bottom: 1px solid var(--border);
        }

        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }

        .admin-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-completed {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-processing {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .user-avatar-sm {
            width: 32px;
            height: 32px;
            background: var(--gradient);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: white;
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .view-all:hover {
            color: var(--primary-light);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-row {
                grid-template-columns: 1fr;
            }
            
            .tables-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                width: 80px;
            }
            
            .sidebar-header .logo span,
            .sidebar-header p,
            .nav-link span,
            .user-details {
                display: none;
            }
            
            .admin-main {
                margin-left: 80px;
            }
            
            .nav-link {
                justify-content: center;
                padding: 15px;
            }
            
            .nav-link i {
                margin: 0;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-header {
                padding: 15px;
            }
            
            .header-search {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="logo">
                    <i class="fas fa-crown"></i>
                    <span>Zesigns Admin</span>
                </a>
                <p>v1.0.0</p>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-title">Main</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="orders.php" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                            <span class="badge"><?php echo $stats['pending_orders']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-title">Management</div>
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
                        </a>
                    </li>
                </ul>

                <div class="nav-title">Settings</div>
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
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                        <span><?php echo ucfirst($user['role']); ?></span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>

                <div class="header-actions">
                    <div class="header-action">
                        <i class="fas fa-bell"></i>
                        <span class="action-badge">3</span>
                    </div>
                    <div class="header-action">
                        <i class="fas fa-envelope"></i>
                        <span class="action-badge">5</span>
                    </div>
                    <div class="header-action">
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                <!-- Welcome Section -->
                <div style="margin-bottom: 30px;">
                    <h1 style="font-size: 28px; margin-bottom: 5px;">Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
                    <p style="color: var(--text-muted);">Here's what's happening with your store today.</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon users">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-up"></i> 12%
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
                            <div class="stat-change">
                                <i class="fas fa-arrow-up"></i> 8%
                            </div>
                        </div>
                        <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-down"></i> 5%
                            </div>
                        </div>
                        <div class="stat-value"><?php echo $stats['pending_orders']; ?></div>
                        <div class="stat-label">Pending Orders</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon revenue">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-up"></i> 15%
                            </div>
                        </div>
                        <div class="stat-value">₦<?php echo number_format($stats['total_revenue'], 0); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="charts-row">
                    <div class="chart-card">
                        <div class="card-header">
                            <h3>Sales Overview</h3>
                            <select>
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 90 days</option>
                            </select>
                        </div>
                        <canvas id="salesChart" style="height: 300px;"></canvas>
                    </div>

                    <div class="chart-card">
                        <div class="card-header">
                            <h3>Traffic Sources</h3>
                        </div>
                        <canvas id="trafficChart" style="height: 300px;"></canvas>
                    </div>
                </div>

                <!-- Tables Row -->
                <div class="tables-row">
                    <!-- Recent Orders -->
                    <div class="table-card">
                        <div class="card-header">
                            <h3>Recent Orders</h3>
                            <a href="orders.php" class="view-all">View All</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $recent_orders->fetchArray(SQLITE3_ASSOC)): ?>
                                    <tr>
                                        <td>#<?php echo $order['order_id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td>₦<?php echo number_format($order['total_amount'], 0); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="table-card">
                        <div class="card-header">
                            <h3>New Users</h3>
                            <a href="users.php" class="view-all">View All</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($recent_user = $recent_users->fetchArray(SQLITE3_ASSOC)): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div class="user-avatar-sm">
                                                    <?php echo strtoupper(substr($recent_user['full_name'], 0, 1)); ?>
                                                </div>
                                                <?php echo htmlspecialchars($recent_user['full_name']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo $recent_user['email']; ?></td>
                                        <td>
                                            <span class="status-badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                                <?php echo ucfirst($recent_user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d', strtotime($recent_user['created_at'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sales',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Traffic Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        new Chart(trafficCtx, {
            type: 'doughnut',
            data: {
                labels: ['Direct', 'Social', 'Organic', 'Referral'],
                datasets: [{
                    data: [45, 25, 20, 10],
                    backgroundColor: ['#6366f1', '#0ea5e9', '#22c55e', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>