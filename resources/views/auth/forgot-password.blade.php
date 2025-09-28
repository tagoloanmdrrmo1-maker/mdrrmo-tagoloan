<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>MDDRMO Rainfall Monitoring - Forgot Password</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    rel="stylesheet"
  />
</head>
<body class="min-h-screen flex flex-col bg-gray-200">

  
  <!-- Form Container -->
  <div class="flex-grow flex justify-center items-center px-4 pb-20">
    <form
      method="POST"
      action="{{ route('password.email') }}"
      class="bg-gray-200 border border-gray-300 rounded-xl p-6 w-full max-w-xs relative shadow-2xl"
    >
      @csrf

      <!-- Logo and Title -->
      <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="MDDRMO Logo" class="w-20 h-20 object-contain mb-3" />
        
      </div>

      <h2 class="text-gray-900 text-lg font-bold mb-6 text-center font-serif">Forgot Password</h2>

      <p class="text-gray-800 text-sm mb-5 text-center">
        Enter your email address and we'll send you a password reset link.
      </p>

      <label
        for="email"
        class="block text-gray-800 font-semibold mb-1 font-serif"
      >
        Email
      </label>
      <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email') }}"
        required
        placeholder="Enter your email"
        class="w-full rounded-lg px-4 py-3 mb-5 text-gray-700 bg-white/90 backdrop-blur-sm border border-gray-300 outline-none focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
      />
      <p id="email_error" class="text-red-500 text-xs mb-3 hidden"></p>
      @error('email')
        <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
      @enderror

      @if (session('status'))
        <p class="text-green-600 text-xs mb-4">{{ session('status') }}</p>
      @endif

      <button
        type="submit"
        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 rounded-lg mb-4 flex justify-center items-center gap-2 font-serif shadow-lg transition-all duration-200 transform hover:scale-105"
      >
        <i class="fas fa-envelope"></i> Send Reset Link
      </button>

      <a
        href="{{ route('login') }}"
        class="block text-center text-sm text-gray-800 mt-4 hover:underline"
      >Back to Login</a>
    </form>
  </div>
</body>
<script>
  const fpForm = document.querySelector('form[action="{{ route('password.email') }}"]');
  const emailInput = document.getElementById('email');
  const emailErr = document.getElementById('email_error');

  function showEmailError(message) {
    if (emailErr) {
      emailErr.textContent = message;
      emailErr.classList.remove('hidden');
    }
    if (emailInput) {
      emailInput.classList.add('border-red-500');
      emailInput.classList.remove('focus:ring-blue-500','focus:border-blue-500');
      emailInput.classList.add('focus:ring-0');
    }
  }

  function clearEmailError() {
    if (emailErr) emailErr.classList.add('hidden');
    if (emailInput) {
      emailInput.classList.remove('border-red-500','focus:ring-0');
      emailInput.classList.add('focus:ring-blue-500','focus:border-blue-500');
    }
  }

  function isValidEmail(value) {
    return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value);
  }

  if (emailInput) {
    emailInput.addEventListener('blur', function() {
      const val = emailInput.value.trim();
      if (val === '') {
        showEmailError('Email is required');
      } else if (!isValidEmail(val)) {
        showEmailError('Please enter a valid email address');
      } else {
        clearEmailError();
      }
    });

    emailInput.addEventListener('input', function() {
      if (emailInput.value.trim() !== '') clearEmailError();
    });
  }

  if (fpForm) {
    fpForm.addEventListener('submit', function(e) {
      const val = emailInput.value.trim();
      let ok = true;
      if (val === '') { showEmailError('Email is required'); ok = false; }
      else if (!isValidEmail(val)) { showEmailError('Please enter a valid email address'); ok = false; }
      if (!ok) e.preventDefault();
    });
  }
</script>
</html>
