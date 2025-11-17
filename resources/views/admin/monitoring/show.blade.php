@extends('layouts.admin')

@section('title', 'Project Details - ' . $project->project_name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 animate-gradient-bg">
    <!-- Back Button & Header -->
    <div class="mb-6 animate-fade-in">
        <a href="{{ route('admin.monitoring.index') }}"
           class="inline-flex items-center space-x-2 px-4 py-2 bg-white/90 backdrop-blur-sm text-gray-700 rounded-lg hover:shadow-lg hover:bg-white transition-all duration-300 border border-gray-200 mb-4 animate-slide-down">
            <i class="fas fa-arrow-left text-indigo-600"></i>
            <span class="font-semibold text-sm">Back to Monitoring</span>
        </a>

        <div class="flex items-center justify-between animate-slide-down" style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-1 animate-shimmer">
                    {{ $project->project_name }}
                </h1>
                <p class="text-sm text-gray-600">{{ $project->description ?? 'No description available' }}</p>
            </div>
            <div class="flex items-center space-x-3">
                 <a href="{{ route('admin.projects.edit', $project->id) }}"
                                   class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 text-sm font-semibold flex items-center space-x-2 hover:scale-105"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>  <span>Edit Project</span>
                                </a>
                {{--  <button class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 text-sm font-semibold flex items-center space-x-2 hover:scale-105">
                    <i class="fas fa-edit"></i>
                    <span>Edit Project</span>
                </button>  --}}
            </div>
        </div>
    </div>

    <!-- Project Stats Cards dengan Animasi -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-blue-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 animate-scale-in group relative overflow-hidden cursor-pointer">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 animate-pulse-slow"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 animate-bounce-soft">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Tasks</p>
                <p class="text-3xl font-bold text-gray-900 animate-count-up">{{ $projectStats['total_tasks'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-green-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 animate-scale-in group relative overflow-hidden cursor-pointer" style="animation-delay: 0.1s;">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 animate-pulse-slow"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 animate-bounce-soft" style="animation-delay: 0.3s;">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Completed</p>
                <p class="text-3xl font-bold text-gray-900 animate-count-up">{{ $projectStats['completed_tasks'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Active Members -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-purple-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 animate-scale-in group relative overflow-hidden cursor-pointer" style="animation-delay: 0.2s;">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 animate-pulse-slow"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 animate-bounce-soft" style="animation-delay: 0.6s;">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Active Members</p>
                <p class="text-3xl font-bold text-gray-900 animate-count-up">{{ $projectStats['active_members'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Progress -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-amber-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 animate-scale-in group relative overflow-hidden cursor-pointer" style="animation-delay: 0.3s;">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 animate-pulse-slow"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 animate-bounce-soft" style="animation-delay: 0.9s;">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Progress</p>
                <p class="text-3xl font-bold text-gray-900 animate-count-up">{{ $projectStats['progress_percentage'] ?? 0 }}%</p>
            </div>
        </div>
    </div>

    <!-- Team Members & Project Leader Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Team Members List -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-slide-left">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4 animate-shimmer-bg">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-users mr-2 animate-wiggle"></i>
                        Team Members
                    </h2>
                    <span class="text-xs bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white font-medium animate-pulse-soft">
                        {{ $project->members->count() }} members
                    </span>
                </div>
            </div>

            <div class="p-5">
                @if($project->members->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($project->members as $index => $member)
                            <div class="flex items-center justify-between p-3.5 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md transform hover:scale-[1.02] transition-all duration-300 group animate-slide-right" style="animation-delay: {{ $index * 0.05 }}s;">
                                <div class="flex items-center space-x-3">
                                    <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                        <span class="text-white text-base font-bold">
                                            {{ strtoupper(substr($member->full_name ?? $member->username ?? 'U', 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-sm text-gray-900 group-hover:text-indigo-600 transition-colors">
                                            {{ $member->full_name ?? $member->username ?? 'Unknown User' }}
                                        </h3>
                                        <p class="text-xs text-gray-600">{{ $member->email ?? 'No email' }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-md text-xs font-bold uppercase tracking-wide shadow-sm hover:shadow-md transition-shadow">
                                    {{ ucfirst($member->role ?? 'member') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 animate-bounce-soft">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-700 mb-1">No Team Members Yet</h3>
                        <p class="text-sm text-gray-500">Add members to start collaborating</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Project Leader Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-slide-right sticky top-6">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-5 py-4 animate-shimmer-bg">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-crown mr-2 animate-wiggle"></i>
                        Project Leader
                    </h2>
                </div>

                <div class="p-5">
                    @foreach($project->teamLeads as $lead)
                        <div class="text-center animate-scale-in">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg mx-auto mb-4 animate-glow hover:scale-110 transition-transform duration-300 cursor-pointer">
                                <span class="text-white text-3xl font-bold">
                                    {{ strtoupper(substr($lead->full_name ?? $lead->username ?? 'L', 0, 2)) }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">
                                {{ $lead->full_name ?? $lead->username ?? 'Unknown Leader' }}
                            </h3>
                            <p class="text-xs text-gray-600 mb-3">{{ $lead->email ?? 'No email' }}</p>
                            <span class="inline-block px-4 py-1.5 bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold border border-purple-200">
                                {{ ucfirst($lead->role ?? 'teamlead') }}
                            </span>
                        </div>
                    @endforeach
                        {{--  <div class="flex flex-col items-center justify-center py-10 animate-fade-in">
                            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 animate-bounce-soft">
                                <i class="fas fa-user-tie text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-sm font-bold text-gray-700 mb-1">No Leader Assigned</h3>
                            <p class="text-xs text-gray-500 mb-4">Assign a project leader</p>
                            <button class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 text-xs font-semibold hover:scale-105">
                                <i class="fas fa-plus mr-1"></i>
                                Assign Leader
                            </button>
                        </div>  --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    {{--  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-slide-up" style="animation-delay: 0.4s;">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4 animate-shimmer-bg">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-clipboard-list mr-2 animate-wiggle"></i>
                    Project Tasks
                    @if(isset($tasks))
                        <span class="ml-2 text-xs bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-medium animate-pulse-soft">
                            {{ $tasks->count() }} tasks
                        </span>
                    @endif
                </h2>
                <button class="px-4 py-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-lg transition-all duration-300 text-xs font-semibold hover:scale-105">
                    <i class="fas fa-plus mr-1"></i>
                    Add Task
                </button>
            </div>
        </div>

        <div class="p-5">
            @if(isset($tasks) && $tasks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($tasks as $index => $task)
                        <div class="bg-white rounded-lg p-4 border-2 border-gray-200 hover:border-indigo-300 hover:shadow-lg transform hover:-translate-y-2 transition-all duration-300 group animate-scale-in cursor-pointer" style="animation-delay: {{ 0.5 + ($index * 0.05) }}s;">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-sm text-gray-900 group-hover:text-indigo-600 transition-colors flex-1 pr-2">
                                    {{ $task->title ?? 'Untitled Task' }}
                                </h3>
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-700 rounded-md text-xs font-semibold whitespace-nowrap animate-pulse-soft">
                                    Active
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">
                                {{ $task->description ?? 'No description available' }}
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                <div class="flex -space-x-2">
                                    @if(isset($task->assignees) && $task->assignees->count() > 0)
                                        @foreach($task->assignees->take(3) as $assignee)
                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full border-2 border-white flex items-center justify-center shadow-sm hover:scale-125 hover:z-10 transition-all duration-300">
                                                <span class="text-white text-xs font-bold">
                                                    {{ strtoupper(substr($assignee->full_name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                        @endforeach
                                        @if($task->assignees->count() > 3)
                                            <div class="w-8 h-8 bg-gray-300 rounded-full border-2 border-white flex items-center justify-center shadow-sm hover:scale-125 transition-transform">
                                                <span class="text-gray-700 text-xs font-bold">
                                                    +{{ $task->assignees->count() - 3 }}
                                                </span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">No assignees</span>
                                    @endif
                                </div>
                                <button class="text-indigo-600 hover:text-indigo-700 font-semibold text-xs flex items-center space-x-1 group-hover:translate-x-1 transition-transform">
                                    <span>Details</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 animate-fade-in">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4 shadow-sm animate-bounce-soft">
                        <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Tasks Yet</h3>
                    <p class="text-sm text-gray-500 mb-6 text-center">Start organizing your project by creating your first task</p>
                    <button class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Task
                    </button>
                </div>
            @endif
        </div>  --}}
    </div>
</div>

<style>
    /* Animasi Background Gradient */
    @keyframes gradient-bg {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .animate-gradient-bg {
        background-size: 200% 200%;
        animation: gradient-bg 15s ease infinite;
    }

    /* Animasi Shimmer untuk Text */
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    .animate-shimmer {
        background-size: 200% auto;
        animation: shimmer 3s linear infinite;
    }

    /* Animasi Shimmer Background */
    @keyframes shimmer-bg {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.95; }
    }

    .animate-shimmer-bg {
        animation: shimmer-bg 2s ease-in-out infinite;
    }

    /* Fade In */
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    /* Slide Down */
    @keyframes slide-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-down {
        animation: slide-down 0.6s ease-out backwards;
    }

    /* Scale In */
    @keyframes scale-in {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-scale-in {
        animation: scale-in 0.5s ease-out backwards;
    }

    /* Slide Left */
    @keyframes slide-left {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-left {
        animation: slide-left 0.6s ease-out;
    }

    /* Slide Right */
    @keyframes slide-right {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-right {
        animation: slide-right 0.6s ease-out backwards;
    }

    /* Slide Up */
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out backwards;
    }

    /* Bounce Soft */
    @keyframes bounce-soft {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .animate-bounce-soft {
        animation: bounce-soft 2s ease-in-out infinite;
    }

    /* Pulse Slow */
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 0.8; }
    }

    .animate-pulse-slow {
        animation: pulse-slow 3s ease-in-out infinite;
    }

    /* Pulse Soft */
    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .animate-pulse-soft {
        animation: pulse-soft 2s ease-in-out infinite;
    }

    /* Glow Effect */
    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.5);
        }
        50% {
            box-shadow: 0 0 30px rgba(168, 85, 247, 0.8);
        }
    }

    .animate-glow {
        animation: glow 2s ease-in-out infinite;
    }

    /* Wiggle Icon */
    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
    }

    .animate-wiggle {
        animation: wiggle 2s ease-in-out infinite;
    }

    /* Count Up (untuk angka) */
    @keyframes count-up {
        from {
            opacity: 0;
            transform: scale(0.5);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-count-up {
        animation: count-up 0.5s ease-out;
    }
</style>
@endsection
