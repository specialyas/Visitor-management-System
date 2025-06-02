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
    
    // Handle redirection after successful registration
    if (window.registerConfig && window.registerConfig.redirect) {
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 3000); // Delay for 3 seconds to show success message
    }
});

// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.register-form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    // // Validation functions
    // function validateEmail(email) {
    //     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //     return emailRegex.test(email);
    // }
    
    // function validateUsername(username) {
    //     return username.length >= 3 && username.length <= 20 && /^[a-zA-Z0-9_]+$/.test(username);
    // }
    
    // function calculatePasswordStrength(password) {
    //     let strength = 0;
    //     if (password.length >= 8) strength++;
    //     if (/[a-z]/.test(password)) strength++;
    //     if (/[A-Z]/.test(password)) strength++;
    //     if (/[0-9]/.test(password)) strength++;
    //     if (/[^A-Za-z0-9]/.test(password)) strength++;
    //     return strength;
    // }
    
    function showFieldError(field, message) {
        clearFieldFeedback(field);
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function showFieldSuccess(field, message) {
        clearFieldFeedback(field);
        field.classList.add('is-valid');
        
        if (message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.textContent = message;
            field.parentNode.appendChild(successDiv);
        }
    }
    
    function clearFieldFeedback(field) {
        field.classList.remove('is-invalid', 'is-valid');
        const feedback = field.parentNode.querySelector('.error-message, .success-message');
        if (feedback) {
            feedback.remove();
        }
    }
    
    // Password strength indicator
    // function createPasswordStrengthIndicator() {
    //     const strengthContainer = document.createElement('div');
    //     strengthContainer.className = 'password-strength';
    //     const strengthBar = document.createElement('div');
    //     strengthBar.className = 'password-strength-bar';
    //     strengthContainer.appendChild(strengthBar);
    //     passwordInput.parentNode.appendChild(strengthContainer);
    //     return strengthBar;
    // }
    
    const passwordStrengthBar = createPasswordStrengthIndicator();
    
    function updatePasswordStrength(password) {
        const strength = calculatePasswordStrength(password);
        const percentage = (strength / 5) * 100;
        
        passwordStrengthBar.style.width = percentage + '%';
        passwordStrengthBar.className = 'password-strength-bar';
        
        if (strength <= 1) {
            passwordStrengthBar.classList.add('password-strength-weak');
        } else if (strength <= 2) {
            passwordStrengthBar.classList.add('password-strength-fair');
        } else if (strength <= 3) {
            passwordStrengthBar.classList.add('password-strength-good');
        } else {
            passwordStrengthBar.classList.add('password-strength-strong');
        }
    }
    
    // Real-time validation
    usernameInput.addEventListener('blur', function() {
        if (this.value) {
            if (!validateUsername(this.value)) {
                showFieldError(this, 'Username must be 3-20 characters long and contain only letters, numbers, and underscores');
            } else {
                showFieldSuccess(this);
            }
        }
    });
    
    emailInput.addEventListener('blur', function() {
        if (this.value) {
            if (!validateEmail(this.value)) {
                showFieldError(this, 'Please enter a valid email address');
            } else {
                showFieldSuccess(this);
            }
        }
    });
    
    passwordInput.addEventListener('input', function() {
        updatePasswordStrength(this.value);
        
        if (this.value) {
            if (this.value.length < 8) {
                showFieldError(this, 'Password must be at least 8 characters long');
            } else {
                const strength = calculatePasswordStrength(this.value);
                if (strength < 3) {
                    showFieldError(this, 'Password is too weak. Include uppercase, lowercase, numbers, and special characters');
                } else {
                    showFieldSuccess(this, 'Password strength is good');
                }
            }
        }
    });
    
    // Clear validation on input
    [usernameInput, emailInput, passwordInput].forEach(function(input) {
        input.addEventListener('input', function() {
            if (this !== passwordInput && this.classList.contains('is-invalid')) {
                clearFieldFeedback(this);
            }
        });
    });
    
    // Form submission validation
    registerForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate username
        if (!usernameInput.value) {
            showFieldError(usernameInput, 'Username is required');
            isValid = false;
        } else if (!validateUsername(usernameInput.value)) {
            showFieldError(usernameInput, 'Username must be 3-20 characters long and contain only letters, numbers, and underscores');
            isValid = false;
        }
        
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
        } else if (passwordInput.value.length < 8) {
            showFieldError(passwordInput, 'Password must be at least 8 characters long');
            isValid = false;
        } else if (calculatePasswordStrength(passwordInput.value) < 3) {
            showFieldError(passwordInput, 'Password is too weak');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});

// Loading state for form submission
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.register-form');
    const submitButton = document.querySelector('.register-btn');
    
    registerForm.addEventListener('submit', function() {
        // Show loading state
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Creating Account...';
        submitButton.disabled = true;
        
        // Re-enable button after 5 seconds (fallback)
        setTimeout(function() {
            submitButton.innerHTML = 'Create Account';
            submitButton.disabled = false;
        }, 5000);
    });
});

// Prevent double submission
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.register-form');
    let isSubmitting = false;
    
    registerForm.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        isSubmitting = true;
        
        // Reset after 5 seconds
        setTimeout(() => {
            isSubmitting = false;
        }, 5000);
    });
});