<?php
session_start();

// Get the last order ID
$order_id = isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : '';

// Find the order details
$order_details = null;
if (isset($_SESSION['orders']) && $order_id) {
    foreach ($_SESSION['orders'] as $order) {
        if ($order['order_id'] === $order_id) {
            $order_details = $order;
            break;
        }
    }
}

// If no order found, redirect to services
if (!$order_details) {
    header('Location: services.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Zesigns Express</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .success-card {
            background: var(--dark-card);
            border-radius: 25px;
            padding: 4rem;
            border: 1px solid var(--border-dark);
            text-align: center;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .success-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(16, 185, 129, 0.1), transparent);
            transform: translateX(-100%);
            animation: shine 2s infinite;
        }
        
        @keyframes shine {
            100% { transform: translateX(100%); }
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--success), #0ca678);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
            color: white;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .success-card h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, var(--success), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .success-card p {
            color: var(--text-muted);
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .order-details {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-dark);
        }
        
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            color: var(--text-muted);
            font-weight: 500;
        }
        
        .detail-value {
            color: var(--text-light);
            font-weight: 600;
        }
        
        .order-id {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.2rem;
            display: inline-block;
            margin-bottom: 2rem;
            letter-spacing: 1px;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
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
        
        .btn.secondary {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--primary-light);
        }
        
        .btn.secondary:hover {
            background: rgba(109, 40, 217, 0.1);
            transform: translateY(-3px);
            border-color: var(--primary);
        }
        
        .confetti {
            position: fixed;
            width: 15px;
            height: 15px;
            background: var(--primary);
            animation: confetti-fall 5s linear infinite;
            z-index: 1000;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 3rem 2rem;
            }
            
            .success-card h1 {
                font-size: 2.5rem;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .success-card h1 {
                font-size: 2rem;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <h1>Order Confirmed!</h1>
            <p>Thank you for choosing Zesigns Express. Your order has been successfully placed and our team will contact you within 24 hours.</p>
            
            <div class="order-id"><?php echo $order_details['order_id']; ?></div>
            
            <div class="order-details">
                <div class="detail-item">
                    <span class="detail-label">Service:</span>
                    <span class="detail-value"><?php echo $order_details['service_name']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Customer:</span>
                    <span class="detail-value"><?php echo $order_details['customer_name']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo $order_details['email']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value"><?php echo date('F j, Y', strtotime($order_details['order_date'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: var(--accent);"><?php echo ucfirst($order_details['status']); ?></span>
                </div>
            </div>
            
            <div class="btn-group">
                <a href="my_orders.php" class="btn primary">
                    <i class="fas fa-clipboard-list"></i> View My Orders
                </a>
                <a href="services.php" class="btn secondary">
                    <i class="fas fa-plus"></i> Order Another Service
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Create confetti effect
        function createConfetti() {
            const colors = ['#6d28d9', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 5 + 's';
                confetti.style.width = Math.random() * 20 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                
                document.body.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }
        }
        
        // Create confetti on page load
        window.addEventListener('load', () => {
            createConfetti();
            // Keep creating confetti every 2 seconds for 10 seconds
            let confettiInterval = setInterval(createConfetti, 2000);
            setTimeout(() => clearInterval(confettiInterval), 10000);
        });
    </script>
</body>
</html>