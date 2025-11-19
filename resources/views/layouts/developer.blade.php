<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Developer Dashboard') - Project Management</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
    
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Sidebar animations */
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar-collapsed {
            width: 80px;
        }
        
        .sidebar-expanded {
            width: 280px;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar sidebar-expanded fixed left-0 top-0 h-screen bg-gradient-to-b from-blue-600 to-blue-800 text-white z-50 overflow-y-auto hide-scrollbar">
        <!-- Logo & Toggle -->
        <div class="flex items-center justify-between p-6 border-b border-blue-500">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-code text-blue-600 text-xl"></i>
                </div>
                <div id="sidebar-title" class="transition-all">
                    <h1 class="text-lg font-bold">Developer</h1>
                    <p class="text-xs text-blue-200">Dashboard</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="text-white hover:bg-blue-500 p-2 rounded-lg transition">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- User Profile -->
        <div class="p-6 border-b border-blue-500">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr(Auth::user()->full_name ?: Auth::user()->username, 0, 2)) }}
                </div>
                <div id="user-info" class="transition-all flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">
                        {{ Auth::user()->full_name ?: Auth::user()->username }}
                    </p>
                    <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('developer.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('developer.dashboard') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-home text-lg w-5"></i>
                        <span id="menu-text-1" class="transition-all">Dashboard</span>
                    </a>
                </li>

                <!-- My Tasks -->
                <li>
                    <a href="{{ route('developer.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-500 transition">
                        <i class="fas fa-tasks text-lg w-5"></i>
                        <span id="menu-text-2" class="transition-all">My Tasks</span>
                    </a>
                </li>

                <!-- Projects -->
                <li>
                    <a href="{{ route('developer.dashboard') }}?tab=projects" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-500 transition">
                        <i class="fas fa-project-diagram text-lg w-5"></i>
                        <span id="menu-text-3" class="transition-all">Projects</span>
                    </a>
                </li>

                <!-- Time Tracking -->
                <li>
                    <a href="{{ route('developer.time-logs') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('developer.time-logs') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-clock text-lg w-5"></i>
                        <span id="menu-text-4" class="transition-all">Time Logs</span>
                    </a>
                </li>

                <!-- Statistics -->
                <li>
                    <a href="{{ route('developer.statistics') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('developer.statistics') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-chart-bar text-lg w-5"></i>
                        <span id="menu-text-5" class="transition-all">Statistics</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="border-t border-blue-500 my-4"></li>

                <!-- Settings -->
                <li>
                    <a href="#" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-500 transition">
                        <i class="fas fa-cog text-lg w-5"></i>
                        <span id="menu-text-6" class="transition-all">Settings</span>
                    </a>
                </li>

                <!-- Logout -->
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-red-500 transition">
                            <i class="fas fa-sign-out-alt text-lg w-5"></i>
                            <span id="menu-text-7" class="transition-all">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div id="main-content" class="transition-all ml-[280px]">
        <!-- Top Navbar -->
        <header class="bg-white shadow-sm sticky top-0 z-40">
            <div class="flex items-center justify-between px-8 py-4">
                <!-- Page Title -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        @yield('page-title', 'Dashboard')
                    </h2>
                    <p class="text-sm text-gray-600">
                        @yield('page-subtitle', 'Welcome back!')
                    </p>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Current Time -->
                    <div class="hidden md:block text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="current-time">{{ now()->format('H:i') }}</span>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button onclick="toggleUserDropdown()" 
                                class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-lg transition">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->full_name ?: Auth::user()->username, 0, 2)) }}
                            </div>
                            <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown" 
                             class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-8">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                    <p class="text-yellow-800 font-semibold">{{ session('warning') }}</p>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-6 px-8 mt-8">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} Project Management System. All rights reserved.</p>
                <p>Developer Dashboard v1.0</p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarTitle = document.getElementById('sidebar-title');
            const userInfo = document.getElementById('user-info');
            
            sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('sidebar-expanded');
            
            if (sidebar.classList.contains('sidebar-collapsed')) {
                mainContent.classList.remove('ml-[280px]');
                mainContent.classList.add('ml-[80px]');
                sidebarTitle.classList.add('hidden');
                userInfo.classList.add('hidden');
                
                // Hide menu text
                for (let i = 1; i <= 7; i++) {
                    const menuText = document.getElementById(`menu-text-${i}`);
                    if (menuText) menuText.classList.add('hidden');
                }
            } else {
                mainContent.classList.remove('ml-[80px]');
                mainContent.classList.add('ml-[280px]');
                sidebarTitle.classList.remove('hidden');
                userInfo.classList.remove('hidden');
                
                // Show menu text
                for (let i = 1; i <= 7; i++) {
                    const menuText = document.getElementById(`menu-text-${i}`);
                    if (menuText) menuText.classList.remove('hidden');
                }
            }
        }

        // User Dropdown Toggle
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            const button = event.target.closest('button');
            
            if (!button || button.getAttribute('onclick') !== 'toggleUserDropdown()') {
                dropdown.classList.add('hidden');
            }
        });

        // Update current time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}`;
        }

        setInterval(updateTime, 60000);
        updateTime();

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"], [class*="bg-yellow-50"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
