<!-- resources/views/admin/dashboard.blade.php -->
@extends('app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8" x-data="dashboard()">
    <!-- Grid Layout: Left (Tasks & Notification) + Right (Calendar) -->
    <div class="grid grid-cols-3 gap-8">
        <!-- Left Column: Today Tasks & Notification -->
        <div class="col-span-2 space-y-6">
            <!-- Today Tasks Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-check text-indigo-600 text-lg"></i>
                        <h3 class="text-lg font-bold text-gray-900">Today Tasks</h3>
                    </div>
                    <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center space-x-1">
                        <span>See All</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </div>

                <!-- Tasks Cards Grid -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    @forelse($todayTasks as $task)
                        <div class="task-card bg-gradient-to-br from-gray-50 to-white border border-gray-100 rounded-xl p-5 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $task['title'] }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task['description'] }}</p>
                                </div>
                                <button class="p-1 hover:bg-gray-100 rounded transition">
                                    <i class="fas fa-ellipsis-h text-gray-400 text-xs"></i>
                                </button>
                            </div>

                            <!-- Team Members Avatars -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div class="flex -space-x-2">
                                    @foreach($task['members'] as $member)
                                        <img src="https://i.pravatar.cc/150?img={{ $loop->index }}"
                                             alt="{{ $member }}"
                                             class="w-6 h-6 rounded-full border-2 border-white"
                                             title="{{ $member }}">
                                    @endforeach
                                    @if(count($task['members']) > 2)
                                        <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-semibold text-gray-600">
                                            +{{ count($task['members']) - 2 }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Progress -->
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-gray-600">{{ $task['progress'] }}%</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full h-2 bg-gray-200 rounded-full mt-3 overflow-hidden">
                                <div class="h-full {{ $task['progress'] >= 80 ? 'bg-teal-500' : ($task['progress'] >= 50 ? 'bg-indigo-500' : 'bg-orange-500') }} progress-bar transition-all duration-1000"
                                     :style="{ width: '{{ $task['progress'] }}%' }"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-gray-50 rounded-xl p-8 text-center">
                            <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 text-sm">No tasks for today</p>
                        </div>
                    @endforelse
                </div>

                <!-- Notification Alert -->
                <div class="flex items-center justify-between bg-gradient-to-r from-teal-500 to-teal-600 rounded-full px-4 py-3 text-white shadow-md">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">You have 5 tasks today. Keep it up! 💪</span>
                    </div>
                    <button class="hover:bg-white/10 p-1 rounded transition">
                        <i class="fas fa-times text-white"></i>
                    </button>
                </div>
            </div>

            <!-- Task Progress Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-chart-pie text-indigo-600 text-lg"></i>
                        <h3 class="text-lg font-bold text-gray-900">Task Progress</h3>
                    </div>
                    <button class="p-2 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-ellipsis-v text-gray-400"></i>
                    </button>
                </div>

                <!-- Progress Chart Container -->
                <div class="flex items-center justify-center h-64">
                    <div class="relative w-48 h-48">
                        <!-- Center Circle with Percentage -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800 rounded-full">
                            <p class="text-3xl font-bold text-white">65%</p>
                            <p class="text-xs text-gray-400 mt-1">Complete</p>
                        </div>

                        <!-- Progress Indicators Around -->
                        <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            +8%
                        </div>
                        <div class="absolute top-8 -left-12 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            +12%
                        </div>
                        <div class="absolute top-8 -right-12 bg-teal-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            +6%
                        </div>
                        <div class="absolute -bottom-12 left-4 bg-gray-700 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            +2%
                        </div>
                        <div class="absolute -bottom-12 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            +10%
                        </div>
                    </div>
                </div>

                <!-- Progress Summary -->
                <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Completed</span>
                        <span class="font-bold text-gray-900">26</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">In Progress</span>
                        <span class="font-bold text-gray-900">14</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Calendar -->
        <div class="col-span-1 space-y-6">
            <!-- Calendar Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-indigo-600 text-lg"></i>
                        <h3 class="text-lg font-bold text-gray-900">Calendar</h3>
                    </div>
                    <button class="flex items-center space-x-1 px-3 py-1 hover:bg-gray-50 rounded-lg transition text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-xs"></i>
                        <span>February</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                </div>

                <!-- Calendar Navigation -->
                <div class="flex items-center justify-between mb-4">
                    <button class="p-1 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-chevron-left text-gray-400"></i>
                    </button>
                    <p class="text-sm font-semibold text-gray-900" x-text="currentMonth"></p>
                    <button class="p-1 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="mb-6">
                    <!-- Day Headers -->
                    <div class="grid grid-cols-7 gap-2 mb-2">
                        @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">{{ $day }}</div>
                        @endforeach
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-2">
                        @foreach($calendarDays as $day)
                            <button class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['isSelected'] ? 'active' : '' }} {{ $day['isOtherMonth'] ? 'opacity-30 cursor-default' : '' }} {{ !$day['isOtherMonth'] ? 'hover:bg-gray-100' : '' }}"
                                    @if(!$day['isOtherMonth']) @click="selectDate('{{ $day['date'] }}')" @endif>
                                {{ $day['day'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Task Timeline -->
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-stream text-indigo-600 text-lg"></i>
                    <h3 class="text-lg font-bold text-gray-900">Task Timeline</h3>
                </div>
                <button class="p-2 hover:bg-gray-50 rounded-lg transition">
                    <i class="fas fa-ellipsis-v text-gray-400"></i>
                </button>
            </div>

            <!-- Timeline Container -->
            <div class="flex items-end justify-between h-64 bg-gradient-to-b from-gray-50 to-white rounded-xl p-8 relative">
                <!-- Timeline Grid Lines -->
                <div class="absolute inset-0 opacity-10">
                    @for($i = 0; $i < 7; $i++)
                        <div class="absolute w-px h-full bg-gray-300"
                             style="left: {{ ($i + 1) * (100 / 7) }}%"></div>
                    @endfor
                </div>

                <!-- Timeline Bars -->
                @foreach($timelineData as $item)
                    <div class="flex-1 flex flex-col items-center group relative z-10">
                        <!-- Timeline Bar -->
                        <div class="w-full flex items-end justify-center mb-4 h-40">
                            <div class="w-20 rounded-t-2xl transition-all duration-300 hover:shadow-lg group-hover:scale-y-110 origin-bottom"
                                 :style="{ height: 'calc({{ $item['height'] }}% * 150px / 100)', background: '{{ $item['color'] }}' }}"
                                 :title="'{{ $item['title'] }}'"
                                 @mouseenter="activeTimeline = '{{ $item['id'] }}'"
                                 @mouseleave="activeTimeline = null">
                            </div>
                        </div>

                        <!-- Timeline Label -->
                        <p class="text-sm font-semibold text-gray-900 text-center bg-white px-4 py-2 rounded-full border-2 transition-all duration-300"
                           :class="{ 'border-gray-300': activeTimeline !== '{{ $item['id'] }}', 'border-{{ $item['color-class'] }}': activeTimeline === '{{ $item['id'] }}' }">
                            {{ $item['title'] }}
                        </p>

                        <!-- Date Label -->
                        <p class="text-xs text-gray-400 mt-2">{{ $item['date'] }}</p>
                    </div>
                @endforeach

                <!-- X-Axis -->
                <div class="absolute bottom-0 left-0 right-0 h-px bg-gray-300"></div>
            </div>

            <!-- Timeline Legend -->
            <div class="mt-6 grid grid-cols-4 gap-4">
                @foreach($timelineData as $item)
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                        <span class="text-xs font-medium text-gray-600">{{ $item['title'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .task-card {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .progress-bar {
        transition: width 1s ease;
    }

    .calendar-day {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .calendar-day:not(.opacity-30):hover {
        background: #F3F4F6;
    }

    .calendar-day.active {
        background: #10B981;
        color: white;
        font-weight: 600;
    }

    .calendar-day.today {
        background: #1E293B;
        color: white;
        font-weight: 600;
    }
</style>

<script>
    function dashboard() {
        return {
            activeTimeline: null,
            selectedDate: new Date(),
            currentMonth: 'February 2025',

            selectDate(date) {
                this.selectedDate = new Date(date);
                console.log('Selected:', date);
            }
        }
    }
</script>
@endsection
