// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if(targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if(targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Service card hover effect
document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.classList.add('hovered');
    });
    
    card.addEventListener('mouseleave', () => {
        card.classList.remove('hovered');
    });
});

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.background = 'rgba(15, 23, 42, 0.9)';
        header.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.background = 'transparent';
        header.style.boxShadow = 'none';
    }
}); 