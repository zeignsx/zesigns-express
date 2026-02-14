// portfolio.js - Save this in js/ folder
const portfolioData = [
    // ... [same portfolio data array as above] ...
];

document.addEventListener('DOMContentLoaded', function() {
    initPortfolio();
});

function initPortfolio() {
    showLoadingScreen();
    createMouseFollower();
    
    setTimeout(() => {
        renderPortfolio();
        setupFilterButtons();
        setupStatsAnimation();
        setupModal();
        setupPortfolioAnimations();
        hideLoadingScreen();
    }, 1000);
    
    setupScrollEffects();
    setupSmoothScrolling();
}

// ... [all other functions from above] ...