<?php
session_start();

// Initialize orders array if not exists
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

$orders = $_SESSION['orders'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Zesigns Express</title>
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
            --shadow-dark: rgba(0, 0, 0, 0.3);
            --shadow-light: rgba(109, 40, 217, 0.2);
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
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-dark);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        
        .orders-hero {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1e1b4b 100%);
            padding: 140px 0 60px;
            text-align: center;
        }
        
        .orders-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .orders-hero p {
            color: var(--text-muted);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .orders-container {
            padding: 3rem 0;
        }
        
        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .orders-header h2 {
            font-size: 2rem;
            color: var(--text-light);
        }
        
        .order-count {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .orders-grid {
            display: grid;
            gap: 2rem;
        }
        
        .order-card {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid var(--border-dark);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .order-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(109, 40, 217, 0.2);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .order-id {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-light);
            letter-spacing: 1px;
        }
        
        .order-date {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .order-service {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        
        .order-status {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .status-in-progress {
            background: rgba(6, 182, 212, 0.1);
            color: var(--secondary);
            border: 1px solid rgba(6, 182, 212, 0.3);
        }
        
        .status-completed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .status-cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .order-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .detail-box {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
        }
        
        .detail-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .detail-value {
            color: var(--text-light);
            font-weight: 500;
        }
        
        .order-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .action-btn.view {
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .action-btn.view:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .action-btn.track {
            background: transparent;
            color: var(--text-light);
            border: 1px solid var(--border-dark);
        }
        
        .action-btn.track:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
        }
        
        .action-btn.cancel {
            background: transparent;
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        
        .action-btn.cancel:hover {
            background: rgba(239, 68, 68, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 6rem 2rem;
            background: var(--dark-card);
            border-radius: 20px;
            border: 2px dashed var(--border-dark);
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
        
        .empty-state h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--text-light);
        }
        
        .empty-state p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            max-width: 500px;
            margin: 0 auto 2rem;
        }
        
        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s ease;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
        }
        
        .btn.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.4);
        }
        
        .btn.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(109, 40, 217, 0.6);
        }
        
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.6rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 50px;
            color: var(--text-muted);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: transparent;
        }
        
        @media (max-width: 768px) {
            .orders-hero h1 {
                font-size: 2.5rem;
            }
            
            .order-header {
                flex-direction: column;
            }
            
            .order-details-grid {
                grid-template-columns: 1fr;
            }
            
            .order-actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .orders-hero h1 {
                font-size: 2rem;
            }
            
            .order-card {
                padding: 2rem;
            }
            
            .order-service {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="orders-hero">
        <div class="container">
            <h1>My Orders</h1>
            <p>Track and manage all your design service orders in one place</p>
        </div>
    </section>
    
    <div class="container">
        <div class="orders-container">
            <div class="orders-header">
                <h2>Order History</h2>
                <div class="order-count"><?php echo count($orders); ?> Order<?php echo count($orders) !== 1 ? 's' : ''; ?></div>
            </div>
            
            <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Browse our services and place your first order to get started!</p>
                <a href="services.php" class="btn primary">
                    <i class="fas fa-shopping-cart"></i> Browse Services
                </a>
            </div>
            <?php else: ?>
            <div class="filters">
                <button class="filter-btn active" data-filter="all">All Orders</button>
                <button class="filter-btn" data-filter="pending">Pending</button>
                <button class="filter-btn" data-filter="in-progress">In Progress</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
            </div>
            
            <div class="orders-grid">
                <?php foreach (array_reverse($orders) as $order): ?>
                <?php 
                // Determine status class
                $status_class = 'status-' . str_replace(' ', '-', $order['status']);
                ?>
                <div class="order-card" data-status="<?php echo $order['status']; ?>">
                    <div class="order-header">
                        <div>
                            <div class="order-id"><?php echo $order['order_id']; ?></div>
                            <div class="order-date"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></div>
                        </div>
                        <div class="order-status <?php echo $status_class; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </div>
                    </div>
                    
                    <div class="order-service"><?php echo $order['service_name']; ?></div>
                    
                    <div class="order-details-grid">
                        <div class="detail-box">
                            <span class="detail-label">Customer</span>
                            <span class="detail-value"><?php echo $order['customer_name']; ?></span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?php echo $order['email']; ?></span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?php echo $order['phone']; ?></span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Company</span>
                            <span class="detail-value"><?php echo $order['company'] ?: 'N/A'; ?></span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Industry</span>
                            <span class="detail-value"><?php echo ucfirst($order['industry']); ?></span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Deadline</span>
                            <span class="detail-value">
                                <?php 
                                switch($order['deadline']) {
                                    case 'urgent': echo 'Urgent (3-5 days)'; break;
                                    case '1-week': echo '1 Week'; break;
                                    case '2-weeks': echo '2 Weeks'; break;
                                    case '3-weeks': echo '3 Weeks'; break;
                                    case '1-month': echo '1 Month'; break;
                                    case 'flexible': echo 'Flexible'; break;
                                    default: echo 'N/A';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <a href="#" class="action-btn view">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="#" class="action-btn track">
                            <i class="fas fa-shipping-fast"></i> Track Progress
                        </a>
                        <?php if ($order['status'] === 'pending'): ?>
                        <a href="#" class="action-btn cancel" onclick="cancelOrder('<?php echo $order['order_id']; ?>')">
                            <i class="fas fa-times"></i> Cancel Order
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const orderCards = document.querySelectorAll('.order-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                const filter = button.dataset.filter;
                
                // Show/hide orders
                orderCards.forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Cancel order function
        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                // In a real application, this would be an AJAX call to the server
                alert(`Order ${orderId} has been cancelled.`);
                location.reload();
            }
        }
        
        // View details functionality
        document.querySelectorAll('.action-btn.view').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderCard = this.closest('.order-card');
                const orderId = orderCard.querySelector('.order-id').textContent;
                
                // Show order details in a modal (simplified for demo)
                alert(`Viewing details for order: ${orderId}\n\nIn a full implementation, this would open a detailed view or modal with complete order information, progress updates, and communication history.`);
            });
        });
        
        // Track progress functionality
        document.querySelectorAll('.action-btn.track').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderCard = this.closest('.order-card');
                const orderId = orderCard.querySelector('.order-id').textContent;
                
                // Show tracking information
                alert(`Tracking progress for order: ${orderId}\n\nCurrent Status: ${orderCard.querySelector('.order-status').textContent}\n\nEstimated Completion: Within 7-10 business days\n\nDesigner Assigned: John Smith\n\nNext Milestone: Initial Concepts Delivery`);
            });
        });
    </script>
</body>
</html>