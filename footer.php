<?php
// footer.php
?>
    </main>
    
    <footer style="background: var(--dark-card); padding: 4rem 0 2rem; margin-top: 4rem; border-top: 1px solid var(--border-dark);">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div class="footer-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
                <div class="footer-about">
                    <div class="footer-logo" style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem;">
                        <i class="fas fa-palette" style="color: var(--primary); font-size: 2rem;"></i>
                        <span style="font-size: 1.8rem; font-weight: 800; color: var(--text-light);">Zesigns Express</span>
                    </div>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Professional design services that transform your vision into stunning visual experiences.</p>
                    <div class="social-links" style="display: flex; gap: 1rem;">
                        <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none;"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h3 style="color: var(--text-light); margin-bottom: 1.5rem; font-size: 1.3rem;">Quick Links</h3>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 0.8rem;"><a href="index.php" style="color: var(--text-muted); text-decoration: none; transition: color 0.3s ease;">Home</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="services.php" style="color: var(--text-muted); text-decoration: none; transition: color 0.3s ease;">Services</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="portfolio.php" style="color: var(--text-muted); text-decoration: none; transition: color 0.3s ease;">Portfolio</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="about.php" style="color: var(--text-muted); text-decoration: none; transition: color 0.3s ease;">About Us</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="contact.php" style="color: var(--text-muted); text-decoration: none; transition: color 0.3s ease;">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h3 style="color: var(--text-light); margin-bottom: 1.5rem; font-size: 1.3rem;">Contact Info</h3>
                    <div class="contact-info" style="color: var(--text-muted);">
                        <p style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 10px;"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Lagos, Nigeria</p>
                        <p style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 10px;"><i class="fas fa-phone" style="color: var(--primary);"></i> +234 812 345 6789</p>
                        <p style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 10px;"><i class="fas fa-envelope" style="color: var(--primary);"></i> info@zeesigns-express.com</p>
                        <p style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-clock" style="color: var(--primary);"></i> Mon - Fri: 9:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom" style="text-align: center; padding-top: 2rem; border-top: 1px solid var(--border-dark);">
                <p style="color: var(--text-muted);">&copy; <?php echo date('Y'); ?> Zesigns Express. All rights reserved.</p>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Professional Design Services | Transforming Brands</p>
            </div>
        </div>
    </footer>
</body>
</html>