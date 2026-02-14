<?php if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
} ?>
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
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="orders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
        </ul>
        
        <div class="nav-title">Management</div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="services.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                    <i class="fas fa-palette"></i>
                    <span>Services</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portfolio.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'portfolio.php' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i>
                    <span>Portfolio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="messages.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                </a>
            </li>
        </ul>
        
        <div class="nav-title">Settings</div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
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