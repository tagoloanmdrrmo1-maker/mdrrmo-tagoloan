<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>MDDRMO Rainfall Monitoring System - Login</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
  
  <style>
    body {
      background: #ffffff;
      min-height: 100vh;
    }
    
    .glass-effect {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .status-indicator {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #10b981;
      position: relative;
    }
    
    .status-indicator::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      border-radius: 50%;
      background: #10b981;
      opacity: 0.3;
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 0.3;
      }
      70% {
        transform: scale(1.3);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 0;
      }
    }
    
    .floating-animation {
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    .gradient-border {
      position: relative;
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      padding: 2px;
      border-radius: 12px;
    }

    .gradient-border::before {
      content: '';
      position: absolute;
      inset: 0;
      padding: 2px;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      border-radius: 12px;
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask-composite: xor;
      -webkit-mask-composite: xor;
    }

    .login-card-content {
      background: white;
      border-radius: 10px;
      position: relative;
      z-index: 1;
    }

    /* Proactive validation styles */
    .success-message {
        color: #059669;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .success-message::before {
        content: "";
        font-size: 0.875rem;
    }

    .form-field-valid {
        border-color: #10b981 !important;
        background-color: #f0fdf4 !important;
    }

    .form-field-invalid {
        border-color: #dc2626 !important;
        background-color: #fef2f2 !important;
    }
  </style>
</head>

<body class="min-h-screen flex">




  <!-- Main Container -->
  <div class="flex w-full min-h-screen">
    <!-- Left Side - Logo and Info -->
    <div class="flex-1 flex flex-col justify-center items-center p-8 lg:p-16 text-gray-800 relative overflow-hidden bg-gray-50">
    
      <div class="relative z-10 text-center max-w-md floating-animation">
        <!-- Logo -->
        <div class="mb-8">
          <img src="{{ asset('images/logo.png') }}" alt="Municipality Logo" class="w-32 h-32 mx-auto object-contain mb-6 drop-shadow-lg" />
        </div>
        
        <!-- Title -->
        <h1 class="text-3xl lg:text-4xl font-bold mb-2 text-gray-900">
          MUNICIPALITY OF TAGOLOAN <small style="color: indigo"> <br> Version 5.2.17</small>
        </h1>
        <p class="text-lg text-gray-600 mb-1">Province of Misamis Oriental</p>
        <div class="w-16 h-0.5 bg-gray-400 mx-auto my-4"></div>
        
        <!-- Department -->
        <h2 class="text-xl lg:text-2xl font-semibold text-blue-600 mb-2">
          MUNICIPAL DISASTER RISK REDUCTION
        </h2>
        <h2 class="text-xl lg:text-2xl font-semibold text-red-500 mb-4">
          AND MANAGEMENT OFFICE
        </h2>
        
        <!-- System Info -->
        <div class="flex items-center justify-center mb-4">
          <i class="fas fa-cloud-rain text-blue-500 mr-2"></i>
          <span class="text-blue-600 font-medium">Automated Rain Gauge Monitoring System</span>
        </div>
        
        <!-- Mission Statement -->
        <p class="text-sm text-gray-600 italic">
          PREPAREDNESS • RESPONSE • RECOVERY
        </p>
        
        <!-- Status Indicators -->
        <div class="flex justify-center space-x-2 mt-6">
          <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
          <div class="w-3 h-3 bg-red-500 rounded-full"></div>
          <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
        </div>
      </div>
    </div>
    
    <!-- Right Side - Login Form -->
    <div class="flex-1 flex justify-center items-center p-8 lg:p-16 bg-white">
      <div class="w-full max-w-sm">
        <!-- Status Header -->
        <div class="text-center mb-8">
          <div class="flex items-center justify-center mb-2">
          </div>
        </div>
        
        <!-- Login Card -->
        <div class="bg-white-200 border border-gray-200 rounded-3xl p-8 shadow-2xl min-h-[500px]">

          <div class="text-center mb-8">
                  <div class="w-16 h-16 bg-gray-100 border border-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-2xl text-gray-600"></i>
                  </div>
          </div>
          
          <form method="POST" action="{{ route('login') }}" novalidate>

            @csrf
            
            @if (session('status'))
              <div class="mb-6 px-4 py-3 rounded-lg border border-green-300 bg-green-50 text-green-700 text-sm">
                {{ session('status') }}
              </div>
            @endif

            <!-- Username Field -->
            <div class="mb-6">
              <label for="username" class="block text-gray-700 font-semibold mb-2 text-sm">
                <i class="fas fa-user text-blue-500 mr-2"></i>
                Username
              </label>
              <input id="username"
                     name="username"
                     type="text"
                     value="{{ old('username') }}"
                     placeholder="Enter your username"
                     autofocus
                     class="w-full rounded-xl px-4 py-3 text-gray-700 bg-white border-2 border-gray-200 outline-none focus:outline-none focus:ring-0 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 hover:border-gray-300" />
              <p id="username_error" class="text-red-500 text-sm mt-2 hidden"></p>
              <div id="username_success" class="success-message mt-2 hidden"></div>
              @error('username') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-6">
              <label for="password" class="block text-gray-700 font-semibold mb-2 text-sm">
                <i class="fas fa-lock text-blue-500 mr-2"></i>
                Password
              </label>
              <div class="relative">
                <input id="password"
                       name="password"
                       type="password"
                       placeholder="Enter your password"
                       class="w-full rounded-xl px-4 py-3 text-gray-700 bg-white border-2 border-gray-200 outline-none focus:outline-none focus:ring-0 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 hover:border-gray-300" />
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <p id="password_error" class="text-red-500 text-sm mt-2 hidden"></p>
              <div id="password_success" class="success-message mt-2 hidden"></div>
              @error('password') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            @if(session()->has('login_failed'))
              <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200">
                <p class="text-red-600 text-sm">
                  <i class="fas fa-exclamation-triangle mr-2"></i>
                  Incorrect Login Credentials.
                </p>
              </div>
            @endif

            <!-- Forgot Password Link -->
            <div class="text-right mb-6">
              <a href="#" 
                 onclick="openForgotPasswordModal(); return false;"
                 class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                Forgot Password?
              </a>
            </div>

            <!-- Login Button -->
            <button type="submit" 
                    class="w-full text-white font-semibold py-3 rounded-lg mb-6 flex justify-center items-center gap-2 shadow-lg transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl focus:outline-none" style="background-color: #242F41; hover:background-color: #1a2332;">
              <i class="fas fa-sign-in-alt"></i>
              <span>login</span>
            </button>

          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Forgot Password Modal -->
  <div id="forgotPasswordModal" class="fixed inset-0 z-[9999999] hidden overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

      <!-- Modal container -->
      <div class="inline-block align-bottom bg-white border border-gray-300 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-8 pt-8 pb-8">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
              <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                <h3 class="text-3xl font-bold text-gray-800 mb-2">Forgot Password</h3>
                <button onclick="closeForgotPasswordModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                  <i class="fas fa-times text-xl"></i>
                </button>
              </div>
              <div class="mt-6">
                <p class="text-gray-600 text-sm mb-6">
                  Enter your email address and we'll send you a password reset link.
                </p>
                <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}" novalidate>
                  @csrf
                  <div class="mb-6">
                    <label for="fp_email" class="block text-gray-700 font-semibold mb-2 text-sm">
                      <i class="fas fa-envelope text-blue-500 mr-2"></i>
                      Email
                    </label>
                    <input id="fp_email" 
                           name="email" 
                           type="email" 
                           placeholder="Enter your email address" 
                           class="w-full rounded-xl px-4 py-3 text-gray-700 bg-white border-2 border-gray-200 outline-none focus:outline-none focus:ring-0 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 hover:border-gray-300" />
                    <p id="fp_email_error" class="text-red-500 text-sm mt-2 hidden"></p>
                    <div id="fp_success_message" class="mt-2 hidden px-4 py-3 rounded-lg border border-green-300 bg-green-50 text-green-700 text-sm"></div>
                  </div>
                  <!-- Send Reset Link Button (Full Width) -->
                  <button type="submit" 
                          class="w-full text-white font-semibold py-3 rounded-lg mb-6 flex justify-center items-center gap-2 shadow-lg transition-all duration-300 focus:outline-none" style="background-color: #242F41;">
                    <i class="fas fa-envelope"></i>
                    <span>Send Reset Link</span>
                  </button>
                </form>
                <!-- Back to Login Link -->
                <div class="text-center">
                  <a href="#" 
                     onclick="closeForgotPasswordModal(); return false;"
                     class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                    Back to Login
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
   
    const loginForm = document.querySelector('form[action="{{ route("login") }}"]');
    const loginFields = {
      'username': 'Username is required',
      'password': 'Password is required'
    };

    
    function showFieldError(fieldId, message) {
      const field = document.getElementById(fieldId);
      const errorElement = document.getElementById(`${fieldId}_error`);

      if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
      }
      if (field) {
        field.classList.add('form-field-invalid');
        field.classList.remove('form-field-valid');
      }
    }


    function clearFieldError(fieldId) {
      const field = document.getElementById(fieldId);
      const errorElement = document.getElementById(`${fieldId}_error`);

      if (errorElement) {
        errorElement.classList.add('hidden');
      }
      if (field) {
        field.classList.remove('form-field-invalid', 'form-field-valid');
      }
    }

    // Function to validate field in real-time
    function validateFieldRealTime(fieldId) {
      const field = document.getElementById(fieldId);
      if (!field) return;

      const value = field.value.trim();
      const isEmpty = value === '';

      // Clear previous styling
      field.classList.remove('form-field-valid', 'form-field-invalid');
      clearFieldSuccess(fieldId);

      // Don't validate empty fields - only show success when there's actual valid content
      if (isEmpty) {
        clearFieldError(fieldId);
        return false;
      }

      if (fieldId === 'username') {
        if (!usernameRegex.test(value)) {
          field.classList.add('form-field-invalid');
          showFieldError(fieldId);
          return false;
        } else {
          // Valid username - only show success if there's actual content
          field.classList.add('form-field-valid');
          clearFieldError(fieldId);
          showFieldSuccess(fieldId);
          return true;
        }
      }

      if (fieldId === 'password') {
        // Updated to match user preference: only require letters and numbers, no special characters
        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;
        if (!passwordRegex.test(value)) {
          field.classList.add('form-field-invalid');
          showFieldError(fieldId, 'Password must be at least 8 characters.');
          return false;
        } else {
          // Valid password - only show success if there's actual content
          field.classList.add('form-field-valid');
          clearFieldError(fieldId);
          showFieldSuccess(fieldId);
          return true;
        }
      }

      return true;
    }

    // Function to validate field (for blur events)
    function validateField(fieldId) {
      const field = document.getElementById(fieldId);
      if (!field) return true;

      const value = field.value.trim();
      const isEmpty = value === '';

      if (isEmpty) {
        showFieldError(fieldId, loginFields[fieldId] || `${fieldId.replace('_', ' ')} is required`);
        return false;
    }


    if (fieldId === 'username') {
      const usernameRegex = /^[A-Za-z0-9]+$/;
      if (!usernameRegex.test(value)) {
        showFieldError(fieldId, 'Username may only contain letters and numbers.');
        return false;
      }
    }

    if (fieldId === 'password') {
      // Updated to match user preference: require letters, numbers, and allow special characters (but no spaces)
      const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d\S]{8,}$/;
      if (!passwordRegex.test(value)) {
        field.classList.add('form-field-invalid');
        showFieldError(fieldId, 'Password must be at least 8 characters and no spaces');
        return false;
      } else {
        // Valid password - only show success if there's actual content
        field.classList.add('form-field-valid');
        clearFieldError(fieldId);
        showFieldSuccess(fieldId);
        return true;
      }
    }

    clearFieldError(fieldId);
    return true;
    }

    // Function to show field success (green styling and message)
    function showFieldSuccess(fieldId) {
      const field = document.getElementById(fieldId);
      const errorElement = document.getElementById(`${fieldId}_error`);
      const successElement = document.getElementById(`${fieldId}_success`);

      if (errorElement) {
        errorElement.classList.add('hidden');
      }
      if (successElement) {
        successElement.classList.remove('hidden');
        successElement.textContent = ' ';
      }
      if (field) {
        field.classList.remove('form-field-invalid');
        field.classList.add('form-field-valid');
      }
    }

    // Function to clear field success (reset to normal)
    function clearFieldSuccess(fieldId) {
      const field = document.getElementById(fieldId);
      const successElement = document.getElementById(`${fieldId}_success`);

      if (successElement) {
        successElement.classList.add('hidden');
      }
      if (field) {
        field.classList.remove('form-field-valid', 'form-field-invalid');
      }
    }

        Object.keys(loginFields).forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (field) {
            // Real-time validation on input
            field.addEventListener('input', () => {
              validateFieldRealTime(fieldId);
            });

            // Also validate on blur for final check
            field.addEventListener('blur', () => {
              validateField(fieldId);
            });

            // Prevent browser's default validation
            field.addEventListener('invalid', (e) => {
              e.preventDefault();
            });
          }
        });

    // Login form submission validation
    if (loginForm) {
      loginForm.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear any previous errors first
        clearFieldError('username');
        clearFieldError('password');

        // Validate username
        if (!validateField('username')) {
          isValid = false;
        }

        // Validate password
        if (!validateField('password')) {
          isValid = false;
        }

        if (!isValid) {
          e.preventDefault();
          // Focus on first error field
          const firstErrorField = document.querySelector('.border-red-500');
          if (firstErrorField) {
            firstErrorField.focus();
          }
        }
      });
    }

    // Password toggle functionality
  
    // Forgot Password Modal Functions
    function openForgotPasswordModal() {
      document.getElementById('forgotPasswordModal').classList.remove('hidden');
      // Clear any previous error/success messages
      document.getElementById('fp_email_error').classList.add('hidden');
      document.getElementById('fp_success_message').classList.add('hidden');
      document.getElementById('fp_email').value = '';
    }

    function closeForgotPasswordModal() {
      document.getElementById('forgotPasswordModal').classList.add('hidden');
    }

    // Handle forgot password form submission
    document.addEventListener('DOMContentLoaded', function() {
      const forgotPasswordForm = document.getElementById('forgotPasswordForm');
      if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const email = document.getElementById('fp_email').value.trim();
          const emailError = document.getElementById('fp_email_error');
          const successMessage = document.getElementById('fp_success_message');
          
          // Reset messages
          emailError.classList.add('hidden');
          successMessage.classList.add('hidden');
          
          // Validate email
          if (!email) {
            emailError.textContent = 'Email is required';
            emailError.classList.remove('hidden');
            return;
          }
          
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address';
            emailError.classList.remove('hidden');
            return;
          }
          
          // Submit form via AJAX
          const formData = new FormData(this);
          const submitButton = this.querySelector('button[type="submit"]');
          const originalButtonText = submitButton.innerHTML;
          
          // Show loading state
          submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
          submitButton.disabled = true;
          
          fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              successMessage.textContent = data.message || 'Password reset link sent successfully!';
              successMessage.classList.remove('hidden');
              document.getElementById('fp_email').value = '';
            } else {
              emailError.textContent = data.message || 'An error occurred. Please try again.';
              emailError.classList.remove('hidden');
            }
          })
          .catch(error => {
            emailError.textContent = 'An error occurred. Please try again.';
            emailError.classList.remove('hidden');
          })
          .finally(() => {
            // Restore button state
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
          });
        });
      }

      // Close modal when clicking outside of it
      const forgotPasswordModal = document.getElementById('forgotPasswordModal');
      if (forgotPasswordModal) {
        forgotPasswordModal.addEventListener('click', function(e) {
          if (e.target === this) {
            closeForgotPasswordModal();
          }
        });
      }

      // Close modal with Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          const modal = document.getElementById('forgotPasswordModal');
          if (modal && !modal.classList.contains('hidden')) {
            closeForgotPasswordModal();
          }
        }
      });

      // Toggle password visibility
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      
      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
      }
    });
  </script>

</body>
</html>
