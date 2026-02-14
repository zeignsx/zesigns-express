<?php
session_start();
require_once '../config/database.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle order actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    switch ($action) {
        case 'delete':
            $stmt = $db->prepare("DELETE FROM orders WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $stmt->execute();
            $_SESSION['message'] = 'Order deleted successfully';
            break;
            
        case 'complete':
            $stmt = $db->prepare("UPDATE orders SET status = 'completed', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $stmt->execute();
            $_SESSION['message'] = 'Order marked as completed';
            break;
    }
    
    header('Location: orders.php');
    exit();
}

// Get orders with filters
$status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM orders WHERE 1=1";
$params = [];

if ($status !== 'all') {
    $query .= " AND status = :status";
    $params[':status'] = $status;
}

if (!empty($search)) {
    $query .= " AND (order_id LIKE :search OR customer_name LIKE :search OR email LIKE :search OR service_name LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$orders = $stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin Panel</title>
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
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 260px;
            background: var(--dark-card);
            border-right: 1px solid var(--border-dark);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-main {
            flex: 1;
            margin-left: 260px;
            padding: 0;
        }
        
        .admin-header {
            background: rgba(15, 15, 30, 0.95);
            border-bottom: 1px solid var(--border-dark);
            padding: 1rem 2rem;
        }
        
        .page-content {
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title h1 {
            font-size: 2rem;
            color: var(--text-light);
        }
        
        .page-actions {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .filters {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-dark);
        }
        
        .filter-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }
        
        .filter-select,
        .filter-input {
            width: 100%;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            color: var(--text-light);
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }
        
        .table-card {
            background: var(--dark-card);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid var(--border-dark);
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th {
            padding: 1rem;
            text-align: left;
            color: var(--text-muted);
            font-weight: 500;
            border-bottom: 1px solid var(--border-dark);
        }
        
        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-dark);
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .status-in-progress { background: rgba(6, 182, 212, 0.1); color: var(--secondary); }
        .status-completed { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        
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
        }
        
        .btn-view { background: var(--primary); }
        .btn-edit { background: var(--warning); }
        .btn-delete { background: var(--danger); }
        
        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .message.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }
        
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 80px;
            }
            
            .admin-main {
                margin-left: 80px;
            }
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .filter-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- MAIN CONTENT -->
        <main class="admin-main">
            <!-- HEADER -->
            <header class="admin-header">
                <div class="header-title">
                    <h1>Manage Orders</h1>
                    <p>View and manage all customer orders</p>
                </div>
            </header>
            
            <!-- CONTENT -->
            <div class="page-content">
                <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
                <?php endif; ?>
                
                <div class="page-header">
                    <div class="page-title">
                        <h1>All Orders</h1>
                        <p style="color: var(--text-muted);">Total: <?php echo $db->querySingle("SELECT COUNT(*) FROM orders"); ?> orders</p>
                    </div>
                    
                    <div class="page-actions">
                        <a href="order_create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Order
                        </a>
                        <a href="export.php?type=orders" class="btn">
                            <i class="fas fa-download"></i> Export
                        </a>
                    </div>
                </div>
                
                <!-- FILTERS -->
                <div class="filters">
                    <form method="GET" class="filter-row">
                        <div class="filter-group">
                            <label>Status</label>
                            <select name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="all" <?php echo $status == 'all' ? 'selected' : ''; ?>>All Status</option>
                                <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="in_progress" <?php echo $status == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="review" <?php echo $status == 'review' ? 'selected' : ''; ?>>Under Review</option>
                                <option value="completed" <?php echo $status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Search</label>
                            <input type="text" name="search" class="filter-input" placeholder="Search orders..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        
                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="orders.php" class="btn">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- ORDERS TABLE -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>Order List</h3>
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
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $orders->fetchArray(SQLITE3_ASSOC)): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                        <small style="color: var(--text-muted);"><?php echo $order['email']; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                                    <td>₦<?php echo number_format($order['total_amount'], 0); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo str_replace(' ', '-', $order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="order_view.php?id=<?php echo $order['id']; ?>" class="btn-view" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="order_edit.php?id=<?php echo $order['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($order['status'] !== 'completed'): ?>
                                            <a href="orders.php?action=complete&id=<?php echo $order['id']; ?>" class="btn-view" title="Complete" onclick="return confirm('Mark this order as completed?')">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="orders.php?action=delete&id=<?php echo $order['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
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
        </main>
    </div>
</body>
</html>