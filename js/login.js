// Toast notification handler
document.addEventListener('DOMContentLoaded', function() {
    // Initialize and show toast notifications
    const toastElements = document.querySelectorAll('.toast');
    
    toastElements.forEach(function(toastElement) {
        const toast = new bootstrap.Toast(toastElement, {
            delay: 3000,
            autohide: true
        });
        toast.show();
    });
});

// Optional: Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    // Add real-time validation feedback
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showFieldError(field, message) {
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Add error styling
        field.classList.add('is-invalid');
        
        // Create and append error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-danger mt-1';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorMessage = field.parentNode.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }
    
    // Email validation on blur
    emailInput.addEventListener('blur', function() {
        if (this.value && !validateEmail(this.value)) {
            showFieldError(this, 'Please enter a valid email address');
        } else {
            clearFieldError(this);
        }
    });
    
    // Password validation on blur
    // passwordInput.addEventListener('blur', function() {
    //     if (this.value && this.value.length < 6) {
    //         showFieldError(this, 'Password must be at least 6 characters long');
    //     } else {
    //         clearFieldError(this);
    //     }
    // });
    
    // Clear errors on input
    [emailInput, passwordInput].forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                clearFieldError(this);
            }
        });
    });
    
    // Form submission validation
    loginForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate email
        if (!emailInput.value) {
            showFieldError(emailInput, 'Email is required');
            isValid = false;
        } else if (!validateEmail(emailInput.value)) {
            showFieldError(emailInput, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate password
        if (!passwordInput.value) {
            showFieldError(passwordInput, 'Password is required');
            isValid = false;
        }  //else if (passwordInput.value.length < 6) {
        //     showFieldError(passwordInput, 'Password must be at least 6 characters long');
        //     isValid = false;
        // }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});

// Optional: Loading state for form submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const submitButton = document.querySelector('.login-btn');
    
    loginForm.addEventListener('submit', function() {
        // Show loading state
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Logging in...';
        submitButton.disabled = true;
        
        // Re-enable button after 5 seconds (fallback)
        setTimeout(function() {
            submitButton.innerHTML = 'Login';
            submitButton.disabled = false;
        }, 5000);
    });
});