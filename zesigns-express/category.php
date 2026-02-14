<?php
session_start();
require_once 'includes/db.php';

$category_slug = $_GET['slug'] ?? '';
$category = null;
$services = [];

if ($category_slug) {
    // Get category details
    $category_query = "SELECT * FROM service_categories WHERE slug = ? AND is_active = 1";
    $stmt = $conn->prepare($category_query);
    $stmt->bind_param("s", $category_slug);
    $stmt->execute();
    $category_result = $stmt->get_result();
    
    if ($category_result->num_rows > 0) {
        $category = $category_result->fetch_assoc();
        
        // Get services in this category
        $services_query = "SELECT * FROM services WHERE category_id = ? AND status = 'active' ORDER BY display_order";
        $stmt = $conn->prepare($services_query);
        $stmt->bind_param("i", $category['id']);
        $stmt->execute();
        $services_result = $stmt->get_result();
        
        while($service = $services_result->fetch_assoc()) {
            $services[] = $service;
        }
    } else {
        header("Location: services.php");
        exit();
    }
} else {
    header("Location: services.php");
    exit();
}

// Function to get service images
function getServiceImages($service_id, $conn) {
    $images_query = "SELECT * FROM service_images WHERE service_id = ? ORDER BY display_order";
    $stmt = $conn->prepare($images_query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while($image = $result->fetch_assoc()) {
        $images[] = $image;
    }
    return $images;
}

// Function to parse features
function parseFeatures($features_json) {
    $features = json_decode($features_json, true);
    return is_array($features) ? $features : [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Zesigns Express</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Category Hero -->
    <section class="services-hero" style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary) 100%);">
        <div class="container">
            <div class="category-header" style="text-align: center; color: white;">
                <div class="category-icon" style="background: rgba(255,255,255,0.2);">
                    <i class="<?php echo $category['icon']; ?>" style="font-size: 3rem;"></i>
                </div>
                <h1><?php echo htmlspecialchars($category['name']); ?></h1>
                <p><?php echo htmlspecialchars($category['description']); ?></p>
                <p class="category-count" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; border-radius: 30px; display: inline-block;">
                    <?php echo count($services); ?> Services Available
                </p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="services-grid">
        <div class="container">
            <?php if(empty($services)): ?>
                <div style="text-align: center; padding: 4rem;">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                    <h3>No services available in this category yet</h3>
                    <p>Check back soon or browse our other categories</p>
                    <a href="services.php" class="btn primary">Browse All Services</a>
                </div>
            <?php else: ?>
                <div class="services-row">
                    <?php foreach($services as $service): 
                        $features = parseFeatures($service['features']);
                        $images = getServiceImages($service['id'], $conn);
                    ?>
                    <div class="service-item">
                        <?php if($service['badge_text']): ?>
                        <span class="service-badge badge-<?php echo $service['badge_color'] ?? 'primary'; ?>">
                            <?php echo htmlspecialchars($service['badge_text']); ?>
                        </span>
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p class="service-description"><?php echo htmlspecialchars($service['short_description']); ?></p>
                        
                        <?php if(!empty($features)): ?>
                        <ul class="service-features">
                            <?php foreach(array_slice($features, 0, 5) as $feature): ?>
                            <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        
                        <div class="service-pricing">
                            <div class="price-tag"><?php echo htmlspecialchars($service['price_range']); ?></div>
                            <div class="price-note">Delivery: <?php echo htmlspecialchars($service['delivery_days']); ?></div>
                        </div>
                        
                        <div class="service-actions">
                            <a href="order.php?service=<?php echo $service['slug']; ?>" class="action-btn primary">
                                <i class="fas fa-shopping-cart"></i> Order Now
                            </a>
                            <a href="service-detail.php?slug=<?php echo $service['slug']; ?>" class="action-btn secondary">
                                <i class="fas fa-info-circle"></i> View Details
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>