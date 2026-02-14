<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - Zesigns Express</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 50%, #16213e 100%);
            color: white;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(109, 40, 217, 0.1), rgba(59, 130, 246, 0.1));
            filter: blur(40px);
            animation: float 20s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 500px;
            height: 500px;
            top: -250px;
            left: -250px;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -200px;
            right: -200px;
            animation-delay: -7s;
            background: linear-gradient(45deg, rgba(236, 72, 153, 0.1), rgba(139, 92, 246, 0.1));
        }

        .shape:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 30%;
            right: 15%;
            animation-delay: -14s;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(50px, 80px) rotate(120deg) scale(1.1);
            }
            66% {
                transform: translate(-40px, 60px) rotate(240deg) scale(0.9);
            }
        }

        /* Glassmorphism Container */
        .logout-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 4rem 3rem;
            width: 100%;
            max-width: 500px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transform: perspective(1000px);
            transition: transform 0.5s ease;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(109, 40, 217, 0.1),
                inset 0 0 60px rgba(255, 255, 255, 0.05);
        }

        .logout-container:hover {
            transform: perspective(1000px) rotateX(2deg) rotateY(2deg);
        }

        /* Glowing Border */
        .logout-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                #6d28d9, 
                #8b5cf6, 
                #ec4899, 
                #f59e0b,
                #10b981);
            border-radius: 32px;
            z-index: -1;
            filter: blur(25px);
            opacity: 0.4;
            animation: borderGlow 10s linear infinite;
        }

        @keyframes borderGlow {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        /* Icon Animation */
        .logout-icon {
            font-size: 5rem;
            color: transparent;
            background: linear-gradient(45deg, #8b5cf6, #ec4899, #f59e0b);
            -webkit-background-clip: text;
            background-clip: text;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            animation: iconFloat 3s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        .logout-icon::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.2) 0%, transparent 70%);
            z-index: -1;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.5;
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                opacity: 0.8;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }

        /* Text Styles */
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #94a3b8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 800;
        }

        .message {
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        /* Buttons Container */
        .buttons-container {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Button Styles */
        .btn {
            flex: 1;
            padding: 1.2rem;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transform-style: preserve-3d;
        }

        .btn-primary {
            background: linear-gradient(45deg, #6d28d9, #8b5cf6, #ec4899);
            background-size: 200% 200%;
            color: white;
            animation: gradientShift 4s ease infinite;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
        }

        .btn-primary:hover {
            box-shadow: 
                0 15px 30px rgba(139, 92, 246, 0.4),
                0 0 0 2px rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 15px 30px rgba(255, 255, 255, 0.1),
                0 0 0 2px rgba(255, 255, 255, 0.05);
        }

        .btn:active {
            transform: translateY(-1px) scale(0.98);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn:hover::after {
            left: 100%;
        }

        /* Countdown Timer */
        .countdown {
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .countdown-text {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .countdown-timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8b5cf6;
            font-family: monospace;
        }

        /* Session Info (for debugging) */
        .session-info {
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            font-size: 0.8rem;
            color: #64748b;
            backdrop-filter: blur(10px);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .logout-container {
                padding: 3rem 2rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .buttons-container {
                flex-direction: column;
            }
            
            .logout-icon {
                font-size: 4rem;
            }
        }

        @media (max-width: 400px) {
            .logout-container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .message {
                font-size: 1rem;
            }
        }

        /* Floating Particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(139, 92, 246, 0.6);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            to {
                transform: translateY(-100vh) rotate(360deg);
            }
        }

        /* Success Animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #10b981;
            opacity: 0;
            animation: checkmark 0.5s ease-in-out 0.3s forwards;
        }

        .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 0.5s ease-in-out forwards;
        }

        .check-icon::before, .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: #0f0f1e;
            transform: rotate(-45deg);
        }

        .icon-line {
            height: 5px;
            background-color: #10b981;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.5s ease forwards;
        }

        .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.5s ease 0.2s forwards;
        }

        @keyframes rotate-circle {
            0% { transform: rotate(-45deg); }
            5% { transform: rotate(-45deg); }
            12% { transform: rotate(-405deg); }
            100% { transform: rotate(-405deg); }
        }

        @keyframes checkmark {
            0% { opacity: 0; transform: scale(0.3); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes icon-line-tip {
            0% { width: 0; left: 1px; top: 19px; }
            54% { width: 0; left: 1px; top: 19px; }
            70% { width: 50px; left: -8px; top: 37px; }
            84% { width: 17px; left: 21px; top: 48px; }
            100% { width: 25px; left: 14px; top: 45px; }
        }

        @keyframes icon-line-long {
            0% { width: 0; right: 46px; top: 54px; }
            65% { width: 0; right: 46px; top: 54px; }
            84% { width: 55px; right: 0px; top: 35px; }
            100% { width: 47px; right: 8px; top: 38px; }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <!-- Logout Container -->
    <div class="logout-container">
        <!-- Success Animation -->
        <div class="success-checkmark">
            <div class="check-icon">
                <span class="icon-line line-tip"></span>
                <span class="icon-line line-long"></span>
            </div>
        </div>

        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>

        <h1>Successfully Logged Out</h1>
        
        <p class="message">
            You have been securely logged out of your Zesigns Express account.<br>
            Thank you for using our services!
        </p>

        <!-- Countdown Timer -->
        <div class="countdown">
            <div class="countdown-text">Redirecting to login page in:</div>
            <div class="countdown-timer" id="countdown">10</div>
        </div>

        <!-- Action Buttons -->
        <div class="buttons-container">
            <a href="login.php" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login Again
            </a>
            <a href="../index.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>

        <!-- Session Debug Info (Optional - remove in production) -->
        <?php if (isset($_SESSION) && !empty($_SESSION)): ?>
            <div class="session-info">
                <i class="fas fa-info-circle"></i> Session data still present. Full logout may require clearing cookies.
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Countdown Timer
        let countdown = 10;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'login.php';
            }
        }, 1000);

        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Random size
                const size = Math.random() * 3 + 1;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
                // Random color
                const colors = [
                    'rgba(139, 92, 246, 0.6)',
                    'rgba(236, 72, 153, 0.6)',
                    'rgba(59, 130, 246, 0.6)',
                    'rgba(16, 185, 129, 0.6)'
                ];
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                
                // Random animation
                const duration = Math.random() * 20 + 10;
                particle.style.animationDuration = duration + 's';
                particle.style.animationDelay = Math.random() * 5 + 's';
                
                particlesContainer.appendChild(particle);
            }
        }

        // 3D hover effect
        const container = document.querySelector('.logout-container');
        container.addEventListener('mousemove', (e) => {
            const xAxis = (container.clientWidth / 2 - e.offsetX) / 20;
            const yAxis = (container.clientHeight / 2 - e.offsetY) / 20;
            container.style.transform = `perspective(1000px) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        container.addEventListener('mouseleave', () => {
            container.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
        });

        // Initialize particles
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
            
            // Button hover effects
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', (e) => {
                    const rect = e.target.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    e.target.style.setProperty('--mouse-x', `${x}px`);
                    e.target.style.setProperty('--mouse-y', `${y}px`);
                });
            });
        });

        // Auto-redirect after countdown
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 10000);

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Enter key - go to login
            if (e.key === 'Enter') {
                window.location.href = 'login.php';
            }
            // Escape key - go to home
            if (e.key === 'Escape') {
                window.location.href = '../index.php';
            }
        });
    </script>
</body>
</html>