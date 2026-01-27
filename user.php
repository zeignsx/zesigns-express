<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle user actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    if ($action == 'delete' && $id != $_SESSION['user']['id']) {
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
        $_SESSION['message'] = 'User deleted successfully';
    }
    
    header('Location: users.php');
    exit();
}

$users = $db->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
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
        
        .page-content {
            padding: 2rem;
        }
        
        .user-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid var(--border-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-info h3 {
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        
        .user-meta {
            display: flex;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .user-role {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .role-admin {
            background: rgba(109, 40, 217, 0.1);
            color: var(--primary);
        }
        
        .role-user {
            background: rgba(94, 166, 255, 0.1);
            color: #5ea6ff;
        }
        
        .user-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 80px;
            }
            
            .admin-main {
                margin-left: 80px;
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
            <header class="admin-header">
                <div class="header-title">
                    <h1>Manage Users</h1>
                    <p>View and manage all user accounts</p>
                </div>
                
                <div class="page-actions">
                    <a href="user_create.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add User
                    </a>
                </div>
            </header>
            
            <div class="page-content">
                <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
                <?php endif; ?>
                
                <div class="users-grid">
                    <?php while ($user = $users->fetchArray(SQLITE3_ASSOC)): ?>
                    <div class="user-card">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                        
                        <div class="user-info">
                            <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                            <div class="user-meta">
                                <span><i class="fas fa-envelope"></i> <?php echo $user['email']; ?></span>
                                <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                            </div>
                        </div>
                        
                        <div class="user-role role-<?php echo $user['role']; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </div>
                        
                        <div class="user-actions">
                            <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                            <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user?')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>