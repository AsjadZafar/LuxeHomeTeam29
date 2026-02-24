// LuxeHome - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initCardHoverEffects();
    initRoomCards();
    initSmoothScrolling();
    initMobileMenu();
    initSearch();
    initCart();
    initAuth();
    
    console.log('LuxeHome - Premium Smart Living initialized');
});

// Card hover effects
function initCardHoverEffects() {
    const cards = document.querySelectorAll('.feature-card, .room-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-0.5rem)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Room card interactions
function initRoomCards() {
    const roomCards = document.querySelectorAll('.room-card');
    
    roomCards.forEach(card => {
        card.addEventListener('click', function() {
            const roomType = this.classList[1];
            console.log(`Navigating to ${roomType} category`);
        });
    });
}

// Smooth scrolling for anchor links
function initSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Mobile menu functionality
function initMobileMenu() {
    console.log('Mobile menu ready for implementation');
}

// ✅ FIX 1: Removed prompt() from initSearch() — it was blocking
// the entire browser tab whenever any button was clicked,
// preventing the chatbot Send button from working.
function initSearch() {
    document.querySelectorAll('.action-btn .fa-search').forEach(btn => {
        btn.closest('.action-btn').addEventListener('click', function() {
            console.log('Search clicked — implement search modal here');
        });
    });
}

// ✅ FIX 2: Removed alert() from initCart() — it was firing when
// the chatbot Send button was clicked (Send also has .action-btn),
// showing "Cart functionality would open here" and swallowing
// the click so luxeChatSend() never ran properly.
function initCart() {
    document.querySelectorAll('.action-btn .fa-shopping-cart').forEach(btn => {
        btn.closest('.action-btn').addEventListener('click', function() {
            console.log('Cart clicked — implement cart modal here');
        });
    });
}

// ✅ FIX 3: Removed alert() from initAuth() — same issue as initCart().
// The alert was intercepting button clicks across the whole page.
function initAuth() {
    document.querySelectorAll('.action-btn .fa-user').forEach(btn => {
        btn.closest('.action-btn').addEventListener('click', function() {
            console.log('User icon clicked — implement auth menu here');
        });
    });
}

// Performance optimization - Lazy loading for images
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Export functions for potential module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initCardHoverEffects,
        initRoomCards,
        initSmoothScrolling,
        initMobileMenu
    };
}
