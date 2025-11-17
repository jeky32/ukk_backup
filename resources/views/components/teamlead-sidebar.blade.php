<!-- resources/views/components/teamlead-sidebar.blade.php -->
<aside x-data="{
           collapsed: false,
           activeTime: '00:00:00',
           activeDate: '',
           greeting: 'Good Morning',
           hours: 0,
           minutes: 0,
           seconds: 0
       }"
       :class="collapsed ? 'w-20' : 'w-64'"
       class="bg-gradient-to-b from-blue-600 via-teal-600 to-blue-700 dark:from-gray-800 dark:via-gray-900 dark:to-gray-950 shadow-2xl min-h-screen flex flex-col relative overflow-hidden transition-all duration-300 ease-in-out"
       x-init="
           setInterval(() => {
               const now = new Date();

               @if(Auth::check() && Auth::user()->getSettings())
                   const userTimezone = '{{ Auth::user()->getSettings()->timezone ?? 'Asia/Jakarta' }}';
                   const nowUserTZ = new Date(now.toLocaleString('en-US', { timeZone: userTimezone }));
                   hours = nowUserTZ.getHours();
                   minutes = nowUserTZ.getMinutes();
                   seconds = nowUserTZ.getSeconds();
               @else
                   hours = now.getHours();
                   minutes = now.getMinutes();
                   seconds = now.getSeconds();
               @endif

               activeTime = hours.toString().padStart(2, '0') + ':' +
                           minutes.toString().padStart(2, '0') + ':' +
                           seconds.toString().padStart(2, '0');
               activeDate = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });

               if (hours < 12) greeting = 'Good Morning';
               else if (hours < 18) greeting = 'Good Afternoon';
               else greeting = 'Good Evening';
           }, 1000);
       ">

    <!-- Animated background elements -->
    <div class="absolute top-0 left-0 w-full h-full opacity-20 dark:opacity-10 pointer-events-none">
        <div class="absolute top-10 -left-10 w-40 h-40 bg-cyan-400 dark:bg-blue-500 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 -right-10 w-40 h-40 bg-blue-400 dark:bg-indigo-500 rounded-full blur-3xl animate-float-delayed"></div>
    </div>

    <!-- EXPANDED STATE -->
    <div class="flex flex-col h-screen w-full">
        <!-- Logo Section -->
        <div class="relative p-6 border-b border-white/20 dark:border-gray-800">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 dark:bg-white/10 backdrop-blur-xl rounded-lg flex items-center justify-center shadow-lg border border-white/30 dark:border-gray-700">
                    <span class="text-white font-bold text-lg">TL</span>
                </div>
                <div x-show="!collapsed" x-transition>
                    <h1 class="text-lg font-bold text-white dark:text-gray-100">Team Lead</h1>
                    <p class="text-xs text-blue-100 dark:text-gray-400">Management Panel</p>
                </div>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="relative p-4 mx-4 mt-4 bg-white/10 dark:bg-white/5 backdrop-blur-xl rounded-xl shadow-lg border border-white/20 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-white/30 dark:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center ring-2 ring-white/40 dark:ring-gray-600 shadow-lg">
                        <span class="text-white dark:text-gray-200 font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                        </span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 dark:bg-green-500 rounded-full border-2 border-blue-600 dark:border-gray-800"></div>
                </div>
                <div x-show="!collapsed" x-transition class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white dark:text-gray-100 truncate">
                        {{ Auth::user()->full_name ?: Auth::user()->username }}
                    </p>
                    <p class="text-xs text-blue-100 dark:text-gray-400 capitalize flex items-center">
                        <span class="w-2 h-2 bg-green-400 dark:bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                        Team Lead
                    </p>
                </div>
            </div>
        </div>

        <!-- CLOCK WIDGET -->
        <div class="relative px-4 pt-4">
            <div class="bg-white/10 dark:bg-white/5 backdrop-blur-xl rounded-xl p-3 shadow-lg border border-white/20 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sun text-yellow-300 dark:text-yellow-400 text-sm" x-show="hours >= 5 && hours < 12"></i>
                        <i class="fas fa-cloud-sun text-orange-300 dark:text-orange-400 text-sm" x-show="hours >= 12 && hours < 18"></i>
                        <i class="fas fa-moon text-blue-200 dark:text-blue-400 text-sm" x-show="hours >= 18 || hours < 5"></i>
                        <h3 class="text-xs font-bold text-white dark:text-gray-200" x-text="greeting"></h3>
                    </div>
                    <span class="px-2 py-0.5 bg-white/10 dark:bg-white/5 text-white dark:text-gray-200 text-xs font-bold rounded-full border border-white/20 dark:border-gray-700" x-text="hours >= 12 ? 'PM' : 'AM'"></span>
                </div>

                <div class="flex items-center justify-center space-x-1 mb-2">
                    <div class="text-2xl font-bold text-white dark:text-gray-100 font-mono tabular-nums drop-shadow" x-text="hours.toString().padStart(2, '0')"></div>
                    <div class="text-2xl font-bold text-white/70 dark:text-gray-400">:</div>
                    <div class="text-2xl font-bold text-white dark:text-gray-100 font-mono tabular-nums drop-shadow" x-text="minutes.toString().padStart(2, '0')"></div>
                    <div class="text-2xl font-bold text-white/70 dark:text-gray-400">:</div>
                    <div class="text-xl font-bold text-cyan-300 dark:text-cyan-400 font-mono tabular-nums drop-shadow" x-text="seconds.toString().padStart(2, '0')"></div>
                </div>

                <p class="text-xs text-white/60 dark:text-gray-400 text-center" x-text="activeDate"></p>

                @if(Auth::check() && Auth::user()->getSettings())
                    <p class="text-xs text-white/50 dark:text-gray-500 text-center mt-1">
                        {{ Auth::user()->getSettings()->timezone ?? 'Asia/Jakarta' }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="relative px-4 pt-4">
            <div class="grid grid-cols-2 gap-3">
                <!-- My Projects -->
                <div class="relative bg-gradient-to-br from-cyan-500/20 to-blue-500/20 dark:from-blue-600/20 dark:to-indigo-600/20 backdrop-blur-xl rounded-lg p-3 border border-cyan-500/30 dark:border-gray-700 transform transition-all duration-300 hover:-translate-y-1 cursor-pointer overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-500 dark:from-blue-500 dark:to-indigo-600 rounded-lg flex items-center justify-center shadow-lg transition-transform group-hover:rotate-12 duration-300">
                                <i class="fas fa-folder-open text-white text-sm"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-bold bg-gradient-to-r from-white to-cyan-200 dark:from-gray-100 dark:to-cyan-300 bg-clip-text text-transparent mb-1">
                            {{ Auth::user()->projects()->count() }}
                        </p>
                        <p class="text-xs text-cyan-300 dark:text-cyan-400 font-semibold">My Projects</p>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="relative bg-gradient-to-br from-blue-500/20 to-teal-500/20 dark:from-indigo-600/20 dark:to-teal-600/20 backdrop-blur-xl rounded-lg p-3 border border-blue-500/30 dark:border-gray-700 transform transition-all duration-300 hover:-translate-y-1 cursor-pointer overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-teal-500 dark:from-indigo-500 dark:to-teal-600 rounded-lg flex items-center justify-center shadow-lg transition-transform group-hover:rotate-12 duration-300">
                                <i class="fas fa-user-friends text-white text-sm"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-bold bg-gradient-to-r from-white to-blue-200 dark:from-gray-100 dark:to-blue-300 bg-clip-text text-transparent mb-1">
                            {{ Auth::user()->projects()->get()->pluck('members')->flatten()->unique('id')->count() }}
                        </p>
                        <p class="text-xs text-blue-300 dark:text-blue-400 font-semibold">Team Members</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu - 6 MENU TEAM LEAD (+ Dashboard) -->
        <nav class="relative flex-1 p-4 pt-6 overflow-y-auto scrollbar-thin scrollbar-thumb-white/20 dark:scrollbar-thumb-gray-700">
            <ul class="space-y-2">
                <!-- ✅ DASHBOARD (BARU) -->
                <li>
                    <a href="{{ route('teamlead.dashboard') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                              {{ request()->routeIs('teamlead.dashboard')
                                 ? 'bg-gradient-to-r from-purple-500 via-blue-500 to-teal-400 text-white shadow-lg'
                                 : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-th-large text-lg w-5 text-center"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Dashboard</span>
                        @if(request()->routeIs('teamlead.dashboard'))
                            <span class="absolute right-3 w-2 h-2 bg-white rounded-full animate-ping"></span>
                        @endif
                    </a>
                </li>

                <!-- My Projects -->
                <li>
                    <a href="{{ route('teamlead.projects.index') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                              {{ request()->routeIs('teamlead.projects.*')
                                 ? 'bg-gradient-to-r from-purple-500 via-blue-500 to-teal-400 text-white shadow-lg'
                                 : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-folder-open text-lg w-5 text-center"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">My Projects</span>
                        @if(request()->routeIs('teamlead.projects.*'))
                            <span class="absolute right-3 w-2 h-2 bg-white rounded-full animate-ping"></span>
                        @endif
                    </a>
                </li>

                <!-- Monitoring -->
                <li>
                    <a href="{{ route('teamlead.monitoring') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                              {{ request()->routeIs('teamlead.monitoring.*')
                                 ? 'bg-gradient-to-r from-purple-500 via-blue-500 to-teal-400 text-white shadow-lg'
                                 : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-chart-line text-lg w-5 text-center"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Monitoring</span>
                        @if(request()->routeIs('teamlead.monitoring.*'))
                            <span class="absolute right-3 w-2 h-2 bg-white rounded-full animate-ping"></span>
                        @endif
                    </a>
                </li>

                <!-- Reports -->
                <li>
                    <a href="{{ route('teamlead.reports.index') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                              {{ request()->routeIs('teamlead.reports*')
                                 ? 'bg-gradient-to-r from-purple-500 via-blue-500 to-teal-400 text-white shadow-lg'
                                 : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-chart-pie text-lg w-5 text-center"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Reports</span>
                        @if(request()->routeIs('teamlead.reports*'))
                            <span class="absolute right-3 w-2 h-2 bg-white rounded-full animate-ping"></span>
                        @endif
                    </a>
                </li>

                    <!-- Messages -->
                    <li>
                        <a href="{{ route('teamlead.messages') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                                {{ request()->routeIs('teamlead.messages') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-comments text-lg w-5 text-center"></i>
                            <span x-show="!collapsed" x-transition class="font-medium">Messages</span>
                            <span x-show="!collapsed" class="absolute right-3 top-3 px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">
                                3
                            </span>
                        </a>
                    </li>


                <!-- Review -->
                <li>
                    <a href="#"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                              text-white/70 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-clipboard-check text-lg w-5 text-center"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Review</span>
                        <span x-show="!collapsed" class="absolute right-3 top-3 px-1.5 py-0.5 bg-orange-500 text-white text-xs font-bold rounded-full">
                            5
                        </span>
                    </a>
                </li>

                 <!-- Riwayat -->
        <li>
            <a href="{{ route('teamlead.riwayat.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 relative
                      {{ request()->routeIs('teamlead.riwayat*')
                         ? 'bg-gradient-to-r from-purple-500 via-blue-500 to-teal-400 text-white shadow-lg'
                         : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-history text-lg w-5 text-center"></i>
                <span x-show="!collapsed" x-transition class="font-medium">Riwayat</span>
                @if(request()->routeIs('teamlead.riwayat*'))
                    <span class="absolute right-3 w-2 h-2 bg-white rounded-full animate-ping"></span>
                @endif
            </a>
        </li>

                <!-- Divider -->
                <li class="py-2">
                    <div class="border-t border-white/20 dark:border-gray-700"></div>
                </li>

                <!-- Logout -->
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 w-full text-left
                                       text-white/70 hover:bg-red-500/30 hover:text-white border border-transparent hover:border-red-400/30">
                            <i class="fas fa-sign-out-alt text-lg w-5 text-center"></i>
                            <span x-show="!collapsed" x-transition class="font-medium">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Footer -->
        <div class="relative p-4 border-t border-white/20 dark:border-gray-800 bg-white/5 dark:bg-white/5 backdrop-blur-xl">
            <p class="text-xs text-center text-blue-100 dark:text-gray-400">© 2025 Team Lead Panel</p>
        </div>
    </div>

    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.6);
        }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.8);
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(20px, -20px); }
            66% { transform: translate(-20px, 20px); }
        }
        .animate-float { animation: float 8s ease-in-out infinite; }
        .animate-float-delayed { animation: float 10s ease-in-out infinite; animation-delay: 2s; }

        * { transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</aside>
