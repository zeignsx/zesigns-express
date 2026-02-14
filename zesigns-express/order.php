<?php
session_start();

// Define service details
$services = [
    'logo-basic' => [
        'name' => 'Logo Design Package',
        'price' => '$149 - $499',
        'description' => 'Complete logo design with multiple concepts, revisions, and final files in all formats.',
        'type' => 'logo'
    ],
    'brand-identity' => [
        'name' => 'Complete Brand Identity',
        'price' => '$299 - $899',
        'description' => 'Full brand identity package including logo, colors, typography, and brand guidelines.',
        'type' => 'branding'
    ],
    'logo-redesign' => [
        'name' => 'Logo Redesign',
        'price' => '$199 - $599',
        'description' => 'Refresh your existing logo with modern design while maintaining brand recognition.',
        'type' => 'logo'
    ],
    'website-ui' => [
        'name' => 'Website UI/UX Design',
        'price' => '$499 - $2,499',
        'description' => 'Complete website design with user experience research and interactive prototypes.',
        'type' => 'web'
    ],
    'mobile-app' => [
        'name' => 'Mobile App UI Design',
        'price' => '$799 - $3,499',
        'description' => 'Native mobile app design for iOS and Android with user-centered approach.',
        'type' => 'app'
    ],
    'dashboard' => [
        'name' => 'Dashboard & Admin Panel',
        'price' => '$999 - $4,999',
        'description' => 'Complex dashboard interfaces with data visualization and user management.',
        'type' => 'web'
    ],
    'stationery' => [
        'name' => 'Business Stationery',
        'price' => '$99 - $299',
        'description' => 'Complete business stationery set including letterhead, business cards, and envelopes.',
        'type' => 'print'
    ],
    'brochure' => [
        'name' => 'Brochure & Catalog Design',
        'price' => '$199 - $799',
        'description' => 'Multi-page brochure and catalog design with professional layout and typography.',
        'type' => 'print'
    ],
    'flyer' => [
        'name' => 'Flyer & Poster Design',
        'price' => '$79 - $199',
        'description' => 'Eye-catching flyers and posters for events, promotions, and announcements.',
        'type' => 'print'
    ]
];

// Get selected service
$selected_service = isset($_GET['service']) ? $_GET['service'] : '';
$service_type = isset($_GET['type']) ? $_GET['type'] : '';

if (empty($selected_service) || !isset($services[$selected_service])) {
    header('Location: services.php');
    exit();
}

$service = $services[$selected_service];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $order_data = [
        'order_id' => 'ORD' . time() . rand(100, 999),
        'service' => $selected_service,
        'service_name' => $service['name'],
        'customer_name' => $_POST['full_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'company' => $_POST['company'],
        'industry' => $_POST['industry'],
        'description' => $_POST['description'],
        'deadline' => $_POST['deadline'],
        'budget' => $_POST['budget'],
        'colors' => $_POST['colors'],
        'inspiration' => $_POST['inspiration'],
        'additional_info' => $_POST['additional_info'],
        'order_date' => date('Y-m-d H:i:s'),
        'status' => 'pending'
    ];
    
    // Store order in session
    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }
    $_SESSION['orders'][] = $order_data;
    
    // Store order ID for success page
    $_SESSION['last_order_id'] = $order_data['order_id'];
    
    // Redirect to success page
    header('Location: order_success.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - <?php echo $service['name']; ?> | Zesigns Express</title>
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
        
        .order-hero {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1e1b4b 100%);
            padding: 140px 0 60px;
            text-align: center;
        }
        
        .order-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .service-info {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .service-info h2 {
            font-size: 1.8rem;
            color: var(--text-light);
        }
        
        .service-price {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .order-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 3rem;
            padding: 3rem 0;
        }
        
        @media (max-width: 992px) {
            .order-container {
                grid-template-columns: 1fr;
            }
        }
        
        .order-form {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 3rem;
            border: 1px solid var(--border-dark);
        }
        
        .order-form h3 {
            font-size: 1.8rem;
            margin-bottom: 2rem;
            color: var(--text-light);
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .form-group.required label::after {
            content: ' *';
            color: var(--danger);
        }
        
        .form-control {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        .order-summary {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid var(--border-dark);
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-dark);
        }
        
        .summary-item.total {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-light);
            border-bottom: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--border-dark);
        }
        
        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.4s ease;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            width: 100%;
            margin-top: 2rem;
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
        
        .btn.primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .form-note {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .form-note i {
            color: var(--primary);
            margin-right: 0.5rem;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: var(--border-dark);
            z-index: 1;
        }
        
        .step {
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .step-icon {
            width: 40px;
            height: 40px;
            background: var(--dark-card);
            border: 2px solid var(--border-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 700;
            color: var(--text-muted);
        }
        
        .step.active .step-icon {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .step-label {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .step.active .step-label {
            color: var(--text-light);
            font-weight: 600;
        }
        
        .file-upload {
            border: 2px dashed var(--border-dark);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload:hover {
            border-color: var(--primary);
            background: rgba(109, 40, 217, 0.05);
        }
        
        .file-upload i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .file-input {
            display: none;
        }
        
        .uploaded-files {
            margin-top: 1rem;
        }
        
        .file-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 0.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .file-item i {
            color: var(--danger);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .order-hero h1 {
                font-size: 2.2rem;
            }
            
            .order-form {
                padding: 2rem;
            }
            
            .service-price {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="order-hero">
        <div class="container">
            <h1>Place Your Order</h1>
            <p>Fill out the form below to get started with your <?php echo $service['name']; ?> project</p>
        </div>
    </section>
    
    <div class="container">
        <div class="service-info">
            <div>
                <h2><?php echo $service['name']; ?></h2>
                <p style="color: var(--text-muted); margin-top: 0.5rem;"><?php echo $service['description']; ?></p>
            </div>
            <div class="service-price"><?php echo $service['price']; ?></div>
        </div>
        
        <div class="progress-steps">
            <div class="step active">
                <div class="step-icon">1</div>
                <div class="step-label">Order Details</div>
            </div>
            <div class="step">
                <div class="step-icon">2</div>
                <div class="step-label">Review & Pay</div>
            </div>
            <div class="step">
                <div class="step-icon">3</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
        
        <form method="POST" class="order-container">
            <div class="order-form">
                <h3>Project Details</h3>
                
                <div class="form-row">
                    <div class="form-group required">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group required">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group required">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Company Name</label>
                        <input type="text" id="company" name="company" class="form-control">
                    </div>
                </div>
                
                <div class="form-group required">
                    <label for="industry">Industry / Business Type</label>
                    <select id="industry" name="industry" class="form-control" required>
                        <option value="">Select your industry</option>
                        <option value="technology">Technology</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="finance">Finance</option>
                        <option value="retail">Retail/E-commerce</option>
                        <option value="education">Education</option>
                        <option value="food">Food & Beverage</option>
                        <option value="real-estate">Real Estate</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group required">
                    <label for="description">Project Description</label>
                    <textarea id="description" name="description" class="form-control" required placeholder="Describe your project in detail..."></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group required">
                        <label for="deadline">Desired Deadline</label>
                        <select id="deadline" name="deadline" class="form-control" required>
                            <option value="">Select deadline</option>
                            <option value="urgent">Urgent (3-5 days)</option>
                            <option value="1-week">1 Week</option>
                            <option value="2-weeks">2 Weeks</option>
                            <option value="3-weeks">3 Weeks</option>
                            <option value="1-month">1 Month</option>
                            <option value="flexible">Flexible</option>
                        </select>
                    </div>
                    
                    <div class="form-group required">
                        <label for="budget">Budget Range</label>
                        <select id="budget" name="budget" class="form-control" required>
                            <option value="">Select budget</option>
                            <option value="100-300">$100 - $300</option>
                            <option value="300-600">$300 - $600</option>
                            <option value="600-1000">$600 - $1,000</option>
                            <option value="1000-2000">$1,000 - $2,000</option>
                            <option value="2000-5000">$2,000 - $5,000</option>
                            <option value="5000+">$5,000+</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="colors">Preferred Colors</label>
                    <input type="text" id="colors" name="colors" class="form-control" placeholder="e.g., Blue, White, Gold">
                </div>
                
                <div class="form-group">
                    <label for="inspiration">Inspiration / Examples</label>
                    <textarea id="inspiration" name="inspiration" class="form-control" placeholder="Links to websites or designs you like..."></textarea>
                </div>
                
                <div class="form-group">
                    <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload reference files</p>
                        <p style="font-size: 0.9rem; color: var(--text-muted);">Supports: JPG, PNG, PDF, AI, PSD</p>
                        <input type="file" id="fileInput" class="file-input" multiple>
                    </div>
                    <div id="uploadedFiles" class="uploaded-files"></div>
                </div>
                
                <div class="form-group">
                    <label for="additional_info">Additional Information</label>
                    <textarea id="additional_info" name="additional_info" class="form-control" placeholder="Any other details or requirements..."></textarea>
                </div>
                
                <div class="form-note">
                    <i class="fas fa-info-circle"></i>
                    After submitting your order, our team will review it and contact you within 24 hours to discuss the project and provide a final quote.
                </div>
                
                <button type="submit" class="btn primary">
                    <i class="fas fa-paper-plane"></i> Submit Order
                </button>
            </div>
            
            <div class="order-summary">
                <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
                
                <div class="summary-item">
                    <span>Service:</span>
                    <span><?php echo $service['name']; ?></span>
                </div>
                
                <div class="summary-item">
                    <span>Price Range:</span>
                    <span><?php echo $service['price']; ?></span>
                </div>
                
                <div class="summary-item">
                    <span>Service Type:</span>
                    <span>
                        <?php 
                        switch($service_type) {
                            case 'logo': echo 'Logo Design'; break;
                            case 'branding': echo 'Brand Identity'; break;
                            case 'web': echo 'Web Design'; break;
                            case 'app': echo 'App Design'; break;
                            case 'print': echo 'Print Design'; break;
                            default: echo 'Design Service';
                        }
                        ?>
                    </span>
                </div>
                
                <div class="summary-item">
                    <span>Delivery Time:</span>
                    <span>
                        <?php
                        switch($selected_service) {
                            case 'logo-basic': echo '3-7 days'; break;
                            case 'brand-identity': echo '5-10 days'; break;
                            case 'logo-redesign': echo '4-8 days'; break;
                            case 'website-ui': echo '7-14 days'; break;
                            case 'mobile-app': echo '10-20 days'; break;
                            case 'dashboard': echo '14-25 days'; break;
                            case 'stationery': echo '3-5 days'; break;
                            case 'brochure': echo '5-10 days'; break;
                            case 'flyer': echo '2-4 days'; break;
                            default: echo '5-10 days';
                        }
                        ?>
                    </span>
                </div>
                
                <div class="summary-item">
                    <span>Revisions:</span>
                    <span>Unlimited</span>
                </div>
                
                <div class="summary-item">
                    <span>Source Files:</span>
                    <span>✓ Included</span>
                </div>
                
                <div class="summary-item total">
                    <span>Total:</span>
                    <span><?php echo $service['price']; ?></span>
                </div>
                
                <div style="margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
                    <p><i class="fas fa-check-circle" style="color: var(--success);"></i> 100% Satisfaction Guarantee</p>
                    <p><i class="fas fa-check-circle" style="color: var(--success);"></i> Unlimited Revisions</p>
                    <p><i class="fas fa-check-circle" style="color: var(--success);"></i> Money Back Guarantee</p>
                </div>
            </div>
        </form>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // File upload functionality
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const uploadedFiles = document.getElementById('uploadedFiles');
            uploadedFiles.innerHTML = '';
            
            Array.from(e.target.files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <span>${file.name}</span>
                    <i class="fas fa-times" onclick="removeFile(this)"></i>
                `;
                uploadedFiles.appendChild(fileItem);
            });
        });
        
        function removeFile(element) {
            element.parentElement.remove();
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--danger)';
                    isValid = false;
                } else {
                    field.style.borderColor = 'var(--border-dark)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
</body>
</html>