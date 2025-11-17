<!-- resources/views/components/modern-sidebar.blade.php -->
<aside class="w-72 bg-white border-r border-gray-200 flex flex-col shadow-sm">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                <i class="fas fa-tasks text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">TaskMaster.</h1>
            </div>
        </div>
    </div>

    <!-- Menu Header -->
    <div class="px-6 pt-6 pb-3">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">MENU</p>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 overflow-y-auto">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700
                          {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home text-lg w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Messages with Badge -->
            <li>
                <a href="#"
                   class="menu-item flex items-center justify-between px-4 py-3 rounded-xl text-gray-700">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-lg w-5"></i>
                        <span class="font-medium">Messages</span>
                    </div>
                    <span class="badge bg-red-500 text-white">46</span>
                </a>
            </li>

            <!-- My Tasks -->
            <li>
                <a href="{{ route('admin.projects.index') }}"
                   class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700
                          {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                    <i class="fas fa-check-square text-lg w-5"></i>
                    <span class="font-medium">My Tasks</span>
                </a>
            </li>

            <!-- Friends -->
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700
                          {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users text-lg w-5"></i>
                    <span class="font-medium">Friends</span>
                </a>
            </li>

            <!-- Calendar with Badge -->
            <li>
                <a href="#"
                   class="menu-item flex items-center justify-between px-4 py-3 rounded-xl text-gray-700">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar text-lg w-5"></i>
                        <span class="font-medium">Calendar</span>
                    </div>
                    <span class="badge bg-orange-500 text-white">+2</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Profile Section -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
            <div class="relative">
                <img src="https://i.pravatar.cc/150?img=33" alt="User" class="w-10 h-10 rounded-full">
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->full_name ?? Auth::user()->username }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
            </div>
            <i class="fas fa-ellipsis-v text-gray-400"></i>
        </div>
    </div>

    <!-- Team Members -->
    <div class="px-6 pb-6">
        <div class="flex items-center space-x-2">
            @for($i = 1; $i <= 4; $i++)
                <img src="https://i.pravatar.cc/150?img={{ $i }}"
                     alt="Team Member"
                     class="w-8 h-8 rounded-full border-2 border-white -ml-2 first:ml-0 hover:scale-110 transition-transform cursor-pointer">
            @endfor
            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-300 transition">
                10+
            </div>
        </div>
    </div>

    <!-- Mood Tracker Section -->
    <div class="px-6 pb-6">
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-700">Michie 👋</p>
                <span class="text-xs text-gray-500">+2</span>
            </div>
            <div class="space-y-2">
                <div class="flex items-start space-x-2">
                    <span class="text-xs text-gray-600">☀️</span>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-700">Morning 👋</p>
                        <p class="text-xs text-gray-500">12:49</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3">
                    <p class="text-xs text-gray-700 mb-2">Today we will move on to the wireframe process</p>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">12:50</p>
                        <p class="text-xs text-green-600 flex items-center">
                            <i class="fas fa-check mr-1"></i> Okay Michie 👍
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 text-right mt-1">13:00</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition">
                <i class="fas fa-sign-out-alt text-lg w-5"></i>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>
