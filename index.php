<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zesigns Express | Hire Elite Graphic Designers</title>
    <meta name="description" content="Hire top graphic designers instantly. 500+ creative professionals ready to bring your vision to life with stunning designs.">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Dark Theme Variables */
        :root {
            --bg-dark: #0f172a;
            --bg-darker: #0a0f1d;
            --bg-card: rgba(30, 41, 59, 0.7);
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --text-light: #f8fafc;
            --text-gray: #94a3b8;
            --border-dark: rgba(255, 255, 255, 0.1);
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        body {
            background: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Header - Glass Morphism */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.5rem 3rem;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-dark);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            color: var(--secondary);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-light);
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
            box-shadow: var(--shadow);
        }

        /* Hero Section with Video */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 0 3rem;
            margin-top: 0;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            object-fit: cover;
            filter: brightness(0.4);
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.95) 0%,
                rgba(15, 23, 42, 0.8) 50%,
                rgba(15, 23, 42, 0.95) 100%);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            background: linear-gradient(45deg, var(--text-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: var(--text-gray);
            margin-bottom: 3rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-btn {
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

        .hero-btn::before {
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

        .hero-btn:hover::before {
            left: 0;
        }

        .hero-btn.primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .hero-btn.secondary {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .hero-btn.secondary:hover {
            border-color: transparent;
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .floating-element {
            position: absolute;
            width: 40px;
            height: 40px;
            background: var(--gradient-2);
            border-radius: 10px;
            animation: float 6s ease-in-out infinite;
            box-shadow: var(--shadow);
        }

        /* Stats Section */
        .stats-section {
            padding: 6rem 3rem;
            background: rgba(255, 255, 255, 0.02);
            position: relative;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            text-align: center;
            padding: 2.5rem 2rem;
            background: var(--bg-card);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-dark);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .stat-card:hover::before {
            opacity: 0.1;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .stat-card h3 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card p {
            color: var(--text-gray);
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Services Preview */
        .services-preview {
            padding: 6rem 3rem;
            background: var(--bg-darker);
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 800;
            background: linear-gradient(45deg, var(--text-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-gray);
            margin-bottom: 4rem;
            font-size: 1.2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-preview-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid var(--border-dark);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-preview-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .service-preview-card:hover::before {
            opacity: 0.1;
        }

        .service-preview-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .service-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .service-preview-card h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .service-preview-card p {
            color: var(--text-gray);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .service-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 1.5rem;
        }

        .service-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .service-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Designer Showcase */
        .designer-showcase {
            padding: 6rem 3rem;
            background: rgba(255, 255, 255, 0.02);
        }

        .designer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .designer-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--border-dark);
            transition: all 0.3s ease;
            text-align: center;
        }

        .designer-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .designer-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            border: 3px solid var(--primary);
            overflow: hidden;
        }

        .designer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .designer-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-light);
        }

        .designer-specialty {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .designer-rating {
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .hire-designer-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .hire-designer-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* CTA Section */
        .cta-section {
            padding: 8rem 3rem;
            text-align: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: white;
        }

        .cta-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 1s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 0 1.5rem;
                text-align: center;
            }

            .hero-title {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero-btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            .section-title {
                font-size: 2.5rem;
            }

            .stats-grid,
            .services-grid,
            .designer-grid {
                grid-template-columns: 1fr;
            }

            .cta-title {
                font-size: 2.5rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
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
            <a href="index.php" class="active">Home</a>
            <a href="services.php">Services</a>
            <a href="portfolio.php">Portfolio</a>
            <a href="designers.php">Designers</a>
            <a href="contact.php">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">
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
    <section class="hero">
        <div class="video-container">
            <video autoplay muted loop playsinline class="hero-video" id="heroVideo" 
                   poster="https://images.unsplash.com/photo-1558655146-9f40138edfeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80">
                <source src="https://assets.mixkit.co/videos/preview/mixkit-waves-crashing-on-the-beach-5016-large.mp4" type="video/mp4">
                <source src="https://cdn.pixabay.com/video/2023/04/19/162851-819774271_large.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-overlay"></div>
        </div>
        
        <div class="floating-elements">
            <div class="floating-element" style="top: 20%; left: 10%;"></div>
            <div class="floating-element" style="top: 60%; left: 15%;"></div>
            <div class="floating-element" style="top: 30%; right: 10%;"></div>
            <div class="floating-element" style="top: 70%; right: 15%;"></div>
        </div>
        
        <div class="hero-content animate-fade-up">
            <h1 class="hero-title">Where Vision Meets Design</h1>
            <p class="hero-subtitle">
                Hire from 500+ elite graphic designers worldwide. Get stunning designs delivered 
                fast by vetted creative professionals. Your vision, our expertise.
            </p>
            <div class="hero-buttons">
                <a href="services.php" class="hero-btn primary">
                    <i class="fas fa-search"></i> Hire Designers
                </a>
                <a href="auth/register.php?role=designer" class="hero-btn secondary">
                    <i class="fas fa-paint-brush"></i> Become a Designer
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>500+</h3>
                <p>Elite Designers</p>
            </div>
            <div class="stat-card">
                <h3>2.5K+</h3>
                <p>Projects Delivered</p>
            </div>
            <div class="stat-card">
                <h3>98%</h3>
                <p>Client Satisfaction</p>
            </div>
            <div class="stat-card">
                <h3>24/7</h3>
                <p>Support Available</p>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="services-preview">
        <h2 class="section-title">Premium Design Services</h2>
        <p class="section-subtitle">Handpicked services from our top-rated designers</p>
        
        <div class="services-grid">
            <div class="service-preview-card">
                <div class="service-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Logo Design</h3>
                <p>Professional logo creation with multiple concepts, unlimited revisions, and full copyright ownership.</p>
                <div class="service-price">Starting at $149</div>
                <a href="services.php?category=logo" class="service-btn">
                    <i class="fas fa-eye"></i> View Designs
                </a>
            </div>
            
            <div class="service-preview-card">
                <div class="service-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3>Brand Identity</h3>
                <p>Complete brand package with guidelines, colors, typography, and visual systems.</p>
                <div class="service-price">Starting at $299</div>
                <a href="services.php?category=branding" class="service-btn">
                    <i class="fas fa-eye"></i> View Portfolio
                </a>
            </div>
            
            <div class="service-preview-card">
                <div class="service-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>UI/UX Design</h3>
                <p>Modern web and app interfaces with user research, wireframing, and interactive prototypes.</p>
                <div class="service-price">Starting at $499</div>
                <a href="services.php?category=uiux" class="service-btn">
                    <i class="fas fa-eye"></i> Explore Services
                </a>
            </div>
        </div>
    </section>

    <!-- Designer Showcase -->
    <section class="designer-showcase">
        <h2 class="section-title">Featured Designers</h2>
        <p class="section-subtitle">Meet our top-rated creative professionals</p>
        
        <div class="designer-grid">
            <div class="designer-card">
                <div class="designer-avatar">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Designer">
                </div>
                <h3>Alex Morgan</h3>
                <div class="designer-specialty">Brand Identity Specialist</div>
                <div class="designer-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    4.9/5
                </div>
                <p>200+ projects • 5 years experience</p>
                <a href="designer-profile.php?id=1" class="hire-designer-btn">
                    <i class="fas fa-briefcase"></i> Hire Alex
                </a>
            </div>
            
            <div class="designer-card">
                <div class="designer-avatar">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Designer">
                </div>
                <h3>Sarah Chen</h3>
                <div class="designer-specialty">UI/UX Expert</div>
                <div class="designer-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    4.8/5
                </div>
                <p>150+ projects • 4 years experience</p>
                <a href="designer-profile.php?id=2" class="hire-designer-btn">
                    <i class="fas fa-briefcase"></i> Hire Sarah
                </a>
            </div>
            
            <div class="designer-card">
                <div class="designer-avatar">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Designer">
                </div>
                <h3>Marcus Lee</h3>
                <div class="designer-specialty">Motion Graphics</div>
                <div class="designer-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    4.7/5
                </div>
                <p>120+ projects • 3 years experience</p>
                <a href="designer-profile.php?id=3" class="hire-designer-btn">
                    <i class="fas fa-briefcase"></i> Hire Marcus
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2 class="cta-title">Ready to Bring Your Vision to Life?</h2>
        <p class="cta-subtitle">Join thousands of satisfied clients who trust Zesigns Express for their design needs.</p>
        <div class="cta-buttons">
            <a href="auth/register.php?role=client" class="hero-btn primary" style="background: white; color: var(--primary);">
                <i class="fas fa-rocket"></i> Start Your Project
            </a>
            <a href="contact.php" class="hero-btn secondary" style="border-color: white; color: white;">
                <i class="fas fa-comments"></i> Get a Free Quote
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Zesigns Express</h3>
                    <p>Connecting businesses with elite graphic designers worldwide. Professional designs delivered on time, every time.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-dribbble"></i></a>
                        <a href="#"><i class="fab fa-behance"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="services.php?category=logo">Logo Design</a></li>
                        <li><a href="services.php?category=branding">Brand Identity</a></li>
                        <li><a href="services.php?category=uiux">UI/UX Design</a></li>
                        <li><a href="services.php?category=print">Print Design</a></li>
                        <li><a href="services.php?category=social">Social Media Graphics</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="how-it-works.php">How It Works</a></li>
                        <li><a href="pricing.php">Pricing</a></li>
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="careers.php">Careers</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="help.php">Help Center</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="refund.php">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Zesigns Express. All rights reserved.</p>
                <p>Made with <i class="fas fa-heart" style="color: #f59e0b;"></i> for the creative community</p>
            </div>
        </div>
    </footer>

    <script>
        // Video controls
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('heroVideo');
            if (video) {
                video.play().catch(e => {
                    console.log('Video autoplay prevented:', e);
                    // Fallback: Show play button
                });
            }

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-up');
                    }
                });
            }, observerOptions);

            // Observe all cards and sections
            document.querySelectorAll('.stat-card, .service-preview-card, .designer-card').forEach(item => {
                observer.observe(item);
            });

            // Parallax effect
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero');
                if (hero) {
                    hero.style.transform = `translate3d(0, ${scrolled * 0.5}px, 0)`;
                }
            });
        });
    </script>
</body>
</html>