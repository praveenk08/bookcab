/**
 * OTP Verification System for Car Booking Platform
 * This is a dummy implementation for demonstration purposes
 */

// Store for verification codes
const verificationStore = {};

/**
 * Generate a random OTP code
 * @param {number} length - Length of the OTP code
 * @returns {string} - Generated OTP code
 */
function generateOTP(length = 6) {
    const digits = '0123456789';
    let otp = '';
    
    for (let i = 0; i < length; i++) {
        otp += digits[Math.floor(Math.random() * 10)];
    }
    
    return otp;
}

/**
 * Send verification email (dummy implementation)
 * @param {string} email - Recipient email
 * @param {string} subject - Email subject
 * @param {string} message - Email message
 * @returns {Promise} - Promise resolving to success message
 */
function sendVerificationEmail(email, subject, message) {
    return new Promise((resolve) => {
        // Simulate network delay
        setTimeout(() => {
            console.log(`Email sent to ${email}`);
            console.log(`Subject: ${subject}`);
            console.log(`Message: ${message}`);
            
            resolve({ success: true, message: 'Email sent successfully' });
        }, 1000);
    });
}

/**
 * Send OTP to user email
 * @param {string} email - User email
 * @param {string} action - Action being verified (booking, cancellation, etc.)
 * @returns {Promise} - Promise resolving to success message
 */
async function sendOTP(email, action) {
    // Generate OTP
    const otp = generateOTP();
    
    // Store OTP with timestamp
    verificationStore[email] = {
        otp,
        timestamp: Date.now(),
        action
    };
    
    // Prepare email content
    const subject = `Your verification code for ${action}`;
    const message = `
        <h2>Verification Code</h2>
        <p>Your verification code for ${action} is: <strong>${otp}</strong></p>
        <p>This code will expire in 10 minutes.</p>
        <p>If you did not request this code, please ignore this email.</p>
    `;
    
    // Send email
    return await sendVerificationEmail(email, subject, message);
}

/**
 * Verify OTP entered by user
 * @param {string} email - User email
 * @param {string} enteredOTP - OTP entered by user
 * @returns {boolean} - Whether verification was successful
 */
function verifyOTP(email, enteredOTP) {
    // Check if there's a stored OTP for this email
    if (!verificationStore[email]) {
        return false;
    }
    
    const { otp, timestamp } = verificationStore[email];
    const currentTime = Date.now();
    const expiryTime = 10 * 60 * 1000; // 10 minutes in milliseconds
    
    // Check if OTP has expired
    if (currentTime - timestamp > expiryTime) {
        delete verificationStore[email];
        return false;
    }
    
    // Check if OTP matches
    if (enteredOTP === otp) {
        // OTP verified, remove from store
        delete verificationStore[email];
        return true;
    }
    
    return false;
}

// DOM event handlers for OTP verification
document.addEventListener('DOMContentLoaded', function() {
    // Handle OTP input fields
    const otpInputs = document.querySelectorAll('.otp-input');
    
    if (otpInputs.length > 0) {
        otpInputs.forEach((input, index) => {
            // Auto-focus next input when a digit is entered
            input.addEventListener('input', function() {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            
            // Handle backspace to go to previous input
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });
    }
    
    // Handle send verification button
    const sendVerificationBtn = document.getElementById('send-verification');
    if (sendVerificationBtn) {
        sendVerificationBtn.addEventListener('click', async function() {
            const email = document.getElementById('verification-email').value;
            const action = this.dataset.action || 'booking confirmation';
            
            if (!email) {
                alert('Please enter an email address');
                return;
            }
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            
            try {
                // In a real implementation, this would call the server
                // For demo, we'll use our client-side function
                await sendOTP(email, action);
                
                // Show success message
                alert(`Verification code sent to ${email}`);
                
                // Show OTP input fields
                document.getElementById('otp-verification-container').classList.remove('d-none');
                
                // Start countdown timer
                startCountdown();
                
            } catch (error) {
                alert('Failed to send verification code. Please try again.');
            } finally {
                // Reset button state
                this.disabled = false;
                this.innerHTML = 'Send Verification Code';
            }
        });
    }
    
    // Handle verify OTP button
    const verifyOTPBtn = document.getElementById('verify-otp');
    if (verifyOTPBtn) {
        verifyOTPBtn.addEventListener('click', function() {
            const email = document.getElementById('verification-email').value;
            
            // Collect OTP from input fields
            let enteredOTP = '';
            otpInputs.forEach(input => {
                enteredOTP += input.value;
            });
            
            if (enteredOTP.length !== otpInputs.length) {
                alert('Please enter the complete verification code');
                return;
            }
            
            // For demo purposes, we'll use our client-side function
            // In a real implementation, this would call the server
            const isVerified = verifyOTP(email, enteredOTP);
            
            if (isVerified) {
                alert('Verification successful!');
                
                // Enable the form submission
                const submitBtn = document.querySelector('.verification-required');
                if (submitBtn) {
                    submitBtn.classList.remove('verification-required');
                    submitBtn.disabled = false;
                }
                
                // Close the modal if it exists
                const modal = bootstrap.Modal.getInstance(document.getElementById('otpVerificationModal'));
                if (modal) {
                    modal.hide();
                }
            } else {
                alert('Invalid or expired verification code. Please try again.');
                
                // Clear OTP inputs
                otpInputs.forEach(input => {
                    input.value = '';
                });
                otpInputs[0].focus();
            }
        });
    }
    
    // Handle resend OTP button
    const resendOTPBtn = document.getElementById('resend-otp');
    if (resendOTPBtn) {
        resendOTPBtn.addEventListener('click', async function() {
            const email = document.getElementById('verification-email').value;
            const action = this.dataset.action || 'booking confirmation';
            
            if (!email) {
                alert('Please enter an email address');
                return;
            }
            
            // Show loading state
            this.disabled = true;
            
            try {
                await sendOTP(email, action);
                alert(`New verification code sent to ${email}`);
                
                // Reset countdown timer
                startCountdown();
                
                // Clear OTP inputs
                otpInputs.forEach(input => {
                    input.value = '';
                });
                otpInputs[0].focus();
                
            } catch (error) {
                alert('Failed to send verification code. Please try again.');
            } finally {
                // Reset button state after 60 seconds
                setTimeout(() => {
                    this.disabled = false;
                }, 60000);
            }
        });
    }
});

/**
 * Start countdown timer for OTP expiration
 */
function startCountdown() {
    const countdownElement = document.getElementById('otp-countdown');
    if (!countdownElement) return;
    
    let timeLeft = 10 * 60; // 10 minutes in seconds
    
    // Clear any existing interval
    if (window.countdownInterval) {
        clearInterval(window.countdownInterval);
    }
    
    // Update countdown every second
    window.countdownInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        
        if (timeLeft <= 0) {
            clearInterval(window.countdownInterval);
            countdownElement.textContent = 'Expired';
            
            // Enable resend button
            const resendOTPBtn = document.getElementById('resend-otp');
            if (resendOTPBtn) {
                resendOTPBtn.disabled = false;
            }
        }
        
        timeLeft -= 1;
    }, 1000);
}