<?php
session_start();

// Handle form submission
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $service = htmlspecialchars(trim($_POST['service'] ?? ''));
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Process the form (you can add database/email functionality here)
        $message_sent = true;
        
        // For now, we'll just show success message
        // In production, you would:
        // 1. Save to database
        // 2. Send email notification
        // 3. Send confirmation email to user
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Zesigns Express | Get in Touch</title>
    <meta name="description" content="Contact our design team for your next project. We're ready to bring your vision to life.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #ffffff;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.5rem 3rem;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            color: var(--primary);
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
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--secondary);
            background: rgba(34, 211, 238, 0.1);
        }

        .nav-links a.active {
            background: var(--primary);
            color: white;
        }

        /* Video Hero Section */
        .contact-hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
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
            filter: brightness(0.7);
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.9) 0%,
                rgba(15, 23, 42, 0.7) 50%,
                rgba(15, 23, 42, 0.9) 100%);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 600px;
            padding: 0 3rem;
            margin-left: 10%;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            background: linear-gradient(45deg, var(--light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* Contact Form Section */
        .contact-section {
            padding: 6rem 3rem;
            background: var(--dark);
            position: relative;
            z-index: 10;
        }

        .contact-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        /* Contact Info */
        .contact-info {
            padding: 3rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-title {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: var(--light);
            font-weight: 700;
        }

        .info-title span {
            color: var(--secondary);
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(10px);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .info-content h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--light);
        }

        .info-content p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .info-content a {
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .info-content a:hover {
            color: var(--primary);
        }

        /* Social Links */
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .social-link {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--gradient);
            transform: translateY(-5px);
        }

        /* Contact Form */
        .contact-form-container {
            padding: 3rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow);
        }

        .form-title {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: var(--light);
            font-weight: 700;
        }

        .form-title span {
            color: var(--secondary);
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 1.2rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: var(--light);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236366f1'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.5rem center;
            background-size: 1.5rem;
            padding-right: 3.5rem;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 1.2rem 2.5rem;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        /* Messages */
        .alert {
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Map Section */
        .map-section {
            padding: 0 3rem 6rem;
            background: var(--dark);
        }

        .map-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .map-wrapper {
            position: relative;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            height: 500px;
        }

        .map-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.8) 0%,
                rgba(15, 23, 42, 0.4) 50%,
                rgba(15, 23, 42, 0.8) 100%);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-content {
            text-align: center;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 600px;
        }

        .map-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--light);
        }

        .map-content p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .map-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 1rem 2rem;
            background: var(--gradient);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .map-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        /* FAQ Section */
        .faq-section {
            padding: 6rem 3rem;
            background: rgba(255, 255, 255, 0.02);
        }

        .faq-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .faq-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--light);
        }

        .faq-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 4rem;
            font-size: 1.2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-5px);
        }

        .faq-question {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .faq-question i {
            color: var(--secondary);
        }

        .faq-answer {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.02);
            padding: 4rem 3rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--light);
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
            color: var(--secondary);
            padding-left: 10px;
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .hero-content {
                margin-left: 0;
                text-align: center;
                padding: 0 2rem;
            }
            
            .hero-title {
                font-size: 3.5rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .contact-hero {
                min-height: 80vh;
            }
            
            .contact-section,
            .map-section,
            .faq-section {
                padding: 3rem 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .info-title,
            .form-title,
            .map-content h2,
            .faq-title {
                font-size: 2rem;
            }
            
            .contact-info,
            .contact-form-container {
                padding: 2rem;
            }
        }

        /* Video Play Button */
        .video-play-btn {
            position: absolute;
            bottom: 3rem;
            right: 3rem;
            z-index: 3;
            background: var(--gradient);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .video-play-btn:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .video-play-btn i {
            font-size: 1.5rem;
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
            <a href="portfolio.php">Portfolio</a>
            <a href="contact.php" class="active">Contact</a>
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

    <!-- Video Hero Section -->
    <section class="contact-hero">
        <div class="video-container">
            <!-- Video of designer talking with client -->
            <video autoplay muted loop playsinline class="hero-video" id="contactVideo" 
                   poster="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80">
                <source src="https://assets.mixkit.co/videos/preview/mixkit-man-talking-with-woman-in-a-meeting-43775-large.mp4" type="video/mp4">
                <!-- Alternative videos -->
                <source src="https://cdn.pixabay.com/video/2023/04/19/162851-819774271_large.mp4" type="video/mp4">
                <source src="https://player.vimeo.com/external/371433846.sd.mp4?s=2e91e0ea5c8c648fb2c7d3c0cc3c9b4c3d4a69d4&profile_id=164&oauth2_token_id=57447761" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-overlay"></div>
        </div>
        
        <div class="hero-content">
            <h1 class="hero-title">Let's Create<br>Together</h1>
            <p class="hero-subtitle">
                Ready to bring your vision to life? Our team of expert designers is here to 
                listen, collaborate, and create something amazing with you.
            </p>
            <a href="#contact-form" class="submit-btn" style="display: inline-flex; width: auto;">
                <i class="fas fa-paper-plane"></i> Start Conversation
            </a>
        </div>
        
        <!-- Video Play Button -->
        <div class="video-play-btn" id="playBtn">
            <i class="fas fa-volume-up"></i>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2 class="info-title">Get in <span>Touch</span></h2>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Visit Our Studio</h3>
                        <p>123 Design Street, Creative City<br>Digital District, DC 10001</p>
                        <a href="#map" class="map-link">
                            <i class="fas fa-directions"></i> Get Directions
                        </a>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h3>Call Us</h3>
                        <p>Monday - Friday: 9AM - 6PM</p>
                        <a href="tel:+15551234567">+2348082349080</a>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email Us</h3>
                        <p>We respond within 24 hours</p>
                        <a href="mailto:zesignsexpress@gmail.com">zesignsexpress@gmail.com</a>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h3>Business Hours</h3>
                        <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 4PM<br>Sunday: Closed</p>
                    </div>
                </div>
                
                <h3 style="margin-top: 3rem; margin-bottom: 1.5rem;">Follow Us</h3>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-dribbble"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-behance"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-container">
                <h2 class="form-title">Send a <span>Message</span></h2>
                <p class="form-subtitle">
                    Fill out the form below and we'll get back to you as soon as possible. 
                    Let's discuss your project!
                </p>
                
                <?php if($message_sent): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Thank you! Your message has been sent successfully. 
                        We'll get back to you within 24 hours.
                    </div>
                <?php elseif($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form id="contact-form" class="contact-form" method="POST" action="#contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   placeholder="Enter your full name" required
                                   value="<?php echo $_POST['name'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   placeholder="Enter your email" required
                                   value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   placeholder="Enter your phone number"
                                   value="<?php echo $_POST['phone'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" 
                                   placeholder="What is this regarding?"
                                   value="<?php echo $_POST['subject'] ?? ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="service">Service Interested In</label>
                        <select id="service" name="service" class="form-control">
                            <option value="">Select a service</option>
                            <option value="logo" <?php echo ($_POST['service'] ?? '') == 'logo' ? 'selected' : ''; ?>>Logo Design</option>
                            <option value="branding" <?php echo ($_POST['service'] ?? '') == 'branding' ? 'selected' : ''; ?>>Brand Identity</option>
                            <option value="web" <?php echo ($_POST['service'] ?? '') == 'web' ? 'selected' : ''; ?>>Web Design</option>
                            <option value="uiux" <?php echo ($_POST['service'] ?? '') == 'uiux' ? 'selected' : ''; ?>>UI/UX Design</option>
                            <option value="print" <?php echo ($_POST['service'] ?? '') == 'print' ? 'selected' : ''; ?>>Print Design</option>
                            <option value="motion" <?php echo ($_POST['service'] ?? '') == 'motion' ? 'selected' : ''; ?>>Motion Graphics</option>
                            <option value="other" <?php echo ($_POST['service'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message <span class="required">*</span></label>
                        <textarea id="message" name="message" class="form-control" 
                                  placeholder="Tell us about your project..." required
                                  rows="5"><?php echo $_POST['message'] ?? ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section" id="map">
        <div class="map-container">
            <div class="map-wrapper">
                <!-- Google Maps Embed -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a316ffb2e09%3A0x8a8b8b8b8b8b8b8b!2sCreative+District!5e0!3m2!1sen!2sus!4v1547580000000!5m2!1sen!2sus" 
                    width="100%" 
                    height="500" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                
                <div class="map-overlay">
                    <div class="map-content">
                        <h2>Visit Our Studio</h2>
                        <p>Come visit us at our creative studio in the heart of the design district. 
                           We'd love to meet you in person and discuss your project over coffee!</p>
                        <a href="https://goo.gl/maps/example" target="_blank" class="map-btn">
                            <i class="fas fa-map-marked-alt"></i> Open in Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <h2 class="faq-title">Frequently Asked Questions</h2>
            <p class="faq-subtitle">Quick answers to common questions about working with us</p>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle"></i> What's your typical response time?
                    </div>
                    <div class="faq-answer">
                        We respond to all inquiries within 24 hours during business days. For urgent matters, 
                        you can call us directly at +2348082349080.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle"></i> Do you offer free consultations?
                    </div>
                    <div class="faq-answer">
                        Yes! We offer a free 30-minute consultation to discuss your project requirements, 
                        budget, and timeline before starting any work.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle"></i> What information should I provide?
                    </div>
                    <div class="faq-answer">
                        Please share your project goals, target audience, budget range, timeline, 
                        and any examples of designs you like. The more details, the better!
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle"></i> What are your payment terms?
                    </div>
                    <div class="faq-answer">
                        We require a 50% deposit to begin work, with the remaining 50% due upon project 
                        completion. We accept all major credit cards and bank transfers.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle"></i> How long does a typical project take?
                    </div>
                    <div class="faq-answer">
                        Timeline varies by project: Logo design (3-7 days), Brand identity (5-10 days), 
                        Website design (7-20 days). We'll provide a detailed timeline during consultation.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="fas fa-question-circle"></i> Do you work with international clients?
                    </div>
                    <div class="faq-answer">
                        Absolutely! We work with clients worldwide. We're experienced in coordinating 
                        across time zones and can conduct meetings via Zoom, Google Meet, or your preferred platform.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Zesigns Express</h3>
                <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.6;">
                    Creating stunning visual experiences that inspire, engage, and transform businesses worldwide.
                </p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="portfolio.php">Portfolio</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="#">About Us</a></li>
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
                    <li><i class="fas fa-map-marker-alt" style="margin-right: 10px;"></i> 123 Design Street</li>
                    <li><i class="fas fa-phone" style="margin-right: 10px;"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-envelope" style="margin-right: 10px;"></i> hello@zesigns.com</li>
                </ul>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> Zesigns Express. All rights reserved. | Made with <i class="fas fa-heart" style="color: #f59e0b;"></i> for the creative community</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Video Controls
            const video = document.getElementById('contactVideo');
            const playBtn = document.getElementById('playBtn');
            let isMuted = true;
            
            // Toggle video sound
            playBtn.addEventListener('click', function() {
                if (isMuted) {
                    video.muted = false;
                    this.innerHTML = '<i class="fas fa-volume-mute"></i>';
                    this.style.background = 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)';
                    isMuted = false;
                } else {
                    video.muted = true;
                    this.innerHTML = '<i class="fas fa-volume-up"></i>';
                    this.style.background = 'var(--gradient)';
                    isMuted = true;
                }
            });
            
            // Form Validation
            const contactForm = document.getElementById('contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    let isValid = true;
                    
                    // Clear previous errors
                    document.querySelectorAll('.form-control').forEach(input => {
                        input.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                    });
                    
                    // Validate required fields
                    const requiredFields = contactForm.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.style.borderColor = '#ef4444';
                            isValid = false;
                            
                            // Add shake animation
                            field.style.animation = 'shake 0.5s ease';
                            setTimeout(() => {
                                field.style.animation = '';
                            }, 500);
                        }
                    });
                    
                    // Validate email
                    const emailField = contactForm.querySelector('input[type="email"]');
                    if (emailField && emailField.value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(emailField.value)) {
                            emailField.style.borderColor = '#ef4444';
                            isValid = false;
                        }
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Show loading state
                    const submitBtn = contactForm.querySelector('.submit-btn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                    submitBtn.disabled = true;
                    
                    // In production, you would send the form via AJAX here
                    // For now, we'll just submit normally
                    
                    // Re-enable button after 3 seconds (in case of error)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                });
            }
            
            // Add shake animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                    20%, 40%, 60%, 80% { transform: translateX(5px); }
                }
            `;
            document.head.appendChild(style);
            
            // Smooth scrolling for anchor links
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
            
            // Add hover effects to form inputs
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-5px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
            
            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        if (value.length <= 3) {
                            value = `(${value}`;
                        } else if (value.length <= 6) {
                            value = `(${value.slice(0,3)}) ${value.slice(3)}`;
                        } else {
                            value = `(${value.slice(0,3)}) ${value.slice(3,6)}-${value.slice(6,10)}`;
                        }
                    }
                    e.target.value = value;
                });
            }
            
            // Character counter for message
            const messageInput = document.getElementById('message');
            if (messageInput) {
                // Create counter element
                const counter = document.createElement('div');
                counter.style.cssText = `
                    color: rgba(255,255,255,0.5);
                    font-size: 0.85rem;
                    text-align: right;
                    margin-top: 5px;
                `;
                counter.innerHTML = '<span id="charCount">0</span>/2000 characters';
                messageInput.parentElement.appendChild(counter);
                
                messageInput.addEventListener('input', function() {
                    const count = this.value.length;
                    document.getElementById('charCount').textContent = count;
                    
                    // Change color if approaching limit
                    if (count > 1800) {
                        counter.style.color = '#ef4444';
                    } else if (count > 1500) {
                        counter.style.color = '#f59e0b';
                    } else {
                        counter.style.color = 'rgba(255,255,255,0.5)';
                    }
                    
                    // Limit to 2000 characters
                    if (count > 2000) {
                        this.value = this.value.substring(0, 2000);
                    }
                });
                
                // Trigger initial count
                messageInput.dispatchEvent(new Event('input'));
            }
            
            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observe elements
            document.querySelectorAll('.info-item, .faq-item').forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'all 0.5s ease';
                observer.observe(item);
            });
            
            // Auto-play video (with sound muted by default)
            if (video) {
                video.play().catch(error => {
                    console.log('Video autoplay failed:', error);
                    // Fallback: Show play button
                    playBtn.style.display = 'flex';
                });
            }
            
            // Add video error handling
            video.addEventListener('error', function() {
                console.log('Video failed to load');
                // Show static image fallback
                video.style.display = 'none';
                const container = document.querySelector('.video-container');
                container.style.backgroundImage = 'url(https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80)';
                container.style.backgroundSize = 'cover';
                container.style.backgroundPosition = 'center';
            });
        });
    </script>
</body>
</html>