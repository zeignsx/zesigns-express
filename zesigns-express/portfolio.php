<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "zesigns_express");
if ($conn->connect_error) {
    // If database doesn't exist, we'll use static content
    $static_mode = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Zesigns Express | Stunning Design Showcase</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--dark);
            color: var(--light);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Header - Glass Morphism */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.5rem 3rem;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2.2rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--light);
            text-decoration: none;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .nav-links a:hover::before {
            left: 0;
        }

        .nav-links a:hover {
            color: white;
            transform: translateY(-2px);
        }

        .nav-links a.active {
            background: var(--gradient);
            color: white;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        /* Hero Section - 3D Effect */
        .portfolio-hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 0 2rem;
            margin-top: 80px;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 60%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #fff, #8b5cf6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% {
                filter: hue-rotate(0deg);
                text-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            }
            100% {
                filter: hue-rotate(360deg);
                text-shadow: 0 10px 50px rgba(139, 92, 246, 0.5);
            }
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        /* Floating 3D Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            width: 40px;
            height: 40px;
            background: var(--gradient);
            border-radius: 10px;
            animation: float 6s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            width: 60px;
            height: 60px;
            background: var(--gradient-2);
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 60%;
            left: 15%;
            width: 30px;
            height: 30px;
            background: var(--gradient-3);
            animation-delay: 1s;
        }

        .floating-element:nth-child(3) {
            top: 30%;
            right: 10%;
            width: 50px;
            height: 50px;
            animation-delay: 2s;
        }

        .floating-element:nth-child(4) {
            top: 70%;
            right: 15%;
            width: 35px;
            height: 35px;
            background: var(--gradient-2);
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        /* Filter Section */
        .filter-section {
            padding: 4rem 2rem;
            position: relative;
            z-index: 2;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .filter-btn {
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 50px;
            color: var(--light);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .filter-btn:hover::before {
            left: 0;
        }

        .filter-btn:hover,
        .filter-btn.active {
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
            border-color: transparent;
        }

        .filter-btn.active::before {
            left: 0;
        }

        /* Portfolio Grid - Masonry with 3D */
        .portfolio-grid {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
            position: relative;
        }

        .masonry-grid {
            columns: 4 300px;
            column-gap: 2rem;
        }

        .portfolio-item {
            break-inside: avoid;
            margin-bottom: 2rem;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow);
            cursor: pointer;
        }

        .portfolio-item:hover {
            transform: translateY(-10px) rotateX(5deg) rotateY(5deg);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .portfolio-image {
            width: 100%;
            height: auto;
            display: block;
            transition: all 0.5s ease;
        }

        .portfolio-item:hover .portfolio-image {
            transform: scale(1.1);
        }

        .portfolio-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            padding: 2rem;
            transform: translateY(100%);
            transition: all 0.5s ease;
            backdrop-filter: blur(10px);
        }

        .portfolio-item:hover .portfolio-overlay {
            transform: translateY(0);
        }

        .portfolio-category {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: var(--gradient);
            color: white;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .portfolio-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .portfolio-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Interactive Stats */
        .stats-section {
            padding: 6rem 2rem;
            background: rgba(255, 255, 255, 0.02);
            margin: 4rem 0;
            border-radius: 40px;
            position: relative;
            overflow: hidden;
        }

        .stats-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0.05;
            z-index: 1;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* CTA Section */
        .cta-section {
            padding: 8rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0.1;
            z-index: 1;
        }

        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #fff, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }

        .cta-btn {
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .cta-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .cta-btn:hover::before {
            left: 0;
        }

        .cta-btn.primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .cta-btn.secondary {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .cta-btn.secondary:hover {
            border-color: transparent;
        }

        /* Image Modal */
        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }

        .modal-content img {
            width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        }

        .close-modal {
            position: absolute;
            top: -50px;
            right: 0;
            color: white;
            font-size: 2.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            color: var(--primary);
            transform: rotate(90deg);
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.02);
            padding: 4rem 2rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 10px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--gradient);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero-title {
                font-size: 3rem;
            }

            .masonry-grid {
                columns: 2 200px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .cta-title {
                font-size: 2.5rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-pen-nib"></i>
            Zesigns Express
        </a>
        
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="portfolio.php" class="active">Portfolio</a>
            <a href="contact.php">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" style="background: var(--gradient); color: white;">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            <?php else: ?>
                <a href="auth/login.php">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="portfolio-hero">
        <div class="hero-bg"></div>
        
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        
        <div class="hero-content">
            <h1 class="hero-title">DESIGN SHOWCASE</h1>
            <p class="hero-subtitle">
                Explore our stunning portfolio of creative designs. Each project tells a unique story 
                of innovation, creativity, and exceptional craftsmanship.
            </p>
            <div class="cta-buttons">
                <a href="#portfolio" class="cta-btn primary">
                    <i class="fas fa-eye"></i> View Portfolio
                </a>
                <a href="contact.php" class="cta-btn secondary">
                    <i class="fas fa-rocket"></i> Start Project
                </a>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="filter-container">
            <button class="filter-btn active" data-filter="all">All Works</button>
            <button class="filter-btn" data-filter="branding">Branding</button>
            <button class="filter-btn" data-filter="web">Web Design</button>
            <button class="filter-btn" data-filter="mobile">Mobile App</button>
            <button class="filter-btn" data-filter="print">Print Design</button>
            <button class="filter-btn" data-filter="illustration">Illustration</button>
            <button class="filter-btn" data-filter="motion">Motion Graphics</button>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section id="portfolio" class="portfolio-grid">
        <div class="masonry-grid" id="portfolioGrid">
            <!-- Portfolio items will be loaded here -->
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-bg"></div>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number" data-count="500">0</div>
                <div class="stat-label">Projects Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="150">0</div>
                <div class="stat-label">Happy Clients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="50">0</div>
                <div class="stat-label">Awards Won</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" data-count="98">0</div>
                <div class="stat-label">Client Satisfaction</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-bg"></div>
        <div class="cta-content">
            <h2 class="cta-title">Ready to Bring Your Vision to Life?</h2>
            <p style="font-size: 1.2rem; color: rgba(255, 255, 255, 0.8); max-width: 600px; margin: 0 auto 2rem;">
                Let's create something amazing together. Our team of expert designers is ready to transform your ideas into stunning visual experiences.
            </p>
            <div class="cta-buttons">
                <a href="contact.php" class="cta-btn primary">
                    <i class="fas fa-paper-plane"></i> Start Your Project
                </a>
                <a href="services.php" class="cta-btn secondary">
                    <i class="fas fa-list"></i> View Services
                </a>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Zesigns Express</h3>
                <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.6;">
                    Creating stunning visual experiences that inspire, engage, and transform businesses worldwide.
                </p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-dribbble"></i></a>
                    <a href="#"><i class="fab fa-behance"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="portfolio.php">Portfolio</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Services</h3>
                <ul class="footer-links">
                    <li><a href="#">Logo Design</a></li>
                    <li><a href="#">Brand Identity</a></li>
                    <li><a href="#">UI/UX Design</a></li>
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">Motion Graphics</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact Info</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt" style="margin-right: 10px;"></i> 123 Design Street, Creative City</li>
                    <li><i class="fas fa-phone" style="margin-right: 10px;"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-envelope" style="margin-right: 10px;"></i> hello@zesigns.com</li>
                </ul>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> Zesigns Express. All rights reserved. | Made with <i class="fas fa-heart" style="color: #f59e0b;"></i> for the creative community</p>
        </div>
    </footer>

    <script>
        // Portfolio Data with High-Quality Images from Unsplash
        const portfolioData = [
            {
                id: 1,
                category: 'branding',
                title: 'Nova Coffee Brand',
                description: 'Complete brand identity for a premium coffee shop chain',
                image: 'https://images.unsplash.com/photo-1514066558159-fc8c737ef259?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
                featured: true
            },
            {
                id: 2,
                category: 'web',
                title: 'FinTech Dashboard UI',
                description: 'Modern dashboard interface for financial technology platform',
                image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
                featured: true
            },
            {
                id: 3,
                category: 'mobile',
                title: 'Fitness App Design',
                description: 'Health and fitness tracking application UI/UX',
                image: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 4,
                category: 'branding',
                title: 'EcoPack Branding',
                description: 'Sustainable packaging design for eco-friendly products',
                image: 'https://images.unsplash.com/photo-1586773860418-dc22f8b874bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 5,
                category: 'web',
                title: 'Travel Agency Website',
                description: 'Responsive travel booking platform with interactive maps',
                image: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
                featured: true
            },
            {
                id: 6,
                category: 'illustration',
                title: 'Digital Art Series',
                description: 'Collection of digital illustrations for marketing campaign',
                image: 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 7,
                category: 'motion',
                title: 'Product Animation',
                description: '3D product animation for tech startup launch',
                image: 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 8,
                category: 'print',
                title: 'Fashion Magazine',
                description: 'Editorial design for luxury fashion publication',
                image: 'https://images.unsplash.com/photo-1544716278-e513176f20b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 9,
                category: 'branding',
                title: 'Tech Startup Logo',
                description: 'Minimalist logo design for innovative tech company',
                image: 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 10,
                category: 'web',
                title: 'E-commerce Platform',
                description: 'Complete online shopping experience redesign',
                image: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 11,
                category: 'mobile',
                title: 'Music Streaming App',
                description: 'UI design for next-gen music streaming service',
                image: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            },
            {
                id: 12,
                category: 'illustration',
                title: 'Children\'s Book Art',
                description: 'Colorful illustrations for educational children\'s book',
                image: 'https://images.unsplash.com/photo-1560972550-aba3456b5564?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
            }
        ];

        // Initialize portfolio
        document.addEventListener('DOMContentLoaded', function() {
            renderPortfolio();
            setupFilterButtons();
            setupStatsAnimation();
            setupModal();
            
            // Add scroll animation for portfolio items
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);

            // Observe portfolio items after they're loaded
            setTimeout(() => {
                document.querySelectorAll('.portfolio-item').forEach(item => {
                    observer.observe(item);
                });
            }, 500);
        });

        // Render portfolio items
        function renderPortfolio(filter = 'all') {
            const grid = document.getElementById('portfolioGrid');
            grid.innerHTML = '';
            
            const filteredItems = filter === 'all' 
                ? portfolioData 
                : portfolioData.filter(item => item.category === filter);
            
            filteredItems.forEach(item => {
                const portfolioItem = document.createElement('div');
                portfolioItem.className = 'portfolio-item';
                portfolioItem.dataset.category = item.category;
                
                if (item.featured) {
                    portfolioItem.style.transform = 'scale(1.05)';
                }
                
                portfolioItem.innerHTML = `
                    <img src="${item.image}" alt="${item.title}" class="portfolio-image" loading="lazy">
                    <div class="portfolio-overlay">
                        <span class="portfolio-category">${item.category.toUpperCase()}</span>
                        <h3 class="portfolio-title">${item.title}</h3>
                        <p class="portfolio-description">${item.description}</p>
                    </div>
                `;
                
                // Add click event for modal
                portfolioItem.addEventListener('click', () => openImageModal(item.image, item.title));
                
                grid.appendChild(portfolioItem);
            });
        }

        // Setup filter buttons
        function setupFilterButtons() {
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Apply filter
                    const filter = this.dataset.filter;
                    renderPortfolio(filter);
                });
            });
        }

        // Animate stats numbers
        function setupStatsAnimation() {
            const statNumbers = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const target = parseInt(element.dataset.count);
                        const duration = 2000; // 2 seconds
                        const step = target / (duration / 16); // 60fps
                        let current = 0;
                        
                        const timer = setInterval(() => {
                            current += step;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            element.textContent = Math.floor(current) + (element.dataset.count === '98' ? '%' : '+');
                        }, 16);
                        
                        observer.unobserve(element);
                    }
                });
            }, { threshold: 0.5 });
            
            statNumbers.forEach(number => observer.observe(number));
        }

        // Setup image modal
        function setupModal() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const closeBtn = document.querySelector('.close-modal');
            
            function openModal(imageSrc, title) {
                modalImage.src = imageSrc;
                modalImage.alt = title;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            window.openImageModal = openModal;
            
            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
            
            // Close with ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeModal();
            });
        }

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.portfolio-hero');
            const rate = scrolled * -0.5;
            hero.style.transform = `translate3d(0, ${rate}px, 0)`;
            
            // Floating elements animation
            const elements = document.querySelectorAll('.floating-element');
            elements.forEach((element, index) => {
                const speed = (index + 1) * 0.5;
               
             element.style.transform = `translateY(${Math.sin(scrolled * 0.001 * speed) * 20}px) rotate(${scrolled * 0.01}deg)`;
            });

        });
    </script>
</body>
</html>