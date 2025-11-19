<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Management</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        /* Professional Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* NEW: Text Gradient Animation */
        @keyframes textGradient {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        /* NEW: Floating Animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* NEW: Shimmer Effect */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        /* NEW: Glow Pulse */
        @keyframes glowPulse {
            0%, 100% {
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(99, 102, 241, 0.6);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .scale-in {
            animation: scaleIn 0.4s ease-out;
        }

        /* NEW: Animated Gradient Text */
        .animated-gradient-text {
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #667eea);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textGradient 3s ease infinite;
        }

        /* NEW: Enhanced Header Title */
        .header-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textGradient 4s ease infinite;
            position: relative;
            display: inline-block;
        }

        .header-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
            border-radius: 10px;
            transform: scaleX(0);
            transform-origin: left;
            animation: underlineGrow 2s ease-in-out infinite;
        }

        @keyframes underlineGrow {
            0%, 100% {
                transform: scaleX(0);
            }
            50% {
                transform: scaleX(1);
            }
        }

        /* Project Card Hover Effects - ENHANCED */
        .project-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .project-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            padding: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .project-card:hover::before {
            opacity: 1;
        }

        .project-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.3);
        }

        .project-card:hover .project-overlay {
            opacity: 1;
        }

        /* NEW: Project Name Animation */
        .project-name {
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
        }

        .project-card:hover .project-name {
            transform: scale(1.05);
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        /* Gradient Backgrounds - ENHANCED */
        .gradient-1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-4 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-5 {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-6 {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            position: relative;
            overflow: hidden;
        }

        /* NEW: Gradient Shine Effect */
        .gradient-1::before,
        .gradient-2::before,
        .gradient-3::before,
        .gradient-4::before,
        .gradient-5::before,
        .gradient-6::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.3) 50%,
                transparent 70%
            );
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        /* Progress Bar - ENHANCED */
        .progress-bar {
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            animation: shimmer 2s infinite;
        }

        /* Glassmorphism Effect - ENHANCED */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Custom Scrollbar - ENHANCED */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: linear-gradient(180deg, #f1f5f9, #e2e8f0);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #f093fb 100%);
        }

        /* Status Badge - ENHANCED */
        .status-badge {
            font-size: 10px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .status-badge:hover::before {
            width: 200px;
            height: 200px;
        }

        /* Tab Active - ENHANCED */
        .tab-active {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            position: relative;
        }

        .tab-active::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            animation: glowPulse 2s ease-in-out infinite;
        }

        /* Clock Animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .clock-separator {
            animation: pulse 1s ease-in-out infinite;
        }

        /* NEW: Stats Card Enhanced Hover */
        .stats-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .stats-card:hover::before {
            opacity: 1;
            animation: float 3s ease-in-out infinite;
        }

        .stats-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        /* NEW: Button Ripple Effect */
        .btn-ripple {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-ripple:hover::after {
            width: 300px;
            height: 300px;
        }

        /* NEW: Icon Bounce */
        @keyframes iconBounce {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            25% {
                transform: translateY(-8px) rotate(-5deg);
            }
            75% {
                transform: translateY(-4px) rotate(5deg);
            }
        }

        .icon-bounce:hover i {
            animation: iconBounce 0.6s ease;
        }

        /* NEW: Search Bar Enhancement */
        #searchProject {
            transition: all 0.3s ease;
        }

        #searchProject:focus {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }

        /* NEW: Modal Scale Animation */
        @keyframes modalScale {
            0% {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .scale-in {
            animation: modalScale 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* NEW: Team Avatar Hover */
        .team-avatar {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .team-avatar:hover {
            transform: translateY(-5px) rotate(5deg) scale(1.15);
            z-index: 10;
        }

        /* NEW: Deadline Badge Pulse */
        .deadline-badge {
            animation: deadlinePulse 2s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.admin-sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <header class="glass-effect sticky top-0 z-40 shadow-sm">
                <div class="flex items-center justify-between px-8 py-4">
                    <!-- Left: Page Title - ANIMATED -->
                    <div class="slide-in">
                        <h1 class="text-3xl font-black header-title">
                            Dashboard
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">Welcome back, let's get productive! 🚀</p>
                    </div>

                    <!-- Right: Clock & User Menu -->
                    <div class="flex items-center space-x-6">
                        <!-- Professional Clock -->
                        <div x-data="professionalClock()" class="hidden lg:flex items-center space-x-4 bg-white px-5 py-3 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <!-- Date Section -->
                            <div class="flex items-center space-x-3 border-r border-gray-200 pr-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md icon-bounce">
                                    <i class="fas fa-calendar-day text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Today</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="currentDate"></p>
                                </div>
                            </div>

                            <!-- Time Section -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center shadow-md icon-bounce">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Current Time</p>
                                    <p class="text-xl font-black text-gray-900 tracking-tight tabular-nums" x-text="currentTime"></p>
                                </div>
                            </div>
                        </div>

                        {{--  <!-- Notifications -->
                        <button class="relative p-2.5 text-gray-600 hover:bg-white rounded-lg transition shadow-sm icon-bounce">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping"></span>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                        </button>  --}}

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center space-x-3 p-2 hover:bg-white rounded-lg transition">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-md team-avatar">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ Auth::user()->full_name ?: Auth::user()->username }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
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
                                            class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center">
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
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-white border-l-4 border-green-500 rounded-lg shadow-sm px-6 py-4 mb-8 slide-in">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Success!</p>
                                <p class="text-sm text-gray-600">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-sm px-6 py-4 mb-8 slide-in">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-circle text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Error!</p>
                                <p class="text-sm text-gray-600">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Stats Cards - ENHANCED with animations -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                    <!-- Total Projects - Biru -->
                    <div class="stats-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-md p-5 text-white fade-in-up">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-xs text-blue-100 font-semibold mb-2 uppercase tracking-wide">Total Projects</p>
                                <h3 class="text-4xl font-black mb-3 leading-none">{{ $projects->count() }}</h3>
                                <div class="flex items-center text-xs text-blue-100">
                                    <i class="fas fa-arrow-up mr-1.5 text-xs"></i>
                                    <span>12% from last month</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 icon-bounce">
                                <i class="fas fa-folder text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Active Tasks - Orange -->
                    <div class="stats-card bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-md p-5 text-white fade-in-up" style="animation-delay: 0.1s;">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-xs text-orange-100 font-semibold mb-2 uppercase tracking-wide">Active Tasks</p>
                                <h3 class="text-4xl font-black mb-3 leading-none">
                                    {{ $projects->sum(function($project) {
                                        return $project->boards->sum(function($board) {
                                            return $board->cards->whereIn('status', ['todo', 'in_progress', 'review'])->count();
                                        });
                                    }) }}
                                </h3>
                                <div class="flex items-center text-xs text-orange-100">
                                    <i class="fas fa-clock mr-1.5 text-xs"></i>
                                    <span>8 tasks due today</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 icon-bounce">
                                <i class="fas fa-list-check text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Team Members - Hijau -->
                    <div class="stats-card bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-md p-5 text-white fade-in-up cursor-pointer"
                        style="animation-delay: 0.2s;"
                        x-data="{ showAllUsers: false }"
                        @click="showAllUsers = true">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-xs text-emerald-100 font-semibold mb-2 uppercase tracking-wide">Team Members</p>
                                <h3 class="text-4xl font-black mb-3 leading-none">
                                    {{ $allUsers->count() }}
                                </h3>
                                <div class="flex items-center text-xs text-emerald-100">
                                    <i class="fas fa-users mr-1.5 text-xs"></i>
                                    <span>Click to view all</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 icon-bounce">
                                <i class="fas fa-user-group text-2xl"></i>
                            </div>
                        </div>

                        <!-- Modal Team Members -->
                        <template x-teleport="body">
                            <div x-show="showAllUsers"
                                @click="showAllUsers = false"
                                x-transition
                                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] flex items-center justify-center p-4"
                                style="display: none;">
                                <div @click.stop
                                    class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] overflow-hidden scale-in">

                                    <!-- Header -->
                                    <div class="sticky top-0 bg-gradient-to-r from-emerald-500 to-green-600 px-8 py-6 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-2xl font-bold text-white">Team Members</h3>
                                            <p class="text-sm text-white/80 mt-1">Total {{ $allUsers->count() }} members</p>
                                        </div>
                                        <button @click="showAllUsers = false" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-lg transition">
                                            <i class="fas fa-times text-xl"></i>
                                        </button>
                                    </div>

                                    <!-- Members List -->
                                    <div class="p-8 overflow-auto max-h-[calc(85vh-100px)]">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($allUsers as $member)
                                            <div class="flex items-center space-x-4 p-5 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl hover:shadow-md transition border border-gray-100">

                                                <!-- Avatar -->
                                                <div class="flex-shrink-0">
                                                    @if($member->avatar)
                                                        <img src="{{ $member->avatar_url }}"
                                                            alt="{{ $member->full_name ?: $member->username }}"
                                                            class="w-16 h-16 rounded-xl object-cover shadow-lg">
                                                    @else
                                                        <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-lg text-white text-xl font-bold
                                                            {{ $loop->index % 6 == 0 ? 'bg-gradient-to-br from-purple-400 to-purple-600' : '' }}
                                                            {{ $loop->index % 6 == 1 ? 'bg-gradient-to-br from-pink-400 to-pink-600' : '' }}
                                                            {{ $loop->index % 6 == 2 ? 'bg-gradient-to-br from-blue-400 to-blue-600' : '' }}
                                                            {{ $loop->index % 6 == 3 ? 'bg-gradient-to-br from-orange-400 to-orange-600' : '' }}
                                                            {{ $loop->index % 6 == 4 ? 'bg-gradient-to-br from-teal-400 to-teal-600' : '' }}
                                                            {{ $loop->index % 6 == 5 ? 'bg-gradient-to-br from-green-400 to-green-600' : '' }}">
                                                            {{ strtoupper(substr($member->full_name ?: $member->username, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Info -->
                                                <div class="flex-1 min-w-0">
                                                    <!-- Nama -->
                                                    <h4 class="font-bold text-gray-900 truncate text-base">
                                                        {{ $member->full_name ?: $member->username }}
                                                    </h4>

                                                    <!-- Email -->
                                                    <p class="text-sm text-gray-600 truncate mt-1 flex items-center">
                                                        <i class="fas fa-envelope text-gray-400 mr-1.5 text-xs"></i>
                                                        <span>{{ $member->email }}</span>
                                                    </p>

                                                    <!-- Phone (optional) -->
                                                    @if($member->phone)
                                                    <p class="text-xs text-gray-500 truncate mt-0.5 flex items-center">
                                                        <i class="fas fa-phone text-gray-400 mr-1.5 text-xs"></i>
                                                        <span>{{ $member->phone }}</span>
                                                    </p>
                                                    @endif

                                                    <!-- Role Badge -->
                                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold mt-2
                                                        {{ $member->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                                        {{ $member->role === 'super_admin' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                        {{ $member->role === 'teamlead' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $member->role === 'developer' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $member->role === 'designer' ? 'bg-pink-100 text-pink-700' : '' }}
                                                        {{ $member->role === 'member' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                        <i class="fas {{ in_array($member->role, ['admin', 'super_admin']) ? 'fa-user-shield' : 'fa-user' }} mr-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Completion Rate - Ungu/Magenta -->
                    <div class="stats-card bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-md p-5 text-white fade-in-up" style="animation-delay: 0.3s;">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-xs text-purple-100 font-semibold mb-2 uppercase tracking-wide">Completed</p>
                                @php
                                    $allTasks = $projects->sum(function($project) {
                                        return $project->boards->sum(function($board) {
                                            return $board->cards->count();
                                        });
                                    });
                                    $completedTasksCount = $projects->sum(function($project) {
                                        return $project->boards->sum(function($board) {
                                            return $board->cards->where('status', 'done')->count();
                                        });
                                    });
                                    $rate = $allTasks > 0 ? round(($completedTasksCount / $allTasks) * 100) : 0;
                                @endphp
                                <h3 class="text-4xl font-black mb-3 leading-none">{{ $rate }}%</h3>
                                <div class="flex items-center text-xs text-purple-100">
                                    <i class="fas fa-chart-line mr-1.5 text-xs"></i>
                                    <span>Great progress!</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 icon-bounce">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Section -->
                <div class="mb-8">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-black animated-gradient-text">Your Projects</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $projects->count() }} active projects</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Search - ENHANCED -->
                            <div class="relative">
                                <input type="text"
                                    id="searchProject"
                                    placeholder="Search projects..."
                                    class="w-72 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                <i class="fas fa-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>

                            <!-- New Project Button - ENHANCED -->
                            <a href="{{ route('admin.projects.create') }}"
                             class="btn-ripple flex items-center space-x-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 hover:shadow-lg transition font-medium text-sm">
                            <i class="fas fa-plus"></i>
                            <span>New Project</span>
                            </a>
                        </div>
                    </div>

                    <!-- Projects Grid with Enhanced Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($projects as $index => $project)
                        <div class="project-card bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 fade-in-up"
                             style="animation-delay: {{ 0.1 + ($index * 0.05) }}s;"
                             x-data="{ showMembers: false, showMenu: false, showBoards: false }">

                            <!-- Project Header with Gradient -->
                            <div class="relative h-48 gradient-{{ ($index % 6) + 1 }} overflow-hidden group">
                                @if($project->thumbnail)
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}"
                                         alt="{{ $project->project_name }}"
                                         class="w-full h-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <i class="fas fa-project-diagram text-white/20 text-6xl"></i>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                @endif

                                <!-- Project Name & Stats -->
                                <div class="absolute bottom-0 left-0 right-0 p-5">
                                    <h3 class="font-black text-white text-xl project-name mb-2 line-clamp-1">
                                        {{ $project->project_name }}
                                    </h3>
                                    <div class="flex items-center space-x-4 text-white/90 text-xs">
                                        <span class="flex items-center">
                                            <i class="fas fa-columns mr-1.5"></i>
                                            {{ $project->boards->count() }} Boards
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-tasks mr-1.5"></i>
                                            {{ $project->boards->sum(fn($b) => $b->cards->count()) }} Tasks
                                        </span>
                                    </div>
                                </div>

                                <!-- Menu Button (Top Right) -->
                                <div class="absolute top-4 right-4">
                                    <button @click="showMenu = !showMenu"
                                            class="w-9 h-9 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl flex items-center justify-center text-white transition shadow-lg icon-bounce">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="showMenu"
                                         @click.away="showMenu = false"
                                         x-transition
                                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-30"
                                         style="display: none;">
                                        <a href="{{ route('admin.projects.show', $project) }}"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <i class="fas fa-eye w-5 text-gray-400"></i>
                                            <span class="ml-3 font-medium">View Details</span>
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('panel.teamlead.index', $project) }}"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <i class="fas fa-columns w-5 text-gray-400"></i>
                                            <span class="ml-3 font-medium">View Panel</span>
                                        </a>
                                        @elseif(Auth::user()->role === 'teamlead')
                                        <a href="{{ route('panel.teamlead.index', $project) }}"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <i class="fas fa-columns w-5 text-gray-400"></i>
                                            <span class="ml-3 font-medium">View Panel</span>
                                        </a>
                                        @else
                                        <a href="{{ route('panel.member.index', $project) }}"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <i class="fas fa-columns w-5 text-gray-400"></i>
                                            <span class="ml-3 font-medium">View Panel</span>
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.projects.edit', $project) }}"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <i class="fas fa-edit w-5 text-gray-400"></i>
                                            <span class="ml-3 font-medium">Edit Project</span>
                                        </a>
                                        <div class="my-2 border-t border-gray-100"></div>
                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition"
                                                    onclick="return confirm('Delete this project permanently?')">
                                                <i class="fas fa-trash w-5"></i>
                                                <span class="ml-3 font-medium">Delete Project</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-6">
                                <!-- Description -->
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">
                                    {{ $project->description ?: 'No description provided.' }}
                                </p>

                                <!-- GitHub Link -->
                                @if($project->github_link)
                                <a href="{{ $project->github_link }}"
                                   target="_blank"
                                   class="inline-flex items-center space-x-2 px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-xs font-medium transition group mb-5">
                                    <i class="fab fa-github text-base group-hover:scale-110 transition-transform"></i>
                                    <span>View Repository</span>
                                    <i class="fas fa-external-link-alt text-xs opacity-70"></i>
                                </a>
                                @endif

                                <!-- Progress Section - ENHANCED -->
                                @php
                                    $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                                    $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                                    $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                                @endphp
                                <div class="mb-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Progress</span>
                                        <span class="text-sm font-black text-indigo-600">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="gradient-{{ ($index % 6) + 1 }} h-2.5 rounded-full progress-bar shadow-sm" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                        <span>{{ $doneCards }} completed</span>
                                        <span>{{ $totalCards }} total tasks</span>
                                    </div>
                                </div>

                                <!-- View Boards Button - ENHANCED -->
                                <div class="mb-5">
                                    <button @click="showBoards = true"
                                            class="btn-ripple w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition font-semibold text-sm group">
                                        <i class="fas fa-th-large group-hover:scale-110 transition-transform"></i>
                                        <span>View Boards</span>
                                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>

                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-5 border-t border-gray-100">
                                    <!-- Team Avatars - ENHANCED -->
                                    <button @click="showMembers = true"
                                            type="button"
                                            class="flex -space-x-2.5 hover:opacity-80 transition">
                                        @foreach($project->members->take(4) as $member)
                                        <div class="w-9 h-9 gradient-{{ ($loop->index % 6) + 1 }} rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold shadow-md team-avatar"
                                             title="{{ $member->full_name }}">
                                            {{ strtoupper(substr($member->full_name ?: $member->username, 0, 1)) }}
                                        </div>
                                        @endforeach
                                        @if($project->members->count() > 4)
                                        <div class="w-9 h-9 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center shadow-md team-avatar">
                                            <span class="text-xs font-bold text-gray-600">+{{ $project->members->count() - 4 }}</span>
                                        </div>
                                        @endif
                                    </button>

                                    <!-- Deadline - ENHANCED -->
                                    @if($project->deadline)
                                    <div class="flex items-center space-x-2 text-xs deadline-badge">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center icon-bounce">
                                            <i class="far fa-clock text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium">Due date</p>
                                            <p class="text-gray-900 font-bold">{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Boards Modal -->
                            <template x-teleport="body">
                                <div x-show="showBoards"
                                     @click="showBoards = false"
                                     x-transition
                                     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] flex items-center justify-center p-4"
                                     style="display: none;">
                                    <div @click.stop
                                         class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] overflow-hidden scale-in">
                                        <div class="sticky top-0 gradient-{{ ($index % 6) + 1 }} px-8 py-6 flex items-center justify-between">
                                            <div>
                                                <h3 class="text-2xl font-bold text-white">Boards</h3>
                                                <p class="text-sm text-white/80 mt-1">{{ $project->project_name }}</p>
                                            </div>
                                            <button @click="showBoards = false" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-lg transition icon-bounce">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        <div class="p-8 overflow-auto max-h-[calc(85vh-100px)]">
                                            @if($project->boards->count() > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($project->boards as $board)
                                                <a href="{{ route('admin.boards.show', ['project' => $project, 'board' => $board]) }}"
                                                   class="block p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition border border-gray-200 group">
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div class="flex-1">
                                                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition mb-2">
                                                                <i class="fas fa-columns mr-2 text-indigo-600"></i>
                                                                {{ $board->board_name }}
                                                            </h4>
                                                            <div class="flex items-center space-x-4 text-xs text-gray-600">
                                                                @php
                                                                    $boardCards = $board->cards;
                                                                    $todoCount = $boardCards->where('status', 'todo')->count();
                                                                    $inProgressCount = $boardCards->where('status', 'in_progress')->count();
                                                                    $reviewCount = $boardCards->where('status', 'review')->count();
                                                                    $doneCount = $boardCards->where('status', 'done')->count();
                                                                @endphp
                                                                <span class="flex items-center">
                                                                    <div class="w-2 h-2 bg-gray-500 rounded-full mr-1.5"></div>
                                                                    {{ $todoCount }} To Do
                                                                </span>
                                                                <span class="flex items-center">
                                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-1.5"></div>
                                                                    {{ $inProgressCount }} In Progress
                                                                </span>
                                                            </div>
                                                            <div class="flex items-center space-x-4 text-xs text-gray-600 mt-1">
                                                                <span class="flex items-center">
                                                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-1.5"></div>
                                                                    {{ $reviewCount }} Review
                                                                </span>
                                                                <span class="flex items-center">
                                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></div>
                                                                    {{ $doneCount }} Done
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 transition icon-bounce">
                                                            <i class="fas fa-arrow-right text-indigo-600 group-hover:text-white transition"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Progress Bar per Board -->
                                                    @php
                                                        $boardTotal = $boardCards->count();
                                                        $boardDone = $doneCount;
                                                        $boardProgress = $boardTotal > 0 ? round(($boardDone / $boardTotal) * 100) : 0;
                                                    @endphp
                                                    <div class="mt-4">
                                                        <div class="flex items-center justify-between mb-1.5">
                                                            <span class="text-xs font-semibold text-gray-600">Progress</span>
                                                            <span class="text-xs font-bold text-indigo-600">{{ $boardProgress }}%</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2 rounded-full progress-bar transition-all" style="width: {{ $boardProgress }}%"></div>
                                                        </div>
                                                    </div>
                                                </a>
                                                @endforeach
                                            </div>
                                            @else
                                            <div class="text-center py-12">
                                                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-columns text-gray-400 text-3xl"></i>
                                                </div>
                                                <h4 class="text-lg font-bold text-gray-900 mb-2">No boards yet</h4>
                                                <p class="text-sm text-gray-500 mb-6">Create your first board to get started</p>
                                                <a href="{{ route('admin.projects.show', $project) }}"
                                                   class="btn-ripple inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition font-medium text-sm">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Create Board
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Members Modal -->
                            <template x-teleport="body">
                                <div x-show="showMembers"
                                     @click="showMembers = false"
                                     x-transition
                                     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] flex items-center justify-center p-4"
                                     style="display: none;">
                                    <div @click.stop
                                         class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden scale-in">
                                        <div class="sticky top-0 gradient-{{ ($index % 6) + 1 }} px-8 py-6 flex items-center justify-between">
                                            <div>
                                                <h3 class="text-xl font-bold text-white">Team Members</h3>
                                                <p class="text-sm text-white/80 mt-1">{{ $project->project_name }}</p>
                                            </div>
                                            <button @click="showMembers = false" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-lg transition icon-bounce">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </div>
                                        <div class="p-8 overflow-auto max-h-[calc(85vh-100px)]">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($project->members as $member)
                                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                                    <div class="w-14 h-14 gradient-{{ ($loop->index % 6) + 1 }} rounded-xl flex items-center justify-center text-white text-lg font-bold shadow-lg team-avatar">
                                                        {{ strtoupper(substr($member->full_name ?: $member->username, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-bold text-gray-900 truncate">{{ $member->full_name ?: $member->username }}</h4>
                                                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $member->email }}</p>
                                                        <span class="status-badge {{ $member->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} mt-2 inline-block">
                                                            {{ ucfirst($member->role) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @empty
                        <div class="col-span-3">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 icon-bounce">
                                    <i class="fas fa-folder-open text-gray-400 text-4xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3 animated-gradient-text">No projects yet</h3>
                                <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">
                                    Get started by creating your first project and start collaborating with your team!
                                </p>
                                <a href="{{ route('admin.projects.create') }}"
                                   class="btn-ripple inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition font-medium">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create Your First Project
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Professional Clock Function
        function professionalClock() {
            return {
                currentTime: '',
                currentDate: '',

                init() {
                    this.updateClock();
                    setInterval(() => {
                        this.updateClock();
                    }, 1000);
                },

                updateClock() {
                    const now = new Date();

                    // Format time (HH:MM:SS)
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    this.currentTime = `${hours}:${minutes}:${seconds}`;

                    // Format date (Day, DD MMM YYYY)
                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                    const dayName = days[now.getDay()];
                    const day = now.getDate();
                    const month = months[now.getMonth()];
                    const year = now.getFullYear();

                    this.currentDate = `${dayName}, ${day} ${month} ${year}`;
                }
            }
        }

        // Search Functionality
        document.getElementById('searchProject')?.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const projectCards = document.querySelectorAll('.project-card');

            projectCards.forEach(card => {
                const projectName = card.querySelector('.project-name')?.textContent.toLowerCase() || '';
                const projectDesc = card.querySelector('p')?.textContent.toLowerCase() || '';
                const isMatch = projectName.includes(searchValue) || projectDesc.includes(searchValue);

                card.style.display = isMatch ? '' : 'none';
            });
        });
    </script>
</body>
</html>
