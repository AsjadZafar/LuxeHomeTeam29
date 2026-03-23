// Accessibility Panel Functionality - Cross-Page Support
document.addEventListener('DOMContentLoaded', function() {
    // Check if accessibility elements exist on this page
    const panel = document.getElementById('accessibilityPanel');
    const toggleBtn = document.getElementById('togglePanel');
    const closeBtn = document.getElementById('closePanel');
    const overlay = document.getElementById('panelOverlay');
    const resetBtn = document.getElementById('resetSettings');
    
    // Always load and apply settings, even if panel doesn't exist on this page
    loadAndApplyAccessibilitySettings();
    
    // Only set up panel functionality if the elements exist
    if (panel && toggleBtn && closeBtn && overlay) {
        setupPanelFunctionality();
    }
    
    function setupPanelFunctionality() {
        // Toggle panel visibility
        toggleBtn.addEventListener('click', function() {
            panel.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close panel
        closeBtn.addEventListener('click', closePanel);
        overlay.addEventListener('click', closePanel);
        
        // Close panel function
        function closePanel() {
            panel.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Reset all settings
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                resetAccessibilitySettings();
            });
        }
        
        // Listen for changes in accessibility options
        const accessibilityOptions = document.querySelectorAll('.accessibility-option input');
        accessibilityOptions.forEach(option => {
            option.addEventListener('change', function() {
                // Add active class to parent for visual feedback
                const parent = this.closest('.accessibility-option');
                if (this.checked) {
                    parent.classList.add('active');
                } else {
                    parent.classList.remove('active');
                }
                
                applyAccessibilitySettings();
                saveAccessibilitySettings();
            });
        });
        
        // Font size slider
        const fontSizeSlider = document.getElementById('fontSize');
        const fontSizeDisplay = document.getElementById('fontSizeDisplay');
        
        if (fontSizeSlider && fontSizeDisplay) {
            fontSizeSlider.addEventListener('input', function() {
                const sizes = ['Small', 'Normal', 'Large', 'Extra Large'];
                fontSizeDisplay.textContent = sizes[this.value];
                applyAccessibilitySettings();
                saveAccessibilitySettings();
            });
        }
        
        // Keyboard navigation for panel
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && panel.classList.contains('open')) {
                closePanel();
            }
        });
    }
    
    function loadAndApplyAccessibilitySettings() {
        const savedSettings = localStorage.getItem('luxehomeAccessibility');
        
        if (savedSettings) {
            const settings = JSON.parse(savedSettings);
            
            // Update form elements if they exist
            const darkModeCheckbox = document.getElementById('darkMode');
            const highContrastCheckbox = document.getElementById('highContrast');
            const fontSizeSlider = document.getElementById('fontSize');
            const dyslexiaFontCheckbox = document.getElementById('dyslexiaFont');
            const lineSpacingCheckbox = document.getElementById('lineSpacing');
            const focusIndicatorCheckbox = document.getElementById('focusIndicator');
            const skipLinksCheckbox = document.getElementById('skipLinks');
            const fontSizeDisplay = document.getElementById('fontSizeDisplay');
            
            if (darkModeCheckbox) darkModeCheckbox.checked = settings.darkMode;
            if (highContrastCheckbox) highContrastCheckbox.checked = settings.highContrast;
            if (fontSizeSlider) fontSizeSlider.value = settings.fontSize;
            if (dyslexiaFontCheckbox) dyslexiaFontCheckbox.checked = settings.dyslexiaFont;
            if (lineSpacingCheckbox) lineSpacingCheckbox.checked = settings.lineSpacing;
            if (focusIndicatorCheckbox) focusIndicatorCheckbox.checked = settings.focusIndicator;
            if (skipLinksCheckbox) skipLinksCheckbox.checked = settings.skipLinks;
            
            // Update font size display if it exists
            if (fontSizeDisplay) {
                const sizes = ['Small', 'Normal', 'Large', 'Extra Large'];
                fontSizeDisplay.textContent = sizes[settings.fontSize];
            }
            
            // Apply active classes to options
            const accessibilityOptions = document.querySelectorAll('.accessibility-option input');
            accessibilityOptions.forEach(option => {
                if (option.checked) {
                    option.closest('.accessibility-option').classList.add('active');
                }
            });
        }
        
        // Always apply settings to the page
        applyAccessibilitySettings();
    }
    
    function resetAccessibilitySettings() {
        // Reset checkboxes
        const darkModeCheckbox = document.getElementById('darkMode');
        const highContrastCheckbox = document.getElementById('highContrast');
        const fontSizeSlider = document.getElementById('fontSize');
        const dyslexiaFontCheckbox = document.getElementById('dyslexiaFont');
        const lineSpacingCheckbox = document.getElementById('lineSpacing');
        const focusIndicatorCheckbox = document.getElementById('focusIndicator');
        const skipLinksCheckbox = document.getElementById('skipLinks');
        const fontSizeDisplay = document.getElementById('fontSizeDisplay');
        
        if (darkModeCheckbox) darkModeCheckbox.checked = false;
        if (highContrastCheckbox) highContrastCheckbox.checked = false;
        if (fontSizeSlider) fontSizeSlider.value = 1;
        if (dyslexiaFontCheckbox) dyslexiaFontCheckbox.checked = false;
        if (lineSpacingCheckbox) lineSpacingCheckbox.checked = false;
        if (focusIndicatorCheckbox) focusIndicatorCheckbox.checked = false;
        if (skipLinksCheckbox) skipLinksCheckbox.checked = false;
        
        if (fontSizeDisplay) {
            fontSizeDisplay.textContent = 'Normal';
        }
        
        // Remove active classes
        const accessibilityOptions = document.querySelectorAll('.accessibility-option');
        accessibilityOptions.forEach(option => {
            option.classList.remove('active');
        });
        
        // Apply changes
        applyAccessibilitySettings();
        saveAccessibilitySettings();
        
        // Show confirmation
        showNotification('All accessibility settings have been reset');
    }
    
    // Apply accessibility settings to current page
    function applyAccessibilitySettings() {
        const body = document.body;
        const savedSettings = localStorage.getItem('luxehomeAccessibility');
        
        if (!savedSettings) return;
        
        const settings = JSON.parse(savedSettings);
        
        // Dark mode
        if (settings.darkMode) {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
        
        // High contrast mode
        if (settings.highContrast) {
            body.classList.add('high-contrast');
        } else {
            body.classList.remove('high-contrast');
        }
        
        // Font size
        body.classList.remove('text-small', 'text-normal', 'text-large', 'text-xlarge');
        
        if (settings.fontSize == 0) {
            body.classList.add('text-small');
        } else if (settings.fontSize == 1) {
            body.classList.add('text-normal');
        } else if (settings.fontSize == 2) {
            body.classList.add('text-large');
        } else if (settings.fontSize == 3) {
            body.classList.add('text-xlarge');
        }
        
        // Dyslexia font
        if (settings.dyslexiaFont) {
            body.classList.add('dyslexia-friendly');
            // Add the font to the page
            if (!document.querySelector('#dyslexia-font')) {
                const link = document.createElement('link');
                link.id = 'dyslexia-font';
                link.rel = 'stylesheet';
                link.href = 'https://fonts.googleapis.com/css2?family=Open+Dyslexic:wght@400;700&display=swap';
                document.head.appendChild(link);
            }
        } else {
            body.classList.remove('dyslexia-friendly');
        }
        
        // Line spacing
        if (settings.lineSpacing) {
            body.classList.add('increased-spacing');
            // Apply to specific elements for better control
            document.querySelectorAll('p, li, .feature-description, .section-description, .hero-description').forEach(el => {
                el.style.lineHeight = '1.8';
            });
        } else {
            body.classList.remove('increased-spacing');
            // Reset line heights
            document.querySelectorAll('p, li, .feature-description, .section-description, .hero-description').forEach(el => {
                el.style.lineHeight = '';
            });
        }
        
        // Focus indicators
        if (settings.focusIndicator) {
            body.classList.add('enhanced-focus');
        } else {
            body.classList.remove('enhanced-focus');
        }
        
        // Skip links
        if (settings.skipLinks) {
            body.classList.add('skip-links-visible');
            // Ensure skip link exists
            if (!document.querySelector('.skip-link')) {
                const skipLink = document.createElement('a');
                skipLink.href = '#main-content';
                skipLink.className = 'skip-link';
                skipLink.textContent = 'Skip to main content';
                document.body.insertBefore(skipLink, document.body.firstChild);
                
                // Add main-content id to main element if it doesn't exist
                const mainContent = document.querySelector('main');
                if (mainContent && !mainContent.id) {
                    mainContent.id = 'main-content';
                }
            }
        } else {
            body.classList.remove('skip-links-visible');
        }
    }
    
    // Save settings to localStorage
    function saveAccessibilitySettings() {
        const darkMode = document.getElementById('darkMode') ? document.getElementById('darkMode').checked : false;
        const highContrast = document.getElementById('highContrast') ? document.getElementById('highContrast').checked : false;
        const fontSize = document.getElementById('fontSize') ? document.getElementById('fontSize').value : 1;
        const dyslexiaFont = document.getElementById('dyslexiaFont') ? document.getElementById('dyslexiaFont').checked : false;
        const lineSpacing = document.getElementById('lineSpacing') ? document.getElementById('lineSpacing').checked : false;
        const focusIndicator = document.getElementById('focusIndicator') ? document.getElementById('focusIndicator').checked : false;
        const skipLinks = document.getElementById('skipLinks') ? document.getElementById('skipLinks').checked : false;
        
        const settings = {
            darkMode,
            highContrast,
            fontSize: parseInt(fontSize),
            dyslexiaFont,
            lineSpacing,
            focusIndicator,
            skipLinks
        };
        
        localStorage.setItem('luxehomeAccessibility', JSON.stringify(settings));
    }
    
    // Show notification function
    function showNotification(message) {
        // Remove existing notification if any
        const existingNotification = document.querySelector('.accessibility-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'accessibility-notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #059669;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1001;
            font-weight: 500;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateY(0)';
            notification.style.opacity = '1';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateY(100px)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
});