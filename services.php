<?php 
session_start();

// Initialize user session if not set (for demo)
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'name' => 'Guest User',
        'email' => 'guest@example.com'
    ];
}

// Initialize orders if not set
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Zesigns Express | Professional Design Services</title>
    <meta name="description" content="Browse our premium design services including logo design, brand identity, UI/UX design, print design, and more. Hire expert designers today.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        
        html {
            background: var(--dark-bg);
            scroll-behavior: smooth;
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* HEADER STYLES - FIXED */
        .main-header {
            background: rgba(15, 15, 30, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-dark);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 1rem 0;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-light);
        }
        
        .logo i {
            color: var(--primary);
            font-size: 2rem;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2.5rem;
            margin: 0;
            padding: 0;
        }
        
        .nav-menu a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }
        
        .nav-menu a:hover,
        .nav-menu a.active {
            color: var(--text-light);
        }
        
        .nav-menu a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary);
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .nav-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
        }
        
        .nav-user i {
            color: var(--primary);
        }
        
        .btn-login,
        .btn-signup {
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-login {
            color: var(--text-light);
            border: 1px solid var(--border-dark);
        }
        
        .btn-login:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
        }
        
        .btn-signup {
            background: var(--primary);
            color: white;
        }
        
        .btn-signup:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-logout {
            color: var(--danger);
            text-decoration: none;
            font-size: 1.2rem;
            padding: 0.5rem;
        }
        
        /* HERO SECTION */
        .services-hero {
            background: linear-gradient(135deg, #0a0a15 0%, #151533 100%);
            color: white;
            padding: 140px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-top: 0;
        }
        
        .services-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(109,40,217,0.1)" d="M0,0 L100,0 L100,100 Z"/></svg>');
            background-size: cover;
        }
        
        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background: var(--primary-light);
            border-radius: 50%;
            opacity: 0.3;
            animation: floatParticle 20s infinite linear;
        }
        
        @keyframes floatParticle {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-1000px) rotate(720deg); }
        }
        
        .services-hero h1 {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 5px 15px var(--shadow-light);
        }
        
        .services-hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 3rem;
            color: var(--text-muted);
        }
        
        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.4);
        }
        
        .btn.primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(109, 40, 217, 0.6);
        }
        
        .btn.secondary {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--primary-light);
            backdrop-filter: blur(10px);
        }
        
        .btn.secondary:hover {
            background: rgba(109, 40, 217, 0.1);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.3);
        }
        
        /* SERVICES FILTER */
        .services-filter {
            background: var(--dark-card);
            padding: 2rem 0;
            box-shadow: 0 5px 30px var(--shadow-dark);
            position: sticky;
            top: 80px;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }
        
        .filter-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .filter-btn {
            padding: 0.8rem 1.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 50px;
            color: var(--text-muted);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(109, 40, 217, 0.3);
            border-color: transparent;
        }
        
        /* SERVICES GRID */
        .services-grid {
            padding: 5rem 0;
            position: relative;
        }
        
        .service-category {
            margin-bottom: 6rem;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .service-category.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .category-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .category-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            transform-style: preserve-3d;
            animation: rotate3d 20s infinite linear;
        }
        
        @keyframes rotate3d {
            0% { transform: rotateY(0deg) rotateX(0deg); }
            100% { transform: rotateY(360deg) rotateX(360deg); }
        }
        
        .category-icon i {
            font-size: 3rem;
            color: white;
            transform: translateZ(20px);
        }
        
        .category-header h2 {
            font-size: 2.8rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        
        .category-header p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.2rem;
        }
        
        .services-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
            margin-bottom: 3rem;
        }
        
        .service-item {
            background: linear-gradient(145deg, var(--dark-card), var(--dark-bg));
            border-radius: 25px;
            padding: 2.5rem;
            border: 1px solid var(--border-dark);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .service-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(109, 40, 217, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        
        .service-item:hover::before {
            transform: translateX(100%);
        }
        
        .service-item:hover {
            transform: translateY(-15px) scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 30px 80px rgba(109, 40, 217, 0.3);
        }
        
        .service-badge {
            position: absolute;
            top: 25px;
            right: 25px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
        }
        
        .service-badge.popular {
            background: linear-gradient(135deg, var(--accent), var(--warning));
        }
        
        .service-item h3 {
            font-size: 1.8rem;
            color: var(--text-light);
            margin-bottom: 1.2rem;
            padding-right: 80px;
            background: linear-gradient(90deg, var(--text-light), var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .service-description {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.7;
            font-size: 1.05rem;
        }
        
        .service-features {
            list-style: none;
            margin-bottom: 2rem;
        }
        
        .service-features li {
            padding: 0.7rem 0;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.3s ease;
        }
        
        .service-features li:hover {
            transform: translateX(10px);
            color: var(--primary-light);
        }
        
        .service-features li i {
            color: var(--success);
            font-size: 1rem;
            background: rgba(16, 185, 129, 0.1);
            padding: 5px;
            border-radius: 50%;
        }
        
        .service-pricing {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            border: 1px solid var(--border-dark);
        }
        
        .price-tag {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        
        .price-note {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .service-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-btn {
            flex: 1;
            padding: 1rem 1.5rem;
            text-align: center;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .action-btn.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
        }
        
        .action-btn.primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(109, 40, 217, 0.4);
        }
        
        .action-btn.secondary {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--primary-light);
        }
        
        .action-btn.secondary:hover {
            background: rgba(109, 40, 217, 0.1);
            transform: translateY(-3px);
            border-color: var(--primary);
        }
        
        .service-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-dark);
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            color: var(--primary-light);
            transform: translateY(-2px);
        }
        
        /* PROCESS SECTION */
        .process-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--dark-card), var(--dark-bg));
            position: relative;
            overflow: hidden;
        }
        
        .process-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 30%, rgba(109, 40, 217, 0.1) 0%, transparent 50%);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-header h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .section-header p {
            color: var(--text-muted);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .process-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2.5rem;
            margin-top: 4rem;
        }
        
        .process-step {
            text-align: center;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            transition: all 0.5s ease;
            border: 1px solid var(--border-dark);
            position: relative;
            overflow: hidden;
        }
        
        .process-step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(109, 40, 217, 0.05), transparent);
            transform: translateX(-100%);
        }
        
        .process-step:hover::before {
            animation: shine 1.5s;
        }
        
        @keyframes shine {
            100% { transform: translateX(100%); }
        }
        
        .process-step:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 20px 60px rgba(109, 40, 217, 0.2);
        }
        
        .step-number {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.3);
        }
        
        .process-step h3 {
            font-size: 1.5rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        
        .process-step p {
            color: var(--text-muted);
            line-height: 1.6;
        }
        
        /* FAQ SECTION */
        .faq-section {
            padding: 6rem 0;
            background: var(--dark-bg);
            position: relative;
        }
        
        .faq-grid {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .faq-item {
            background: var(--dark-card);
            border-radius: 15px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            border: 1px solid var(--border-dark);
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 40px rgba(109, 40, 217, 0.1);
        }
        
        .faq-question {
            padding: 1.8rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--text-light);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            background: rgba(109, 40, 217, 0.05);
        }
        
        .faq-answer {
            padding: 0 1.8rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: var(--text-muted);
            line-height: 1.7;
        }
        
        .faq-item.active .faq-answer {
            padding: 0 1.8rem 1.8rem;
            max-height: 1000px;
        }
        
        .faq-toggle {
            color: var(--primary);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: rgba(109, 40, 217, 0.1);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .faq-item.active .faq-toggle {
            transform: rotate(180deg);
            background: var(--primary);
            color: white;
        }
        
        /* CTA SECTION */
        .cta-section {
            padding: 8rem 0;
            background: linear-gradient(135deg, var(--primary-dark), #0597aa);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 Z"/></svg>');
            background-size: cover;
        }
        
        .cta-section h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }
        
        .cta-section p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 3rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .cta-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }
        
        .cta-buttons .btn {
            min-width: 200px;
        }
        
        /* FOOTER */
        footer {
            background: var(--dark-card);
            padding: 4rem 0 2rem;
            border-top: 1px solid var(--border-dark);
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        
        .footer-logo i {
            color: var(--primary);
            font-size: 2rem;
        }
        
        .footer-logo span {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-light);
        }
        
        .footer-links h3,
        .footer-contact h3 {
            color: var(--text-light);
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary);
        }
        
        .contact-info {
            color: var(--text-muted);
        }
        
        .contact-info p {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .contact-info i {
            color: var(--primary);
            width: 20px;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--border-dark);
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }
        
        /* RESPONSIVE DESIGN */
        @media (max-width: 1200px) {
            .services-hero h1 {
                font-size: 3.5rem;
            }
            
            .category-header h2 {
                font-size: 2.5rem;
            }
            
            .services-row {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            }
        }
        
        @media (max-width: 992px) {
            .services-hero {
                padding: 120px 0 80px;
            }
            
            .services-hero h1 {
                font-size: 3rem;
            }
            
            .section-header h2 {
                font-size: 2.5rem;
            }
            
            .process-steps {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .nav-menu {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .services-hero h1 {
                font-size: 2.5rem;
            }
            
            .services-hero p {
                font-size: 1.1rem;
            }
            
            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
            }
            
            .services-row {
                grid-template-columns: 1fr;
            }
            
            .service-actions {
                flex-direction: column;
            }
            
            .process-steps {
                grid-template-columns: 1fr;
            }
            
            .section-header h2 {
                font-size: 2rem;
            }
            
            .cta-section h2 {
                font-size: 2.5rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-buttons .btn {
                width: 100%;
                max-width: 300px;
            }
            
            .filter-container {
                gap: 0.5rem;
            }
            
            .filter-btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
            
            .nav-actions {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .services-hero h1 {
                font-size: 2rem;
            }
            
            .category-header h2 {
                font-size: 1.8rem;
            }
            
            .service-item {
                padding: 2rem;
            }
            
            .service-item h3 {
                font-size: 1.5rem;
            }
            
            .price-tag {
                font-size: 2rem;
            }
            
            .step-number {
                width: 60px;
                height: 60px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="main-header">
        <nav class="container">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    <i class="fas fa-palette"></i>
                    <span>Zesigns Express</span>
                </a>
                
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php" class="active">Services</a></li>
                    <li><a href="portfolio.php">Portfolio</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="my_orders.php">My Orders (<?php echo count($_SESSION['orders']); ?>)</a></li>
                </ul>
                
                <div class="nav-actions">
                    <?php if (isset($_SESSION['user'])): ?>
                    <a href="dashboard.php" class="nav-user">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                    </a>
                    <a href="logout.php" class="btn-logout" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <?php else: ?>
                    <a href="login.php" class="btn-login">Login</a>
                    <a href="register.php" class="btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Services Hero Section -->
    <section class="services-hero">
        <div class="hero-particles" id="particles"></div>
        <div class="container">
            <h1 class="animate__animated animate__fadeInDown">Premium Design Services</h1>
            <p class="animate__animated animate__fadeInUp animate__delay-1s">Choose from our handpicked selection of professional design services. Every project is crafted by expert designers to meet your specific needs.</p>
            <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                <a href="#services" class="btn primary floating">
                    <i class="fas fa-sparkles"></i> Browse Services
                </a>
                <a href="contact.php" class="btn secondary pulse-glow">
                    <i class="fas fa-headset"></i> Get Custom Quote
                </a>
            </div>
        </div>
    </section>

    <!-- Services Filter -->
    <section class="services-filter" id="services">
        <div class="container">
            <div class="filter-container">
                <button class="filter-btn active" data-filter="all">All Services</button>
                <button class="filter-btn" data-filter="branding">Branding</button>
                <button class="filter-btn" data-filter="digital">Digital Design</button>
                <button class="filter-btn" data-filter="print">Print Design</button>
                <button class="filter-btn" data-filter="motion">Motion Graphics</button>
                <button class="filter-btn" data-filter="packaging">Packaging</button>
            </div>
        </div>
    </section>

    <!-- Main Services Grid -->
    <section class="services-grid">
        <div class="container">
            <?php
            // Services data array with Naira prices
            $services = [
                'branding' => [
                    'icon' => 'fas fa-palette',
                    'title' => 'Brand Identity & Logo Design',
                    'description' => 'Create a memorable brand identity that stands out and tells your story',
                    'items' => [
                        [
                            'badge' => 'Most Popular',
                            'badge_class' => 'popular',
                            'title' => 'Logo Design Package',
                            'description' => 'Complete logo design with multiple concepts, revisions, and final files in all formats.',
                            'features' => ['3 Initial Concepts', 'Unlimited Revisions', 'Vector Source Files', 'Color Variations', 'Style Guide'],
                            'price' => '₦75,000 - ₦250,000',
                            'timeline' => '3-7 Days',
                            'rating' => '4.9/5',
                            'orders' => '500+ Orders',
                            'order_link' => 'order.php?service=logo-basic&type=logo',
                            'portfolio_link' => 'portfolio.php?category=logo'
                        ],
                        [
                            'badge' => '',
                            'badge_class' => '',
                            'title' => 'Complete Brand Identity',
                            'description' => 'Full brand identity package including logo, colors, typography, and brand guidelines.',
                            'features' => ['Logo Design', 'Color Palette', 'Typography System', 'Brand Guidelines', 'Business Card Design'],
                            'price' => '₦150,000 - ₦450,000',
                            'timeline' => '5-10 Days',
                            'rating' => '4.8/5',
                            'orders' => '300+ Orders',
                            'order_link' => 'order.php?service=brand-identity&type=branding',
                            'portfolio_link' => 'portfolio.php?category=branding'
                        ],
                        [
                            'badge' => 'Custom',
                            'badge_class' => '',
                            'title' => 'Logo Redesign',
                            'description' => 'Refresh your existing logo with modern design while maintaining brand recognition.',
                            'features' => ['Current Logo Audit', '2 Redesign Concepts', 'Modern Adaptation', 'Updated Guidelines', 'All File Formats'],
                            'price' => '₦100,000 - ₦300,000',
                            'timeline' => '4-8 Days',
                            'rating' => '4.7/5',
                            'orders' => '200+ Orders',
                            'order_link' => 'order.php?service=logo-redesign&type=logo',
                            'portfolio_link' => 'contact.php'
                        ]
                    ]
                ],
                'digital' => [
                    'icon' => 'fas fa-laptop-code',
                    'title' => 'UI/UX & Web Design',
                    'description' => 'Beautiful, functional digital experiences that engage and convert users',
                    'items' => [
                        [
                            'badge' => 'Trending',
                            'badge_class' => 'popular',
                            'title' => 'Website UI/UX Design',
                            'description' => 'Complete website design with user experience research and interactive prototypes.',
                            'features' => ['User Research', 'Wireframing', 'UI Design (5-10 pages)', 'Interactive Prototype', 'Responsive Design'],
                            'price' => '₦250,000 - ₦1,250,000',
                            'timeline' => '7-14 Days',
                            'rating' => '4.9/5',
                            'orders' => '400+ Orders',
                            'order_link' => 'order.php?service=website-ui&type=web',
                            'portfolio_link' => 'portfolio.php?category=web-design'
                        ],
                        [
                            'badge' => '',
                            'badge_class' => '',
                            'title' => 'Mobile App UI Design',
                            'description' => 'Native mobile app design for iOS and Android with user-centered approach.',
                            'features' => ['iOS & Android Design', 'User Flow Mapping', 'Interactive Prototypes', 'Design System', 'Developer Handoff'],
                            'price' => '₦400,000 - ₦1,750,000',
                            'timeline' => '10-20 Days',
                            'rating' => '4.8/5',
                            'orders' => '250+ Orders',
                            'order_link' => 'order.php?service=mobile-app&type=app',
                            'portfolio_link' => 'portfolio.php?category=mobile'
                        ],
                        [
                            'badge' => '',
                            'badge_class' => '',
                            'title' => 'Dashboard & Admin Panel',
                            'description' => 'Complex dashboard interfaces with data visualization and user management.',
                            'features' => ['Dashboard Layout', 'Data Visualization', 'User Management', 'Analytics Design', 'Dark/Light Mode'],
                            'price' => '₦500,000 - ₦2,500,000',
                            'timeline' => '14-25 Days',
                            'rating' => '4.9/5',
                            'orders' => '150+ Orders',
                            'order_link' => 'order.php?service=dashboard&type=web',
                            'portfolio_link' => 'contact.php'
                        ]
                    ]
                ],
                'print' => [
                    'icon' => 'fas fa-print',
                    'title' => 'Print & Marketing Materials',
                    'description' => 'Professional print designs that make a lasting impression offline',
                    'items' => [
                        [
                            'badge' => '',
                            'badge_class' => '',
                            'title' => 'Business Stationery',
                            'description' => 'Complete business stationery set including letterhead, business cards, and envelopes.',
                            'features' => ['Business Card Design', 'Letterhead Design', 'Envelope Design', 'Print-Ready Files', 'Bleed & Safe Area'],
                            'price' => '₦50,000 - ₦150,000',
                            'timeline' => '3-5 Days',
                            'rating' => '4.7/5',
                            'orders' => '600+ Orders',
                            'order_link' => 'order.php?service=stationery&type=print',
                            'portfolio_link' => 'portfolio.php?category=print'
                        ],
                        [
                            'badge' => 'Popular',
                            'badge_class' => '',
                            'title' => 'Brochure & Catalog Design',
                            'description' => 'Multi-page brochure and catalog design with professional layout and typography.',
                            'features' => ['4-12 Page Layout', 'Professional Typography', 'Image Placement', 'Print Specifications', 'Digital PDF Version'],
                            'price' => '₦100,000 - ₦400,000',
                            'timeline' => '5-10 Days',
                            'rating' => '4.8/5',
                            'orders' => '350+ Orders',
                            'order_link' => 'order.php?service=brochure&type=print',
                            'portfolio_link' => 'portfolio.php?category=brochure'
                        ],
                        [
                            'badge' => '',
                            'badge_class' => '',
                            'title' => 'Flyer & Poster Design',
                            'description' => 'Eye-catching flyers and posters for events, promotions, and announcements.',
                            'features' => ['Single/Double Sided', 'Multiple Size Options', 'High-Resolution Output', 'Print & Digital Versions', '3 Design Concepts'],
                            'price' => '₦40,000 - ₦100,000',
                            'timeline' => '2-4 Days',
                            'rating' => '4.6/5',
                            'orders' => '800+ Orders',
                            'order_link' => 'order.php?service=flyer&type=print',
                            'portfolio_link' => 'portfolio.php?category=flyer'
                        ]
                    ]
                ]
            ];
            
            // Display services from PHP array
            foreach ($services as $category => $categoryData): ?>
            <div class="service-category" data-category="<?php echo $category; ?>">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="<?php echo $categoryData['icon']; ?>"></i>
                    </div>
                    <h2><?php echo $categoryData['title']; ?></h2>
                    <p><?php echo $categoryData['description']; ?></p>
                </div>
                
                <div class="services-row">
                    <?php foreach ($categoryData['items'] as $item): ?>
                    <div class="service-item rotate-3d">
                        <?php if ($item['badge']): ?>
                        <span class="service-badge <?php echo $item['badge_class']; ?>"><?php echo $item['badge']; ?></span>
                        <?php endif; ?>
                        <h3><?php echo $item['title']; ?></h3>
                        <p class="service-description"><?php echo $item['description']; ?></p>
                        
                        <ul class="service-features">
                            <?php foreach ($item['features'] as $feature): ?>
                            <li><i class="fas fa-check"></i> <?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="service-pricing">
                            <div class="price-tag"><?php echo $item['price']; ?></div>
                            <div class="price-note">Starts from <?php echo $item['timeline']; ?></div>
                        </div>
                        
                        <div class="service-actions">
                            <a href="<?php echo $item['order_link']; ?>" class="action-btn primary">
                                <i class="fas fa-shopping-cart"></i> Order Now
                            </a>
                            <a href="<?php echo $item['portfolio_link']; ?>" class="action-btn secondary">
                                <i class="fas fa-eye"></i> View Portfolio
                            </a>
                        </div>
                        
                        <div class="service-stats">
                            <div class="stat-item">
                                <i class="fas fa-clock"></i> <?php echo $item['timeline']; ?>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-star"></i> <?php echo $item['rating']; ?>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-users"></i> <?php echo $item['orders']; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <div class="section-header">
                <h2>How Our Service Process Works</h2>
                <p>Simple, transparent process from start to finish</p>
            </div>
            
            <div class="process-steps">
                <div class="process-step floating">
                    <div class="step-number">1</div>
                    <h3>Choose Service</h3>
                    <p>Select a service package that fits your needs and budget</p>
                </div>
                
                <div class="process-step floating" style="animation-delay: 0.2s;">
                    <div class="step-number">2</div>
                    <h3>Place Order</h3>
                    <p>Fill out our brief form with your requirements and preferences</p>
                </div>
                
                <div class="process-step floating" style="animation-delay: 0.4s;">
                    <div class="step-number">3</div>
                    <h3>Design Creation</h3>
                    <p>Our expert designers work on your project with regular updates</p>
                </div>
                
                <div class="process-step floating" style="animation-delay: 0.6s;">
                    <div class="step-number">4</div>
                    <h3>Review & Revise</h3>
                    <p>Review the designs and request revisions until perfect</p>
                </div>
                
                <div class="process-step floating" style="animation-delay: 0.8s;">
                    <div class="step-number">5</div>
                    <h3>Final Delivery</h3>
                    <p>Receive all final files with full ownership rights</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Get answers to common questions about our services</p>
            </div>
            
            <div class="faq-grid">
                <?php
                $faqs = [
                    [
                        'question' => 'How long does a typical design project take?',
                        'answer' => 'Project timelines vary based on complexity. Logo designs typically take 3-7 business days, while complete brand identities take 5-10 days. Web design projects can take 7-20 days depending on the number of pages and complexity. We always provide an estimated timeline before starting.'
                    ],
                    [
                        'question' => 'What file formats will I receive?',
                        'answer' => 'You\'ll receive all standard file formats: For logos - AI, EPS, PDF, PNG, JPEG, SVG. For print designs - PDF with bleed, high-res JPEG, and print-ready files. For web designs - PSD, Figma, Sketch, and optimized web images. We also provide source files for future edits.'
                    ],
                    [
                        'question' => 'How many revisions are included?',
                        'answer' => 'Most packages include 2-3 rounds of revisions. Unlimited revisions are available in premium packages. We work with you until you\'re completely satisfied with the design. Additional revisions beyond the included rounds are available at a small fee.'
                    ],
                    [
                        'question' => 'Do you offer rush delivery?',
                        'answer' => 'Yes! We offer expedited delivery options for urgent projects. Rush delivery typically adds a 30-50% premium depending on the timeline. Contact us directly for urgent projects, and we\'ll do our best to accommodate your timeline.'
                    ],
                    [
                        'question' => 'What\'s your refund policy?',
                        'answer' => 'We offer a 100% satisfaction guarantee. If you\'re not happy with our work after the included revisions, we offer a partial or full refund depending on the project stage. Refund requests must be made within 14 days of project completion.'
                    ],
                    [
                        'question' => 'Can I hire the same designer for multiple projects?',
                        'answer' => 'Absolutely! We encourage building long-term relationships with our designers. Once you find a designer whose style matches your vision, you can request them for future projects and maintain consistency across all your branding.'
                    ]
                ];
                
                foreach ($faqs as $index => $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <?php echo $faq['question']; ?>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <?php echo $faq['answer']; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Transform Your Vision into Reality?</h2>
            <p>Join thousands of satisfied clients who have elevated their brand with our premium design services</p>
            <div class="cta-buttons">
                <a href="#services" class="btn primary pulse-glow">
                    <i class="fas fa-rocket"></i> Explore All Services
                </a>
                <a href="contact.php" class="btn secondary">
                    <i class="fas fa-comments"></i> Schedule a Consultation
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <i class="fas fa-palette"></i>
                        <span>Zesigns Express</span>
                    </div>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Professional design services that transform your vision into stunning visual experiences.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-behance"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="portfolio.php">Portfolio</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="my_orders.php">My Orders</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h3>Contact Info</h3>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> Lagos, Nigeria</p>
                        <p><i class="fas fa-phone"></i> +234 812 345 6789</p>
                        <p><i class="fas fa-envelope"></i> info@zeesigns-express.com</p>
                        <p><i class="fas fa-clock"></i> Mon - Fri: 9:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Zesigns Express. All rights reserved.</p>
                <p style="margin-top: 0.5rem;">Professional Design Services | Transforming Brands</p>
            </div>
        </div>
    </footer>

    <script>
        // JavaScript for interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Create floating particles
            createParticles();
            
            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            const serviceCategories = document.querySelectorAll('.service-category');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    const filter = button.dataset.filter;
                    
                    // Show/hide categories
                    serviceCategories.forEach(category => {
                        if (filter === 'all' || category.dataset.category === filter) {
                            category.style.display = 'block';
                            setTimeout(() => {
                                category.classList.add('visible');
                            }, 50);
                        } else {
                            category.classList.remove('visible');
                            setTimeout(() => {
                                category.style.display = 'none';
                            }, 500);
                        }
                    });
                });
            });
            
            // FAQ toggle functionality
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    // Toggle active class
                    item.classList.toggle('active');
                    
                    // Close other FAQs
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });
                });
            });
            
            // Animate service items on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);
            
            serviceCategories.forEach(category => {
                observer.observe(category);
            });
            
            // Add hover effects to service items
            const serviceItems = document.querySelectorAll('.service-item');
            serviceItems.forEach(item => {
                item.addEventListener('mouseenter', () => {
                    item.style.zIndex = '10';
                });
                
                item.addEventListener('mouseleave', () => {
                    setTimeout(() => {
                        item.style.zIndex = '1';
                    }, 300);
                });
            });
            
            // Function to create floating particles
            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                const particleCount = 50;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');
                    
                    // Random properties
                    const size = Math.random() * 4 + 1;
                    const posX = Math.random() * 100;
                    const delay = Math.random() * 20;
                    const duration = Math.random() * 10 + 10;
                    
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.left = `${posX}%`;
                    particle.style.animationDelay = `${delay}s`;
                    particle.style.animationDuration = `${duration}s`;
                    particle.style.opacity = Math.random() * 0.5 + 0.1;
                    
                    particlesContainer.appendChild(particle);
                }
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
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
        });
    </script>
</body>
</html>