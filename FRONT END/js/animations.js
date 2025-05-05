// Scroll Animation Handler
document.addEventListener('DOMContentLoaded', () => {
    // Initialize scroll animations
    initScrollAnimations();
    
    // Initialize hover effects
    initHoverEffects();
    
    // Initialize form animations
    initFormAnimations();
});

// Scroll Animation Function
function initScrollAnimations() {
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });
    
    fadeElements.forEach(element => {
        observer.observe(element);
    });
}

// Hover Effects
function initHoverEffects() {
    // Add hover effect to cards
    const cards = document.querySelectorAll('.card, .course-card, .teacher-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
    
    // Add hover effect to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translateY(0)';
        });
    });
}

// Form Animations
function initFormAnimations() {
    const inputs = document.querySelectorAll('.form-input, .form-control');
    
    inputs.forEach(input => {
        // Add focus animation
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) {
                input.parentElement.classList.remove('focused');
            }
        });
    });
}

// Loading Animation
function showLoading() {
    const loadingElement = document.createElement('div');
    loadingElement.className = 'loading-spinner';
    document.body.appendChild(loadingElement);
}

function hideLoading() {
    const loadingElement = document.querySelector('.loading-spinner');
    if (loadingElement) {
        loadingElement.remove();
    }
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Video Player Controls Animation
const videoPlayers = document.querySelectorAll('.video-player');
videoPlayers.forEach(player => {
    const controls = player.querySelector('.video-controls');
    if (controls) {
        player.addEventListener('mousemove', () => {
            controls.style.opacity = '1';
        });
        
        player.addEventListener('mouseleave', () => {
            controls.style.opacity = '0';
        });
    }
});

// Search Bar Animation
const searchBars = document.querySelectorAll('.search-bar');
searchBars.forEach(searchBar => {
    searchBar.addEventListener('focus', () => {
        searchBar.style.width = '100%';
    });
    
    searchBar.addEventListener('blur', () => {
        if (!searchBar.value) {
            searchBar.style.width = 'auto';
        }
    });
});

// Dropdown Menu Animation
const dropdowns = document.querySelectorAll('.dropdown');
dropdowns.forEach(dropdown => {
    const menu = dropdown.querySelector('.dropdown-menu');
    if (menu) {
        dropdown.addEventListener('mouseenter', () => {
            menu.style.display = 'block';
            menu.style.animation = 'slideDown 0.3s ease';
        });
        
        dropdown.addEventListener('mouseleave', () => {
            menu.style.display = 'none';
        });
    }
}); 