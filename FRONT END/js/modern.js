/**
 * Modern JS for E-learning Platform
 * Enhanced UI interactions and animations
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize components
  initSidebar();
  initThemeToggleNew();
  initAnimations();
  initProgressBars();
  initCourseCards();
  initAchievementBadges();
  initMobileMenu();
  initFormControls();
  initFilterControls();
  
  // Initialize page-specific components
  if (document.querySelector('.progress-tracker')) {
    initProgressTracker();
  }
  
  if (document.querySelector('.auth-section')) {
    initAuthForms();
  }
});

/**
 * Initialize sidebar functionality
 */
function initSidebar() {
  const sidebarToggle = document.querySelector('#menu-btn');
  const sidebar = document.querySelector('.side-bar');
  const closeBtn = document.querySelector('.close-side-bar');
  const body = document.body;
  
  if (!sidebarToggle || !sidebar) return;
  
  sidebarToggle.addEventListener('click', function() {
    sidebar.classList.toggle('active');
    body.classList.toggle('active');
  });
  
  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      sidebar.classList.remove('active');
      body.classList.remove('active');
    });
  }
  
  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function(e) {
    if (window.innerWidth < 991 && 
        !sidebar.contains(e.target) && 
        e.target !== sidebarToggle && 
        sidebar.classList.contains('active')) {
      sidebar.classList.remove('active');
      body.classList.remove('active');
    }
  });
}

/**
 * Initialize scroll animations for elements with animation classes
 */
function initAnimations() {
  // Elements with built-in animation classes
  const animatedElements = document.querySelectorAll('[class*="animate-"]');
  
  if (animatedElements.length === 0) return;
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  
  animatedElements.forEach(el => {
    observer.observe(el);
  });
}

/**
 * Initialize progress bars with animation
 */
function initProgressBars() {
  const progressBars = document.querySelectorAll('.progress-bar');
  
  if (progressBars.length === 0) return;
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const progressElement = entry.target.querySelector('.progress');
        if (progressElement) {
          const width = progressElement.getAttribute('data-width') || '0';
          progressElement.style.width = width + '%';
        }
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  
  progressBars.forEach(bar => {
    observer.observe(bar);
  });
}

/**
 * Initialize course cards with hover effects
 */
function initCourseCards() {
  const courseCards = document.querySelectorAll('.course-card');
  
  courseCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      const img = this.querySelector('.course-card-image img');
      if (img) {
        img.style.transform = 'scale(1.05)';
      }
    });
    
    card.addEventListener('mouseleave', function() {
      const img = this.querySelector('.course-card-image img');
      if (img) {
        img.style.transform = 'scale(1)';
      }
    });
  });
}

/**
 * Initialize achievement badges with effects
 */
function initAchievementBadges() {
  const badges = document.querySelectorAll('.achievement-badge:not(.locked)');
  
  badges.forEach(badge => {
    badge.addEventListener('click', function() {
      this.classList.add('pulse');
      
      // Display achievement details in a modal or tooltip
      const title = this.querySelector('h3')?.textContent || this.querySelector('h4')?.textContent;
      const description = this.querySelector('p')?.textContent;
      
      if (title && description) {
        showAchievementDetails(title, description);
      }
      
      setTimeout(() => {
        this.classList.remove('pulse');
      }, 2000);
    });
  });
}

/**
 * Show achievement details in a modal
 */
function showAchievementDetails(title, description) {
  // Create modal if it doesn't exist
  let modal = document.querySelector('#achievement-modal');
  
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'achievement-modal';
    modal.className = 'modal fade-in';
    modal.innerHTML = `
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div class="modal-header">
          <h3>${title}</h3>
        </div>
        <div class="modal-body">
          <p>${description}</p>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    
    const closeBtn = modal.querySelector('.modal-close');
    closeBtn.addEventListener('click', () => {
      modal.style.display = 'none';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  } else {
    // Update modal content
    modal.querySelector('.modal-header h3').textContent = title;
    modal.querySelector('.modal-body p').textContent = description;
  }
  
  // Display modal
  modal.style.display = 'block';
}

/**
 * Initialize mobile menu
 */
function initMobileMenu() {
  const mobileMenuBtn = document.querySelector('#menu-btn');
  
  if (!mobileMenuBtn) return;
  
  mobileMenuBtn.addEventListener('click', function() {
    this.classList.toggle('fa-bars');
    this.classList.toggle('fa-times');
  });
}

/**
 * Initialize form controls
 */
function initFormControls() {
  // Custom file input
  const fileInputs = document.querySelectorAll('.custom-file-input');
  
  fileInputs.forEach(input => {
    input.addEventListener('change', function(e) {
      const fileName = e.target.files[0]?.name || 'Choose file';
      const label = this.nextElementSibling;
      
      if (label && label.classList.contains('custom-file-label')) {
        label.textContent = fileName;
      }
    });
  });
  
  // Password visibility toggle
  const passwordToggles = document.querySelectorAll('.password-toggle');
  
  passwordToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const passwordInput = this.previousElementSibling;
      
      if (passwordInput && passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash');
      } else if (passwordInput) {
        passwordInput.type = 'password';
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye');
      }
    });
  });
}

/**
 * Initialize filter controls for courses page
 */
function initFilterControls() {
  const filters = document.querySelectorAll('.filter-group select');
  const viewButtons = document.querySelectorAll('.view-controls .btn');
  
  // Filters change event
  filters.forEach(filter => {
    filter.addEventListener('change', function() {
      // Apply filtering (to be implemented based on backend)
      console.log('Filter changed:', this.value);
    });
  });
  
  // View toggle buttons
  if (viewButtons.length > 0) {
    viewButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        viewButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Toggle grid/list view
        const isList = this.querySelector('.fa-list');
        const coursesContainer = document.querySelector('.courses-grid .row');
        
        if (coursesContainer) {
          if (isList) {
            coursesContainer.classList.add('list-view');
          } else {
            coursesContainer.classList.remove('list-view');
          }
        }
      });
    });
  }
}

/**
 * Initialize authentication forms
 */
function initAuthForms() {
  // Form validation
  const authForms = document.querySelectorAll('.login-form, .register-form');
  
  authForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      let isValid = true;
      const requiredInputs = this.querySelectorAll('[required]');
      
      requiredInputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          showInputError(input, 'This field is required');
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
          isValid = false;
          showInputError(input, 'Please enter a valid email address');
        } else if (input.name === 'cpass') {
          const password = this.querySelector('[name="pass"]');
          if (password && input.value !== password.value) {
            isValid = false;
            showInputError(input, 'Passwords do not match');
          } else {
            clearInputError(input);
          }
        } else {
          clearInputError(input);
        }
      });
      
      if (!isValid) {
        e.preventDefault();
      }
    });
  });
}

/**
 * Show error message for input
 */
function showInputError(input, message) {
  clearInputError(input);
  
  const formGroup = input.closest('.form-group');
  if (!formGroup) return;
  
  const error = document.createElement('div');
  error.className = 'invalid-feedback';
  error.textContent = message;
  
  input.classList.add('is-invalid');
  formGroup.appendChild(error);
}

/**
 * Clear error message for input
 */
function clearInputError(input) {
  const formGroup = input.closest('.form-group');
  if (!formGroup) return;
  
  const error = formGroup.querySelector('.invalid-feedback');
  if (error) {
    error.remove();
  }
  
  input.classList.remove('is-invalid');
}

/**
 * Validate email format
 */
function isValidEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

/**
 * Initialize Progress Tracker functionality
 */
function initProgressTracker() {
  // This functionality is handled by the ProgressTracker class in progress.js
  console.log('Progress Tracker initialized');
}

/**
 * Format date string
 */
function formatDate(dateString) {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  
  return date.toLocaleDateString('en-US', options);
}

/**
 * Format learning time from minutes
 */
function formatLearningTime(minutes) {
  if (!minutes) return '0 min';
  
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  
  if (hours === 0) {
    return `${mins} min`;
  } else if (mins === 0) {
    return `${hours} hr`;
  } else {
    return `${hours} hr ${mins} min`;
  }
}

function initThemeToggleNew() {
  const themeToggle = document.querySelector('.theme-toggle-new');
  if (!themeToggle) return;

  // Set initial theme from localStorage
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('dark');
    setThemeIcon('moon');
  } else {
    document.body.classList.remove('dark');
    setThemeIcon('sun');
  }

  themeToggle.addEventListener('click', function() {
    const isDark = document.body.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    setThemeIcon(isDark ? 'moon' : 'sun');
  });

  function setThemeIcon(type) {
    if (type === 'moon') {
      themeToggle.innerHTML = `<svg viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 0112.21 3a1 1 0 00-1.13 1.32A7 7 0 1019.68 13.92a1 1 0 001.32-1.13z"/></svg>`;
    } else {
      themeToggle.innerHTML = `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><g><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></g></svg>`;
    }
  }
} 