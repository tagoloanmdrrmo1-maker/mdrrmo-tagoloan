{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'MDDRMO Rainfall Monitoring')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>

    // Global Form Validation Utility
    window.FormValidator = {
      // Show validation error banner
      showErrorBanner: function(containerId, message = 'Please fill in all required fields before submitting') {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Remove existing banner
        const existingBanner = container.querySelector('.validation-error-banner');
        if (existingBanner) {
          existingBanner.remove();
        }
        
        // Create new banner
        const banner = document.createElement('div');
        banner.className = 'validation-error-banner';
        banner.textContent = message;
        
        // Insert at the beginning of the container
        container.insertBefore(banner, container.firstChild);
      },
      
      // Hide validation error banner
      hideErrorBanner: function(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const banner = container.querySelector('.validation-error-banner');
        if (banner) {
          banner.remove();
        }
      },
      
      // Show field error
      showFieldError: function(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(fieldId + '_error');
        
        if (field && errorElement) {
          field.classList.add('form-field-error');
          errorElement.textContent = message;
          errorElement.style.display = 'block';
        }
      },
      
      // Clear field error
      clearFieldError: function(fieldId) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(fieldId + '_error');
        
        if (field && errorElement) {
          field.classList.remove('form-field-error');
          errorElement.style.display = 'none';
        }
      },
      
      // Show field success
      showFieldSuccess: function(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
          field.classList.remove('form-field-error');
          field.classList.add('form-field-valid');
        }
      },
      
      // Clear field success
      clearFieldSuccess: function(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
          field.classList.remove('form-field-valid');
        }
      },
      
      // Clear all errors in a form
      clearAllErrors: function(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        // Clear banner
        this.hideErrorBanner(formId);
        
        // Clear field errors
        const errorMessages = form.querySelectorAll('.error-message');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        errorMessages.forEach(error => {
          error.style.display = 'none';
          error.textContent = '';
        });
        
        inputs.forEach(input => {
          input.classList.remove('form-field-error');
        });
        
        // Clear error summary
        const errorSummary = form.querySelector('.error-summary');
        if (errorSummary) {
          errorSummary.remove();
        }
      },
      
      // Shake animation for error feedback
      shakeElement: function(element) {
        element.style.animation = 'shake 0.5s';
        setTimeout(() => {
          element.style.animation = '';
        }, 500);
      },
      
      // Show error summary
      showErrorSummary: function(formId, errors) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        // Remove existing summary
        const existingSummary = form.querySelector('.error-summary');
        if (existingSummary) {
          existingSummary.remove();
        }
        
        // Create new summary
        const summary = document.createElement('div');
        summary.className = 'error-summary';
        
        const ul = document.createElement('ul');
        Object.keys(errors).forEach(fieldId => {
          const li = document.createElement('li');
          li.textContent = errors[fieldId];
          ul.appendChild(li);
        });
        
        summary.appendChild(ul);
        
        // Insert before the form buttons or at the end
        const buttons = form.querySelector('.flex.justify-end, .flex.justify-between, .modal-footer, .form-actions');
        if (buttons) {
          form.insertBefore(summary, buttons);
        } else {
          form.appendChild(summary);
        }
      },
      
      // Validate required field
      validateRequired: function(value, fieldName) {
        if (!value || value.trim() === '') {
          return `${fieldName} is required.`;
        }
        return null;
      },
      
      // Validate email
      validateEmail: function(value) {
        if (!value) return 'Email is required.';
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          return 'Please enter a valid email address.';
        }
        return null;
      },
      
      // Validate name (letters and spaces only)
      validateName: function(value, fieldName) {
        if (!value || value.trim() === '') {
          return `${fieldName} is required.`;
        }
        if (value.length < 2) {
          return `${fieldName} must be at least 2 characters.`;
        }
        if (!/^[A-Za-z\s]+$/.test(value)) {
          return `${fieldName} may only contain letters and spaces.`;
        }
        return null;
      },
      
      // Validate password
      validatePassword: function(value) {
        if (!value) return 'Password is required.';
        if (value.length < 6) {
          return 'Password must be at least 6 characters.';
        }
        return null;
      },
      
      // Validate date range
      validateDateRange: function(startDate, endDate) {
        if (!startDate) return 'Start date is required.';
        if (!endDate) return 'End date is required.';
        if (new Date(endDate) < new Date(startDate)) {
          return 'End date must be after or equal to start date.';
        }
        return null;
      },
      
      // Validate phone number
      validatePhone: function(value) {
        if (!value) return 'Phone number is required.';
        // Basic phone number validation (allows various formats)
        const phoneRegex = /^[+]?[0-9\s\-()]{10,20}$/;
        if (!phoneRegex.test(value)) {
          return 'Please enter a valid phone number.';
        }
        return null;
      },
      
      // Validate numeric values
      validateNumeric: function(value, fieldName) {
        if (!value && value !== 0) return `${fieldName} is required.`;
        if (isNaN(value) || value < 0) {
          return `${fieldName} must be a positive number.`;
        }
        return null;
      },
      
      // Validate length
      validateLength: function(value, fieldName, min, max) {
        if (!value) return `${fieldName} is required.`;
        if (value.length < min) {
          return `${fieldName} must be at least ${min} characters.`;
        }
        if (max && value.length > max) {
          return `${fieldName} cannot exceed ${max} characters.`;
        }
        return null;
      }
    };
  </script>
  <style>
    /* Sidebar label animation and alignment (base) */
    #sidebar a { display: flex; align-items: center; column-gap: 12px; }
    #sidebar a > i, #sidebar a > .fa-layers, #sidebar a > img.icon { width: 24px; min-width: 24px; text-align: center; }
    #sidebar a .sidebar-text { display: inline-block; white-space: nowrap; overflow: hidden; opacity: 1; max-width: 220px; transition: max-width 220ms ease, opacity 220ms ease; }
    #sidebar.sidebar-collapsed a .sidebar-text { max-width: 0; opacity: 0; }
    #sidebar.sidebar-collapsed a { justify-content: center; }

    /* Keep brand area height constant */
    #brandBox { height: 180px; overflow: hidden; position: relative; }

    /* Always-visible toggle button styling */
    #sidebar #sidebarToggle { position: absolute; top: 0.5rem; right: 0.5rem; padding: 0.375rem; z-index: 2000; display: block; }
    #sidebar #sidebarToggle i { font-size: 0.875rem; }
    #sidebar.sidebar-collapsed #sidebarToggle { top: 0.25rem; right: 0.25rem; padding: 0.125rem; }
    #sidebar.sidebar-collapsed #sidebarToggle i { font-size: 0.625rem; }
    /* Disable transitions during initialization to prevent flash */
    #sidebar.no-transition { transition: none !important; }

    /* Alpine.js cloak to prevent flash */
    [x-cloak] { display: none !important; }
    
    /* Form Validation Styles */
    .validation-error-banner {
      background-color: #dc2626;
      color: white;
      padding: 12px 16px;
      margin-bottom: 16px;
      border-radius: 6px;
      font-weight: 500;
      font-size: 14px;
    }

    .form-field-error {
      border-color: #dc2626 !important;
      border-width: 2px !important;
    }

    .form-field-error:focus {
      border-color: #dc2626 !important;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    .error-message {
      color: #dc2626;
      font-size: 14px;
      font-weight: 500;
      margin-top: 4px;
      display: block;
      min-height: 0;
      transition: all 0.2s ease;
    }

    .error-message:empty {
      display: none;
    }

    .error-summary {
      background-color: #dc2626;
      border: 1px solid #fecaca;
      border-radius: 6px;
      padding: 12px 16px;
      margin-top: 16px;
    }

    .error-summary ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .error-summary li {
      color: #dc2626;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
    }

    .error-summary li:before {
      content: "•";
      color: #dc2626;
      font-weight: bold;
      margin-right: 8px;
    }

    .error-summary li:last-child {
      margin-bottom: 0;
    }

    /* Enhanced Form Inputs */
    .form-input {
      width: 100%;
      border: 2px solid #d1d5db;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 14px;
      transition: all 0.2s ease;
      background-color: #ffffff;
    }

    .form-input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      background-color: #ffffff;
    }

    .form-select {
      width: 100%;
      border: 2px solid #d1d5db;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 14px;
      background-color: white;
      transition: all 0.2s ease;
      appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 12px center;
      background-repeat: no-repeat;
      background-size: 16px 16px;
      padding-right: 40px;
    }

    .form-select:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Error state for form inputs */
    .form-input.error,
    .form-select.error {
      border-color: #dc2626 !important;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    .form-input.error:focus,
    .form-select.error:focus {
      border-color: #dc2626 !important;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    /* Animation for input field errors */
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    /* Smooth transitions for error states */

    .form-input, .form-select {
      transition: all 0.2s ease-in-out;
    }

    /* Form field validation styles from devices */
    .form-field-valid {
      border-color: #10b981 !important;
      background-color: #f0fdf4 !important;
    }

    .form-field-invalid {
      border-color: #dc2626 !important;
      background-color: #fef2f2 !important;
    }

    .validation-icon {
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      width: 1rem;
      height: 1rem;
      pointer-events: none;
    }

    .validation-icon.valid {
      color: #10b981;
    }

    .validation-icon.invalid {
      color: #dc2626;
    }

    /* Textarea styling */
    .form-textarea {
      width: 100%;
      border: 2px solid #d1d5db;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 14px;
      resize: vertical;
      min-height: 80px;
      transition: all 0.2s ease;
    }

    .form-textarea:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea.form-field-error {
      border-color: #dc2626;
    }

    .form-textarea.form-field-error:focus {
      border-color: #dc2626;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
    
    /* Unified Banner Styles */
    .alert-banner {
      position: fixed;
      top: 1rem;
      left: 50%;
      transform: translateX(-50%);
      z-index: 9999999;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 1rem 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      font-weight: 500;
    }
    
    .alert-banner.success {
      background-color: #ffffff;
      border: 1px solidrgb(97, 226, 142);
      color: #166534;
    }
    
    .alert-banner.error {
      background-color:rgb(255, 214, 214);
      color: #dc2626;
      border: 1px solid rgb(220, 38, 38);
      margin-top: 0.25rem;
      display: block;
      align-items: center;
      gap: 0.25rem;
    }
        
    /* Enhanced Modal */
    .modal-container {
      backdrop-filter: blur(4px);
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Search and Filter Styling */
    .search-input {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m19 19-3.5-3.5m0 0a7 7 0 1 1-10-10 7 7 0 0 1 10 10Z'/%3e%3c/svg%3e");
      background-position: left 12px center;
      background-repeat: no-repeat;
      background-size: 16px 16px;
      padding-left: 40px;
    }
  </style>
</head>
<body class="flex min-h-screen bg-gray-200">

  <!-- Global Toast Container -->
  <div id="toast-root" class="fixed top-4 right-4 z-[99999] space-y-3"></div>
  
  <!-- Global Alert Banner Container -->
  <div id="alert-banner-container"></div>

  <!-- Sidebar -->
<aside id="sidebar" class="w-[260px] bg-[#242F41] text-white flex flex-col shadow-lg h-screen overflow-y-auto transition-all duration-300 ease-in-out">
  <div id="brandBox" class="flex flex-col items-center py-6 border-b border-slate-700 relative">
    <button id="sidebarToggle" class="absolute top-2 right-2 text-white hover:text-gray-300 focus:outline-none p-1.5 rounded hover:bg-[#185E9A] transition-colors">
      <i class="fas fa-bars text-sm"></i>
    </button>
    <div class="flex flex-col items-center">
    <img
      src="{{ asset('images/logo.png') }}"
      alt="MDDRMO Logo"
      class="w-20 h-20 object-contain mb-3"
    />
    <h2 class="text-xl font-semibold">MDDRMO</h2>
    <p class="text-sm text-slate-300 mt-1">Rainfall Monitoring</p>
    </div>
  </div>

  <nav class="flex-1 mt-4">
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('dashboard') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
      <i class="fas fa-home w-5"></i>
      <span class="sidebar-text">Dashboard</span>
    </a>

    <a href="{{ route('history') }}"
       class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('history') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
      <i class="fas fa-history w-5"></i>
      <span class="sidebar-text">Rainfall History</span>
    </a>

    <a href="{{ route('devices.index') }}"
       class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('devices.*') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
      <i class="fas fa-microchip w-5"></i>
      <span class="sidebar-text">Device Management</span>
    </a>

     {{-- ✅ NEW: Recipients --}}
  <a href="{{ route('contacts.index') }}"
     class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('contacts.*') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
    <i class="fas fa-users w-5"></i>
    <span class="sidebar-text">Contacts</span>
  </a>

    <a href="{{ route('messages.index') }}"
       class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('messages.*') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
      <i class="fas fa-comments text-white text-lg"></i>
      <span class="sidebar-text">Message Management</span>
    </a>

    <a href="{{ route('reports.index') }}"
       class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('reports.*') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
      <i class="fas fa-file-alt w-5"></i>
      <span class="sidebar-text">Reports</span>
    </a>

    <a href="{{ route('users') }}"
       class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('users') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
      <i class="fas fa-users-cog w-5"></i>
      <span class="sidebar-text">User Management</span>
    </a>
  </nav>

  <a href="{{ route('settings') }}"
     id="settingsLink"
     class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('settings') ? 'bg-[#185E9A]' : 'hover:bg-[#185E9A]' }}">
    <i class="fas fa-cog w-5"></i>
    <span class="sidebar-text">Settings</span>
  </a>




</aside>

<!-- Inline script to prevent sidebar flash -->
 <script>
   (function() {
     const sidebarState = localStorage.getItem('sidebarState');
     const sidebar = document.getElementById('sidebar');
     if (sidebar) {
       sidebar.classList.add('no-transition');

       const sidebarLogo = sidebar.querySelector('img');
       const sidebarTitle = sidebar.querySelector('h2');
       const sidebarSubtitle = sidebar.querySelector('p');
       const sidebarToggle = sidebar.querySelector('#sidebarToggle');

       if (sidebarState === 'collapsed') {
         sidebar.style.width = '80px';
         sidebar.style.overflow = 'auto';
         sidebar.style.opacity = '1';
         sidebar.classList.add('sidebar-collapsed');

         // Set collapsed styles immediately to prevent flash
         if (sidebarLogo) {
           sidebarLogo.style.width = '3rem';
           sidebarLogo.style.height = '3rem';
           sidebarLogo.style.marginBottom = '0.5rem';
         }
         if (sidebarTitle) {
           sidebarTitle.style.display = 'block';
           sidebarTitle.style.fontSize = '0.875rem';
           sidebarTitle.style.textAlign = 'center';
         }
         if (sidebarSubtitle) {
           sidebarSubtitle.style.display = 'block';
           sidebarSubtitle.style.fontSize = '0.75rem';
           sidebarSubtitle.style.textAlign = 'center';
         }
         if (sidebarToggle) {
           sidebarToggle.style.display = 'block';
           sidebarToggle.style.top = '0.25rem';
           sidebarToggle.style.right = '0.25rem';
           sidebarToggle.style.left = 'auto';
           sidebarToggle.style.padding = '0.125rem';
           const toggleIcon = sidebarToggle.querySelector('i');
           if (toggleIcon) {
             toggleIcon.style.fontSize = '0.625rem';
           }
         }
       } else {
         // Ensure expanded state styles are set
         sidebar.style.width = '260px';
         sidebar.style.overflow = 'visible';
         sidebar.style.opacity = '1';
         sidebar.classList.remove('sidebar-collapsed');

         if (sidebarLogo) {
           sidebarLogo.style.width = '5rem';
           sidebarLogo.style.height = '5rem';
           sidebarLogo.style.marginBottom = '0.75rem';
         }
         if (sidebarTitle) {
           sidebarTitle.style.display = 'block';
           sidebarTitle.style.fontSize = '';
           sidebarTitle.style.textAlign = '';
         }
         if (sidebarSubtitle) {
           sidebarSubtitle.style.display = 'block';
           sidebarSubtitle.style.fontSize = '';
           sidebarSubtitle.style.textAlign = '';
           sidebarSubtitle.style.whiteSpace = '';
         }
         if (sidebarToggle) {
           sidebarToggle.style.display = 'block';
           sidebarToggle.style.top = '0.5rem';
           sidebarToggle.style.right = '0.5rem';
           sidebarToggle.style.left = 'auto';
           sidebarToggle.style.padding = '0.375rem';
           const toggleIcon = sidebarToggle.querySelector('i');
           if (toggleIcon) {
             toggleIcon.style.fontSize = '0.875rem';
           }
         }
       }
     }
   })();
 </script>

<!-- Main Content -->
  <main class="flex-1 h-screen overflow-y-scroll">

<header class="text-black flex justify-between items-center px-6 py-2 shadow-lg relative border-b-2 border-gray-200 z-40" style="background-color: #f5f5f5ff;">
  <!-- Removed the dark overlay -->
  
      <div class="relative z-10 flex items-center gap-4">
      </div>

      <div class="relative z-10 flex items-center gap-6">
        <div class="text-right">
      <div id="clock" class="text-lg font-semibold text-gray-800">00:00</div>
      <div id="date" class="text-xs text-gray-600">Loading...</div>
        </div>

    <div class="relative" x-data="{ notificationOpen: false }">
          <button @click="notificationOpen = !notificationOpen" 
                  @click.away="notificationOpen = false"
                  class="relative text-gray-600 hover:text-black focus:outline-none p-2 rounded hover:bg-gray-200 transition-colors">
            <i class="fas fa-bell text-lg"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
              <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                {{ auth()->user()->unreadNotifications->count() }}
              </span>
            @endif
          </button>

          <!-- Notification Dropdown -->
          <div x-show="notificationOpen" 
               x-transition:enter="transition ease-out duration-200" 
               x-transition:enter-start="opacity-0 transform scale-95" 
               x-transition:enter-end="opacity-100 transform scale-100" 
               x-transition:leave="transition ease-in duration-100" 
               x-transition:leave-start="opacity-100 transform scale-100" 
               x-transition:leave-end="opacity-0 transform scale-95" 
               class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-[99999] max-h-[80vh] overflow-y-auto">
            
            <!-- Dropdown Header -->
            <div class="p-3 border-b border-gray-200 flex justify-between items-center bg-gray-50">
              <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
              @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="m-0">
                  @csrf
                  <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                    Mark all as read
                  </button>
                </form>
              @endif
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-100">
              @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                <div class="p-4 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'opacity-75' : '' }}">
                  <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 {{ $notification->colorClass }} rounded-full flex items-center justify-center">
                      <i class="{{ $notification->icon }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900">
                        {{ $notification->title }}
                      </p>
                      <p class="text-sm text-gray-600 line-clamp-2">
                        {{ $notification->message }}
                      </p>
                      <p class="text-xs text-gray-500 mt-1">
                        {{ $notification->formattedTime }}
                      </p>
                    </div>
                    @unless($notification->is_read)
                      <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                          Mark as read
                        </button>
                      </form>
                    @endunless
                  </div>
                </div>
              @empty
                <div class="p-4 text-center text-gray-500">
                  No notifications
                </div>
              @endforelse
            </div>

            <!-- View All Link -->
            <a href="{{ route('notifications.index') }}" class="block p-3 text-center text-sm text-blue-600 hover:text-blue-800 font-medium border-t bg-gray-50">
              View all notifications
            </a>
          </div>
        </div>

        <div class="relative z-[50]">
      <button id="userProfileBtn" class="text-gray-600 hover:text-black focus:outline-none p-2 rounded hover:bg-gray-200 transition-colors">
            <i class="fas fa-user-circle text-lg"></i>
          </button>

                      <!-- User Profile Dropdown -->
            <div id="userProfileDropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 hidden z-[99999] overflow-hidden">
            <!-- Profile Info Section -->
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-sky-50 to-blue-50">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center shadow-lg relative overflow-hidden cursor-pointer" id="avatarImage" onclick="openProfilePictureModal()">
                  @php
                    // Generate a full name from user fields
                    $fullName = trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? ''));
                    $displayName = $fullName ?: $user->username ?? 'U';
                  @endphp
                  <div class="w-full h-full bg-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg hover:opacity-80 transition-opacity">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                  <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 rounded-full transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-camera text-white opacity-0 hover:opacity-100 transition-opacity text-xs"></i>
                  </div>
                </div>
                <div class="flex-1">
                  <div class="font-bold text-gray-900 text-base">{{ $displayName }}</div>
                  <div class="text-xs text-gray-600 mb-1">{{ Auth::user()->email ?? 'user@example.com' }}</div>
                  <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Admin
                  </div>
                </div>
              </div>
            </div>

            <!-- Sign Out Section -->
            <div class="p-2">
              <form action="{{ route('logout') }}" method="POST" style="margin: 0;" onsubmit="console.log('Logout form submitted');">
                @csrf
                <button type="submit"
                   onclick="console.log('Logout button clicked');"
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-3 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm">
                  <i class="fas fa-sign-out-alt text-gray-600 text-sm"></i>
                  <span class="text-sm">Log out</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Light Content Area -->
    <div class="bg-gray-200 min-h-full">
      <!-- Page Title and Breadcrumbs -->
      <div class="px-6 py-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">@yield('page_heading', 'Dashboard')</h1>
      </div>
    <!-- Dynamic Page Content -->
      <div class="px-6 pb-6">
      @yield('content')
      </div>
    </div>
  </main>

<!-- Clock Script -->
  <script>


    function updateClock() {
      const now = new Date();
      document.getElementById('clock').textContent = now.toLocaleTimeString('en-GB');
      document.getElementById('date').textContent = now.toLocaleDateString('en-GB', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Sidebar Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Toast helper
      window.showToast = function(type, message, options = {}) {
        const root = document.getElementById('toast-root');
        const id = 't_' + Math.random().toString(36).slice(2);
        const bg = type === 'success' ? 'bg-green-50' : type === 'error' ? 'bg-red-50' : 'bg-blue-50';
        const text = type === 'success' ? 'text-green-800' : type === 'error' ? 'text-red-800' : 'text-blue-800';
        const icon = type === 'success' ? 'fa-check-circle text-green-500' : type === 'error' ? 'fa-times-circle text-red-500' : 'fa-info-circle text-blue-500';
        const duration = options.duration ?? 4000;
        const el = document.createElement('div');
        el.id = id;
        el.className = `${bg} ${text} shadow-md rounded-lg px-4 py-3 w-80 border flex items-start gap-3 transition transform translate-x-96`;
        el.innerHTML = `
          <i class="fas ${icon} mt-0.5"></i>
          <div class="flex-1 text-sm">${message}</div>
          <button class="opacity-60 hover:opacity-100" aria-label="Close" onclick="(function(id){ const n=document.getElementById(id); if(n){ n.classList.add('translate-x-96','opacity-0'); setTimeout(()=>n.remove(), 220);} })('${id}')">
            <i class="fas fa-times"></i>
          </button>
        `;
        root.appendChild(el);
        setTimeout(() => { el.classList.remove('translate-x-96'); }, 20);
        setTimeout(() => {
          if (document.getElementById(id)) {
            el.classList.add('translate-x-96','opacity-0');
            setTimeout(() => el.remove(), 220);
          }
        }, duration);
      };
      
      // Unified banner helper
      window.showBanner = function(type, message, duration = 1500) {
        // Remove any existing banners
        closeBanner();
        
        const container = document.getElementById('alert-banner-container');
        const banner = document.createElement('div');
        
        // Set banner classes based on type
        const bannerClasses = 'alert-banner';
        const typeClasses = type === 'success' ? 'success' : 'error';
        const iconClass = type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-red-500';
        
        banner.className = `${bannerClasses} ${typeClasses}`;
        banner.innerHTML = `
          <i class="fas ${iconClass} text-lg"></i>
          <span class="font-medium">${message}</span>
        `;
        
        container.appendChild(banner);
        
        // Auto-close banner after specified duration
        if (duration > 0) {
          setTimeout(() => {
            if (banner.parentNode) {
              banner.classList.add('opacity-0', 'translate-y-[-20px]');
              setTimeout(() => {
                if (banner.parentNode) {
                  banner.remove();
                }
              }, 300);
            }
          }, duration);
        }
      };
      
      // Close banner function
      window.closeBanner = function() {
        const container = document.getElementById('alert-banner-container');
        if (container) {
          // Remove all banner elements
          while (container.firstChild) {
            container.removeChild(container.firstChild);
          }
        }
        
        // Also remove legacy success banner if it exists
        const legacyBanner = document.getElementById('success-banner');
        if (legacyBanner) {
          legacyBanner.remove();
        }
      };

      // Auto-close legacy success banner after 1 second
      const banner = document.getElementById('success-banner');
      if (banner) {
        setTimeout(() => closeBanner(), 1000);
      };
      
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebarTexts = document.querySelectorAll('.sidebar-text');
      const sidebarLogo = sidebar.querySelector('img');
      const sidebarTitle = sidebar.querySelector('h2');
      const sidebarSubtitle = sidebar.querySelector('p');
      let isSidebarOpen = true;
      let isTransitioning = false;

      // User Profile Dropdown Functionality
      const userProfileBtn = document.getElementById('userProfileBtn');
      const userProfileDropdown = document.getElementById('userProfileDropdown');
      let isDropdownOpen = false;

      function collapseSidebar() {
        // Close sidebar
        sidebar.style.width = '80px';
        sidebar.style.overflow = 'auto';
        sidebar.style.opacity = '1';
        isSidebarOpen = false;
        localStorage.setItem('sidebarState', 'collapsed');
        
        // Smoothly collapse nav link texts via CSS class
        sidebar.classList.add('sidebar-collapsed');
        sidebarTitle.style.display = 'block';
        sidebarSubtitle.style.display = 'block';
        // Reduce brand text size to fit collapsed width
        sidebarTitle.style.fontSize = '0.875rem'; // ~text-sm
        sidebarSubtitle.style.fontSize = '0.75rem'; // ~text-xs
        sidebarTitle.style.textAlign = 'center';
        sidebarSubtitle.style.textAlign = 'center';

        // Keep the toggle button visible and adjust position for collapsed state
        sidebarToggle.style.display = 'block';
        sidebarToggle.style.top = '0.25rem';
        sidebarToggle.style.right = '0.25rem';
        sidebarToggle.style.left = 'auto';
        sidebarToggle.style.padding = '0.125rem';
        sidebarToggle.querySelector('i').style.fontSize = '0.625rem';

        // Keep logo visible but make it smaller
        sidebarLogo.style.width = '3rem';
        sidebarLogo.style.height = '3rem';
        sidebarLogo.style.marginBottom = '0.5rem';
      }

      function expandSidebar() {
        // Open sidebar
        sidebar.style.width = '260px';
        sidebar.style.overflow = 'visible';
        sidebar.style.opacity = '1';
        isSidebarOpen = true;
        localStorage.setItem('sidebarState', 'expanded');

        // Expand nav link texts via CSS class and restore brand text sizing
        sidebar.classList.remove('sidebar-collapsed');
        sidebarTitle.style.display = 'block';
        sidebarSubtitle.style.display = 'block';
        sidebarTitle.style.fontSize = '';
        sidebarSubtitle.style.fontSize = '';
        sidebarTitle.style.textAlign = '';
        sidebarSubtitle.style.textAlign = '';
        sidebarSubtitle.style.whiteSpace = '';

        // Keep the toggle button visible and restore original size/position
        sidebarToggle.style.display = 'block';
        sidebarToggle.style.top = '0.5rem';
        sidebarToggle.style.right = '0.5rem';
        sidebarToggle.style.left = 'auto';
        sidebarToggle.style.padding = '0.375rem';
        sidebarToggle.querySelector('i').style.fontSize = '0.875rem';

        // Restore logo size
        sidebarLogo.style.width = '5rem';
        sidebarLogo.style.height = '5rem';
        sidebarLogo.style.marginBottom = '0.75rem';
      }

      // Initialize from saved state to avoid auto-opening after navigation
      const savedState = localStorage.getItem('sidebarState');
      if (savedState === 'collapsed') {
        collapseSidebar();
      } else {
        expandSidebar();
      }

      // Toggle sidebar (expand/collapse) with debounce to avoid flicker/double-toggles
      sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (isTransitioning) return;
        isTransitioning = true;
        if (isSidebarOpen) {
          collapseSidebar();
        } else {
          expandSidebar();
        }
        setTimeout(() => { isTransitioning = false; }, 260); // matches CSS/JS timing
      });

      // Prevent menu items and brand from toggling sidebar state
      const brandBox = document.getElementById('brandBox');
      brandBox.addEventListener('click', (e) => e.stopPropagation());
      document.querySelectorAll('#sidebar nav a, #settingsLink').forEach(link => {
        link.addEventListener('click', (e) => {
          // Do not toggle sidebar; allow normal navigation
          e.stopPropagation();
        });
      });

      // Toggle user profile dropdown
      userProfileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        isDropdownOpen = !isDropdownOpen;
        userProfileDropdown.classList.toggle('hidden', !isDropdownOpen);
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!userProfileBtn.contains(e.target) && !userProfileDropdown.contains(e.target)) {
          isDropdownOpen = false;
          userProfileDropdown.classList.add('hidden');
        }
      });
    });


  </script>

  
  <!-- Profile Picture Customization Modal -->
  <div id="profilePictureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl relative transform transition-all duration-300 scale-100">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Customize Profile</h2>
        <button type="button" onclick="closeProfilePictureModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Tab Navigation -->
      <div class="flex border-b border-gray-200">
        <button type="button" onclick="switchTab('picture')" id="pictureTab" class="profile-tab active-tab px-6 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
          <i class="fas fa-image mr-2"></i>
          Profile Picture
        </button>
        <button type="button" onclick="switchTab('info')" id="infoTab" class="profile-tab px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
          <i class="fas fa-user-edit mr-2"></i>
          Personal Info
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <!-- Picture Tab Content -->
        <div id="pictureTabContent" class="tab-content">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Side - Current Profile Picture -->
            <div class="text-center">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Profile Picture</h3>
              <div class="w-32 h-32 mx-auto mb-4 relative">
                <div id="currentProfilePicture" class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center shadow-lg relative overflow-hidden">
                  @if(Auth::user()->profile_picture)
                    @if(str_starts_with(Auth::user()->profile_picture, 'preset:'))
                      @php
                        $presetColor = str_replace('preset:', '', Auth::user()->profile_picture);
                        $colorClasses = [
                          'blue-purple' => 'bg-gradient-to-br from-blue-500 to-purple-600',
                          'green' => 'bg-green-600',
                          'pink' => 'bg-pink-500',
                          'orange' => 'bg-orange-500',
                          'royal-blue' => 'bg-blue-600',
                          'purple' => 'bg-purple-600',
                          'emerald' => 'bg-emerald-600',
                          'cyan' => 'bg-cyan-500'
                        ];
                        $bgClass = $colorClasses[$presetColor] ?? 'bg-blue-500';
                      @endphp
                      <div class="w-full h-full {{ $bgClass }} rounded-full flex items-center justify-center text-white text-4xl font-bold">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                      </div>
                    @else
                      <img src="{{ asset('images/' . Auth::user()->profile_picture) }}" alt="Current Avatar" class="w-full h-full object-cover rounded-full">
                    @endif
                  @else
                    <div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                      {{ strtoupper(substr($displayName, 0, 1)) }}
                    </div>
                  @endif
                  <button class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs hover:bg-red-600 transition-colors z-10 shadow-lg border-2 border-white" onclick="resetToDefault()">
                    <i class="fas fa-edit"></i>
                  </button>
                </div>
              </div>

              <!-- Upload New Picture Section -->
              <div class="mt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-3">Upload New Picture</h4>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer">
                  <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                  <p class="text-gray-600 mb-1">Drag & drop your image here</p>
                  <p class="text-gray-500 text-sm mb-3">or click to browse files</p>
                  <p class="text-blue-600 text-xs font-medium">Supports JPG, PNG, GIF up to 5MB</p>
                  <input type="file" id="profilePictureInput" class="hidden" accept="image/*">
                </div>
              </div>
            </div>

            <!-- Right Side - Choose from Presets -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Choose from Presets</h3>
              
              <!-- Category Tabs -->
              <div class="flex space-x-1 mb-4 bg-gray-100 rounded-lg p-1">
                <button class="preset-tab active" data-category="all">All</button>
                <button class="preset-tab" data-category="professional">Professional</button>
                <button class="preset-tab" data-category="fun">Fun</button>
                <button class="preset-tab" data-category="animals">Animals</button>
              </div>

              <!-- Preset Avatars Grid -->
              <div class="grid grid-cols-4 gap-3 mb-6">
                <!-- Blue Purple Gradient -->
                <div class="preset-avatar" data-color="blue-purple" data-category="professional">
                  <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Green -->
                <div class="preset-avatar" data-color="green" data-category="professional">
                  <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Pink -->
                <div class="preset-avatar" data-color="pink" data-category="fun">
                  <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Orange -->
                <div class="preset-avatar" data-color="orange" data-category="fun">
                  <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Royal Blue -->
                <div class="preset-avatar" data-color="royal-blue" data-category="professional">
                  <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Purple -->
                <div class="preset-avatar" data-color="purple" data-category="fun">
                  <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Emerald -->
                <div class="preset-avatar" data-color="emerald" data-category="professional">
                  <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
                
                <!-- Cyan -->
                <div class="preset-avatar" data-color="cyan" data-category="professional">
                  <div class="w-12 h-12 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:scale-110 transition-transform">
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                  </div>
                </div>
              </div>

              <!-- Selected Avatar Preview -->
              <div id="selectedAvatarSection" class="hidden bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                  <div id="selectedAvatarPreview" class="w-10 h-10 rounded-full"></div>
                  <div>
                    <p class="font-medium text-gray-900">Selected Avatar</p>
                    <p id="selectedAvatarName" class="text-sm text-gray-600"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Personal Info Tab Content -->
        <div id="infoTabContent" class="tab-content hidden">
          <form id="profileInfoForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Full Name -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-user text-blue-600 mr-2"></i>
                  Full Name
                </label>
                <input type="text" id="fullName" name="name" value="{{ $fullName }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                       placeholder="Enter your full name">
              </div>

              <!-- Email -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-envelope text-blue-600 mr-2"></i>
                  Email Address
                </label>
                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                       placeholder="Enter your email address">
              </div>

              <!-- Phone Number -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-phone text-blue-600 mr-2"></i>
                  Phone Number
                </label>
                <input type="tel" id="phone" name="contact_no" value="{{ Auth::user()->contact_no ?? '' }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                       placeholder="Enter your phone number">
              </div>

              <!-- Gender -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-venus-mars text-blue-600 mr-2"></i>
                  Gender
                </label>
                <select id="gender" name="gender" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                  <option value="">Select Gender</option>
                  <option value="male" {{ (Auth::user()->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                  <option value="female" {{ (Auth::user()->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                  <option value="other" {{ (Auth::user()->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                  <option value="prefer_not_to_say" {{ (Auth::user()->gender ?? '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                </select>
              </div>

              <!-- Date of Birth -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-calendar text-blue-600 mr-2"></i>
                  Date of Birth
                </label>
                <input type="date" id="dateOfBirth" name="date_of_birth" value="{{ Auth::user()->date_of_birth ?? '' }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
              </div>

              <!-- Address -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                  Address
                </label>
                <textarea id="address" name="address" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                          placeholder="Enter your address">{{ Auth::user()->address ?? '' }}</textarea>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-between p-6 border-t border-gray-200">
        <button type="button" onclick="resetToDefault()" class="flex items-center text-red-600 hover:text-red-700 transition-colors" id="resetButton">
          <i class="fas fa-undo mr-2"></i>
          Reset to Default
        </button>
        <div class="flex space-x-3">
          <button type="button" onclick="closeProfilePictureModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            Cancel
          </button>
          <button type="button" onclick="saveProfileChanges()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center" id="saveButton">
            <i class="fas fa-save mr-2"></i>
            <span id="saveButtonText">Save Changes</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Picture Modal Scripts -->
  <script>
    let selectedAvatar = null;
    let uploadedFile = null;
    let currentTab = 'picture';
    
    // Modal functions
    function openProfilePictureModal() {
      document.getElementById('profilePictureModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      switchTab('picture'); // Always start with picture tab
    }

    function closeProfilePictureModal() {
      document.getElementById('profilePictureModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
      selectedAvatar = null;
      uploadedFile = null;
      currentTab = 'picture';
      document.getElementById('selectedAvatarSection').classList.add('hidden');
    }

    // Tab switching functionality
    function switchTab(tabName) {
      currentTab = tabName;
      
      // Update tab buttons
      document.querySelectorAll('.profile-tab').forEach(tab => {
        tab.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
      });
      
      // Show/hide tab content
      document.getElementById('pictureTabContent').classList.toggle('hidden', tabName !== 'picture');
      document.getElementById('infoTabContent').classList.toggle('hidden', tabName !== 'info');
      
      // Update active tab
      const activeTab = document.getElementById(tabName + 'Tab');
      activeTab.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
      activeTab.classList.remove('border-transparent', 'text-gray-500');
      
      // Update footer buttons based on active tab
      const resetButton = document.getElementById('resetButton');
      const saveButtonText = document.getElementById('saveButtonText');
      
      if (tabName === 'picture') {
        resetButton.style.display = 'flex';
        saveButtonText.textContent = 'Save Changes';
      } else {
        resetButton.style.display = 'flex';
        saveButtonText.textContent = 'Update Profile';
      }
    }

    // Preset avatar selection
    document.querySelectorAll('.preset-avatar').forEach(avatar => {
      avatar.addEventListener('click', function() {
        // Remove previous selection
        document.querySelectorAll('.preset-avatar').forEach(a => a.classList.remove('ring-4', 'ring-blue-500'));
        
        // Add selection to clicked avatar
        this.classList.add('ring-4', 'ring-blue-500');
        
        // Store selected avatar
        selectedAvatar = {
          type: 'preset',
          color: this.dataset.color,
          name: this.dataset.color.charAt(0).toUpperCase() + this.dataset.color.slice(1).replace('-', ' ') + ' Gradient'
        };
        
        // Show selected avatar preview
        showSelectedAvatar();
      });
    });

    // Category tabs
    document.querySelectorAll('.preset-tab').forEach(tab => {
      tab.addEventListener('click', function() {
        // Update active tab
        document.querySelectorAll('.preset-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        // Filter avatars
        const category = this.dataset.category;
        document.querySelectorAll('.preset-avatar').forEach(avatar => {
          if (category === 'all' || avatar.dataset.category === category) {
            avatar.style.display = 'block';
          } else {
            avatar.style.display = 'none';
          }
        });
      });
    });

    // File upload functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('profilePictureInput');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
      dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropZone.classList.remove('border-blue-400', 'bg-blue-50');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        handleFileUpload(files[0]);
      }
    });

    fileInput.addEventListener('change', (e) => {
      if (e.target.files.length > 0) {
        handleFileUpload(e.target.files[0]);
      }
    });

    function handleFileUpload(file) {
      if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        return;
      }
      
      if (file.size > 5 * 1024 * 1024) {
        alert('File size must be less than 5MB.');
        return;
      }
      
      uploadedFile = file;
      
      // Clear preset selection
      document.querySelectorAll('.preset-avatar').forEach(a => a.classList.remove('ring-4', 'ring-blue-500'));
      
      // Preview uploaded image
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById('currentProfilePicture');
        preview.innerHTML = `<img src="${e.target.result}" alt="Uploaded Avatar" class="w-full h-full object-cover rounded-full">`;
        
        selectedAvatar = {
          type: 'upload',
          name: 'Uploaded Image'
        };
        
        showSelectedAvatar();
      };
      reader.readAsDataURL(file);
    }

    function showSelectedAvatar() {
      const section = document.getElementById('selectedAvatarSection');
      const preview = document.getElementById('selectedAvatarPreview');
      const name = document.getElementById('selectedAvatarName');
      
      if (selectedAvatar.type === 'preset') {
        const colorClasses = {
          'blue-purple': 'bg-gradient-to-br from-blue-500 to-purple-600',
          'green': 'bg-green-600',
          'pink': 'bg-pink-500',
          'orange': 'bg-orange-500',
          'royal-blue': 'bg-blue-600',
          'purple': 'bg-purple-600',
          'emerald': 'bg-emerald-600',
          'cyan': 'bg-cyan-500'
        };
        
        preview.className = `w-10 h-10 rounded-full flex items-center justify-center text-white font-bold ${colorClasses[selectedAvatar.color]}`;
        preview.textContent = '{{ strtoupper(substr(Auth::user()->name ?? "U", 0, 1)) }}';
      }
      
      name.textContent = selectedAvatar.name;
      section.classList.remove('hidden');
    }

    function resetToDefault() {
      if (confirm('Are you sure you want to reset to the default profile picture?')) {
        // Implementation for resetting to default
        fetch('{{ route("profile.avatar.set") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ avatar_url: 'default' })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeProfilePictureModal();
            location.reload();
          } else {
            alert('Failed to reset profile picture.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while resetting profile picture.');
        });
      }
    }

    function saveProfileChanges() {
      if (currentTab === 'picture') {
        // Handle picture changes
        if (!selectedAvatar) {
          alert('Please select an avatar or upload an image.');
          return;
        }
        
        if (selectedAvatar.type === 'upload' && uploadedFile) {
          // Handle file upload
          const formData = new FormData();
          formData.append('profile_picture', uploadedFile);
          formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
          
          fetch('{{ route("profile.picture.update") }}', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              closeProfilePictureModal();
              location.reload();
            } else {
              alert('Failed to update profile picture.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the image.');
          });
        } else if (selectedAvatar.type === 'preset') {
          // Handle preset avatar
          fetch('{{ route("profile.avatar.set") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ avatar_url: selectedAvatar.color })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              closeProfilePictureModal();
              location.reload();
            } else {
              alert('Failed to update profile picture.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the avatar.');
          });
        }
      } else if (currentTab === 'info') {
        // Handle profile info updates
        const formData = new FormData(document.getElementById('profileInfoForm'));
        
        fetch('{{ route("profile.info.update") }}', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeProfilePictureModal();
            location.reload();
          } else {
            alert('Failed to update profile information.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while updating profile information.');
        });
      }
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !document.getElementById('profilePictureModal').classList.contains('hidden')) {
        closeProfilePictureModal();
      }
    });
  </script>

</body>
</html>
