<!-- resources/views/components/top-header.blade.php -->
<header class="bg-white border-b border-gray-200 px-8 py-4 shadow-sm">
    <div class="flex items-center justify-between">
        <!-- Welcome Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Start Your Day</h2>
            <p class="text-2xl font-bold text-gray-900">& Be Productive 🤘</p>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text"
                       placeholder="Start searching here..."
                       class="w-80 pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Icons -->
            <button class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                <i class="fas fa-sliders-h text-gray-600"></i>
            </button>

            <button class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                <i class="fas fa-sun text-gray-600"></i>
            </button>

            <button class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-xl transition relative">
                <i class="fas fa-bell text-gray-600"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-3 bg-gray-50 hover:bg-gray-100 px-3 py-2 rounded-xl transition">
                    <img src="https://i.pravatar.cc/150?img=33" alt="User" class="w-8 h-8 rounded-full">
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-900">Kim So Men</p>
                        <p class="text-xs text-gray-500">UI/UX Designer</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs" :class="{ 'rotate-180': open }"></i>
                </button>
            </div>
        </div>
    </div>
</header>
