<!-- resources/views/components/developer-sidebar.blade.php -->
<aside x-data="{
           collapsed: false,
           activeTime: '00:00:00',
           greeting: 'Good Morning',
           hours: 0,
           minutes: 0,
           seconds: 0
       }"
       :class="collapsed ? 'w-20' : 'w-64'"
       class="bg-[#1a1d29] shadow-2xl min-h-screen flex flex-col relative transition-all duration-300 ease-in-out"
       x-init="
           setInterval(() => {
               const now = new Date();
               hours = now.getHours();
               minutes = now.getMinutes();
               seconds = now.getSeconds();

               if (hours < 12) greeting = 'Good Morning';
               else if (hours < 18) greeting = 'Good Afternoon';
               else greeting = 'Good Evening';
           }, 1000);
       ">

    <div class="flex flex-col h-screen w-full">
        <!-- Logo Section -->
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-code text-white text-lg"></i>
                    </div>
                    <div x-show="!collapsed" x-transition>
                        <h1 class="text-base font-bold text-white">DevWorkspace</h1>
                        <p class="text-xs text-gray-400">Developer</p>
                    </div>
                </div>
                <button @click="collapsed = !collapsed"
                        class="w-8 h-8 bg-gray-700/50 hover:bg-gray-600 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas text-gray-300 text-sm" :class="collapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
                </button>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="p-4 mx-4 mt-4 bg-gray-800/50 rounded-xl border border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center ring-2 ring-gray-700 shadow-lg">
                        <span class="text-white font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->username, 0, 2)) }}
                        </span>
                    </div>
                    @if(isset($activeTimeLog) && $activeTimeLog)
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-gray-800 animate-pulse"></div>
                    @else
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-gray-500 rounded-full border-2 border-gray-800"></div>
                    @endif
                </div>
                <div x-show="!collapsed" x-transition class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">
                        {{ Auth::user()->full_name ?: Auth::user()->username }}
                    </p>
                    <p class="text-xs text-gray-400 flex items-center">
                        <i class="fas fa-laptop-code mr-1.5 text-xs"></i>
                        Developer
                    </p>
                </div>
            </div>
        </div>

        <!-- Clock Widget -->
        <div class="px-4 pt-4" x-show="!collapsed" x-transition>
            <div class="bg-gray-800/50 rounded-xl p-3 border border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sun text-yellow-400 text-sm" x-show="hours >= 5 && hours < 12"></i>
                        <i class="fas fa-cloud-sun text-orange-400 text-sm" x-show="hours >= 12 && hours < 18"></i>
                        <i class="fas fa-moon text-blue-400 text-sm" x-show="hours >= 18 || hours < 5"></i>
                        <h3 class="text-xs font-semibold text-gray-300" x-text="greeting"></h3>
                    </div>
                    <span class="px-2 py-0.5 bg-gray-700 text-gray-300 text-xs font-bold rounded border border-gray-600" x-text="hours >= 12 ? 'PM' : 'AM'"></span>
                </div>

                <div class="flex items-center justify-center space-x-1">
                    <div class="text-2xl font-bold text-white font-mono" x-text="hours.toString().padStart(2, '0')"></div>
                    <div class="text-2xl font-bold text-gray-500">:</div>
                    <div class="text-2xl font-bold text-white font-mono" x-text="minutes.toString().padStart(2, '0')"></div>
                    <div class="text-2xl font-bold text-gray-500">:</div>
                    <div class="text-xl font-bold text-blue-400 font-mono" x-text="seconds.toString().padStart(2, '0')"></div>
                </div>

                <!-- Active Timer -->
                @if(isset($activeTimeLog) && $activeTimeLog)
                <div class="border-t border-gray-700 pt-2 mt-2">
                    <div class="flex items-center mb-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-xs text-gray-400">Working on</span>
                    </div>
                    <p class="text-xs text-white font-semibold truncate mb-2">
                        {{ $activeTimeLog->card->card_title ?? 'Task' }}
                    </p>
                    <div class="text-center bg-green-500/10 rounded py-1 border border-green-500/30">
                        <div id="sidebar-timer" class="text-base font-mono font-bold text-green-400">00:00:00</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="px-4 pt-4" x-show="!collapsed" x-transition>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-green-500/10 rounded-lg p-3 border border-green-500/30">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-check-double text-white text-sm"></i>
                    </div>
                    <p class="text-xl font-bold text-white">{{ $completedThisMonth ?? 0 }}</p>
                    <p class="text-xs text-gray-400">Completed</p>
                </div>

                <div class="bg-blue-500/10 rounded-lg p-3 border border-blue-500/30">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($todayTotalHours ?? 0, 1) }}</p>
                    <p class="text-xs text-gray-400">Hours Today</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-4 pt-6 overflow-y-auto">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('developer.dashboard') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all group
                              {{ request()->routeIs('developer.dashboard')
                                 ? 'bg-blue-600 text-white'
                                 : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <i class="fas fa-th-large text-lg w-5"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Dashboard</span>
                    </a>
                </li>

                <!-- ✅ TAMBAHAN: Review Menu -->
                <li>
                    <a href="{{ route('developer.dashboard') }}"
                       onclick="event.preventDefault(); switchTab('review');"
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all relative
                              text-gray-400 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-hourglass-half text-lg w-5"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Review</span>
                        @php
                            $reviewCount = ($myCards ?? collect())->where('status', 'review')->count();
                        @endphp
                        @if($reviewCount > 0)
                        <span x-show="!collapsed" x-transition class="absolute right-3 px-2 py-0.5 bg-yellow-500 text-white text-xs font-bold rounded-full">
                            {{ $reviewCount }}
                        </span>
                        @endif
                    </a>
                </li>

                <!-- Projects -->
                <li>
                    <a href="{{ route('developer.dashboard') }}"
                       onclick="event.preventDefault(); switchTab('projects');"
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all
                              text-gray-400 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-layer-group text-lg w-5"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Projects</span>
                    </a>
                </li>

                <!-- Monitoring (Time Track) -->
                <li>
                    <a href="{{ route('developer.dashboard') }}"
                       onclick="event.preventDefault(); switchTab('timetrack');"
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all
                              text-gray-400 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-chart-line text-lg w-5"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Monitoring</span>
                    </a>
                </li>

                <!-- Achievements -->
                <li>
                    <a href="{{ route('developer.dashboard') }}"
                       onclick="event.preventDefault(); switchTab('achievements');"
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all
                              text-gray-400 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-trophy text-lg w-5"></i>
                        <span x-show="!collapsed" x-transition class="font-medium">Achievements</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="py-2"><div class="border-t border-gray-700"></div></li>

                <!-- Quick Actions -->
                @if(isset($activeTimeLog) && $activeTimeLog)
                <li>
                    <form method="POST" action="{{ route('developer.pause-task') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg w-full text-left text-gray-400 hover:bg-orange-500/20 hover:text-orange-400 transition-all">
                            <i class="fas fa-pause text-lg w-5"></i>
                            <span x-show="!collapsed" x-transition class="font-medium">Stop Work</span>
                        </button>
                    </form>
                </li>
                @endif

                <!-- Logout -->
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg w-full text-left text-gray-400 hover:bg-red-500/20 hover:text-red-400 transition-all">
                            <i class="fas fa-sign-out-alt text-lg w-5"></i>
                            <span x-show="!collapsed" x-transition class="font-medium">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-700">
            <p class="text-xs text-center text-gray-500" x-show="!collapsed">© 2025 DevWorkspace</p>
            <p class="text-xs text-center text-gray-500" x-show="collapsed">©25</p>
        </div>
    </div>

    @if(isset($activeTimeLog) && $activeTimeLog)
    <script>
        let start = new Date('{{ $activeTimeLog->start_time }}').getTime();
        setInterval(() => {
            const distance = new Date().getTime() - start;
            const h = Math.floor(distance / 3600000);
            const m = Math.floor((distance % 3600000) / 60000);
            const s = Math.floor((distance % 60000) / 1000);
            const el = document.getElementById('sidebar-timer');
            if(el) el.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }, 1000);
    </script>
    @endif
</aside>
