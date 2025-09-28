<!-- resources/views/layouts/navigation.blade.php -->

<nav class="flex h-screen">
  <!-- Sidebar -->
  <aside class="w-[260px] bg-[#0a1d3a] text-white flex flex-col shadow-lg">
    <div class="flex flex-col items-center py-6 border-b border-slate-700">
      <img src="{{ asset('images/logo.png') }}"
           alt="MDDRMO Logo"
           class="w-20 h-20 object-contain mb-3" />
      <h2 class="text-xl font-semibold">MDDRMO</h2>
      <p class="text-sm text-slate-300 mt-1">Rainfall Monitoring</p>
    </div>

    <nav class="flex-1 mt-4">
      <a href="{{ route('dashboard') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('dashboard') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-tachometer-alt w-5"></i>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('history') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('history') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-history w-5"></i>
        <span>Rainfall History</span>
      </a>

      <a href="{{ route('devices') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('devices') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-microchip w-5"></i>
        <span>Device Management</span>
      </a>

      <a href="{{ route('recipients.index') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('recipients.*') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-address-book w-5"></i>
        <span>Recipients</span>
      </a>

      <a href="{{ route('alerts') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('alerts') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-bullhorn w-5"></i>
        <span>Alert Message</span>
      </a>

      @if(auth()->user() && strcasecmp(auth()->user()->role ?? '', 'Admin') === 0)
      <a href="{{ route('users') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('users') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-users w-5"></i>
        <span>User Management</span>
      </a>
      @endif

      <a href="{{ route('profile.edit') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('profile.*') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-user w-5"></i>
        <span>Profile</span>
      </a>

      <a href="{{ route('settings') }}"
         class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('settings') ? 'bg-[#60a5fa]' : 'hover:bg-slate-800' }}">
        <i class="fas fa-cog w-5"></i>
        <span>Settings</span>
      </a>
    </nav>

    <!-- Logout Form -->
    <form method="POST" action="{{ route('logout') }}"
          class="mt-auto px-6 py-4 border-t border-slate-700">
      @csrf
      <button type="submit"
              class="w-full flex items-center gap-2 text-white hover:bg-slate-800 px-2 py-2 rounded">
        <i class="fas fa-sign-out-alt w-5"></i>
        <span>Logout</span>
      </button>
    </form>
  </aside>

  <!-- Main content slot -->
  <div class="flex-1 overflow-y-auto">
    {{ $slot }}
  </div>
</nav>
