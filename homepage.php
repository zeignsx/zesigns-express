<!-- public/home.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Hub | Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .home-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .user-greeting h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }

        .user-greeting p {
            font-size: 16px;
            opacity: 0.9;
        }

        .logout-btn {
            padding: 12px 24px;
            background: white;
            color: #6a11cb;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .card p {
            color: #666;
            line-height: 1.6;
        }

        .card-icon {
            font-size: 40px;
            color: #6a11cb;
            margin-bottom: 15px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .recent-designs {
            margin-top: 40px;
        }

        .design-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .design-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .design-item:hover {
            transform: scale(1.03);
        }

        .design-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .design-info {
            padding: 15px;
            background: white;
        }

        .welcome-message {
            text-align: center;
            padding: 40px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            margin: 30px 0;
        }

        .welcome-message h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .welcome-message p {
            color: #666;
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="home-container">
        <!-- Header with User Greeting -->
        <header class="header">
            <div class="user-greeting">
                <h1 id="greeting">Welcome back, Designer!</h1>
                <p id="user-specialty">Ready to create something amazing today?</p>
            </div>
            <button class="logout-btn" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </header>

        <!-- Welcome Message -->
        <div class="welcome-message">
            <h2>Your Creative Workspace</h2>
            <p>Access your projects, connect with other designers, and explore new inspiration in our community.</p>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">24</div>
                <div class="stat-label">Projects Created</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">156</div>
                <div class="stat-label">Designs Shared</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">89</div>
                <div class="stat-label">Connections</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Awards Won</div>
            </div>
        </div>

        <!-- Dashboard -->
        <div class="dashboard">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h3>My Projects</h3>
                <p>Access and manage all your design projects in one place. Continue where you left off or start a new masterpiece.</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Community</h3>
                <p>Connect with fellow designers, share feedback, and collaborate on exciting projects.</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Learning Hub</h3>
                <p>Enhance your skills with our curated tutorials, courses, and design resources.</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Inspiration Gallery</h3>
                <p>Explore thousands of designs from our creative community to spark your next idea.</p>
            </div>
        </div>

        <!-- Recent Designs -->
        <div class="recent-designs">
            <h2>Recent Designs from Community</h2>
            <div class="design-grid">
                <div class="design-item">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="UI Design">
                    <div class="design-info">
                        <h4>Modern Dashboard UI</h4>
                        <p>By Sarah Chen</p>
                    </div>
                </div>
                <div class="design-item">
                    <img src="https://images.unsplash.com/photo-1558655146-9f40138edfeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Brand Identity">
                    <div class="design-info">
                        <h4>Brand Identity Package</h4>
                        <p>By Miguel Rodriguez</p>
                    </div>
                </div>
                <div class="design-item">
                    <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Mobile App">
                    <div class="design-info">
                        <h4>Fitness App Design</h4>
                        <p>By Alex Johnson</p>
                    </div>
                </div>
                <div class="design-item">
                    <img src="https://images.unsplash.com/photo-1567446537710-0c5ff5a61ac4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Web Design">
                    <div class="design-info">
                        <h4>E-commerce Website</h4>
                        <p>By Emma Wilson</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Check if user is logged in and display their name
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('/api/user');
                const data = await response.json();
                
                if (data.success) {
                    const user = data.user;
                    const greeting = document.getElementById('greeting');
                    const specialty = document.getElementById('user-specialty');
                    
                    // Get current hour for time-based greeting
                    const hour = new Date().getHours();
                    let timeGreeting = '';
                    
                    if (hour < 12) timeGreeting = 'Good morning';
                    else if (hour < 18) timeGreeting = 'Good afternoon';
                    else timeGreeting = 'Good evening';
                    
                    greeting.textContent = `${timeGreeting}, ${user.name}!`;
                    specialty.textContent = `Specialty: ${user.specialty || 'Designer'}`;
                } else {
                    // Not logged in, redirect to login page
                    window.location.href = '/';
                }
            } catch (error) {
                console.error('Error fetching user:', error);
                window.location.href = '/';
            }
        });

        // Logout functionality
        document.getElementById('logout-btn').addEventListener('click', async () => {
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '/';
                }
            } catch (error) {
                console.error('Logout error:', error);
            }
        });
    </script>
</body>
</html>