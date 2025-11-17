<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Team Lead Dashboard')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.teamlead-sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <header class="bg-white sticky top-0 z-40 shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-8 py-4">
                    <!-- Left: Page Title -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">@yield('page-subtitle', '')</p>
                    </div>

                    <!-- Right: User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ Auth::user()->full_name ?: Auth::user()->username }}
                                    </p>
                                    <p class="text-xs text-gray-500">Team Lead</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open"
                                 x-transition
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->full_name ?: Auth::user()->username }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
