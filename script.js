// LuxeHome - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initCardHoverEffects();
    initRoomCards();
    initSmoothScrolling();
    initMobileMenu();
    
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
            const roomType = this.classList[1]; // living-room, bedroom, etc.
            console.log(`Navigating to ${roomType} category`);
            // In a real implementation, this would navigate to the category page
            // window.location.href = `/shop?category=${roomType}`;
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

// Mobile menu functionality (for future implementation)
function initMobileMenu() {
    // This would be implemented when adding a mobile menu
    console.log('Mobile menu ready for implementation');
}

// Newsletter subscription
function initNewsletter() {
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Simulate API call
            console.log(`Subscribing email: ${email}`);
            
            // Show success message
            alert('Thank you for subscribing to our newsletter!');
            this.reset();
        });
    }
}

// Product search functionality
function initSearch() {
    const searchButtons = document.querySelectorAll('.action-btn .fa-search').forEach(btn => {
        btn.closest('.action-btn').addEventListener('click', function() {
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search products...';
            searchInput.className = 'search-input';
            
            // Simple search implementation
            // In a real app, this would open a search modal
            const searchTerm = prompt('Enter product name:');
            if (searchTerm) {
                console.log(`Searching for: ${searchTerm}`);
                // Implement search logic here
            }
        });
    });
}

// Shopping cart functionality
function initCart() {
    const cartButtons = document.querySelectorAll('.action-btn .fa-shopping-cart').forEach(btn => {
        btn.closest('.action-btn').addEventListener('click', function() {
            console.log('Opening shopping cart');
            // In a real implementation, this would open a cart sidebar/modal
            alert('Cart functionality would open here');
        });
    });
}

// User authentication
function initAuth() {
    const userButtons = document.querySelectorAll('.action-btn .fa-user').forEach(btn => {
        btn.closest('.action-btn').addEventListener('click', function() {
            console.log('Opening user menu');
            // In a real implementation, this would open a user dropdown
            alert('User authentication menu would open here');
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