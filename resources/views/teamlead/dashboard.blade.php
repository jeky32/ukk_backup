@extends('layouts.teamlead')

@section('title', 'Team Lead Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->full_name)

@push('styles')
<style>
    /* ========== GRADIENT & BASIC ANIMATIONS ========== */
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-shift 15s ease infinite;
    }

    /* ========== ANIMATED STATS CARDS (SAMA SEPERTI ADMIN) ========== */
    .card-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-lift:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Floating animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    /* Counter Animation */
    @keyframes count-up {
        from {
            opacity: 0;
            transform: translateY(10px) scale(0.8);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .animate-count-up {
        animation: count-up 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Fade in animations */
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out backwards;
    }

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

    /* Bounce subtle */
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .animate-bounce-subtle {
        animation: bounce-subtle 2s ease-in-out infinite;
    }

    /* Icon Bounce */
    @keyframes icon-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .icon-bounce:hover {
        animation: icon-bounce 0.6s ease-in-out;
    }

    /* Ripple Effect */
    .ripple-effect {
        position: relative;
        overflow: hidden;
    }

    .ripple-effect::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        pointer-events: none;
    }

    .ripple-effect:active::after {
        animation: ripple 0.6s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 0.8;
        }
        100% {
            transform: scale(2.5);
            opacity: 0;
        }
    }

    /* ========== SCROLLBAR ========== */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }

    /* ========== PROJECT CAROUSEL ========== */
    .project-carousel {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 1.5rem;
        padding: 1.5rem 0;
        scrollbar-width: thin;
        scrollbar-color: #a78bfa #f1f5f9;
    }

    .project-card {
        min-width: 360px;
        max-width: 360px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(99, 102, 241, 0.15);
    }

    .project-card.selected {
        border: 2px solid #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* Progress bar animation */
    @keyframes progress-load {
        0% { width: 0%; }
    }

    .progress-bar {
        animation: progress-load 1.5s ease-out;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 animate-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8"
         x-data="{
             selectedProject: null,
             showStats: false,
             selectProject(projectId) {
                 if(this.selectedProject === projectId) {
                     this.showStats = !this.showStats;
                 } else {
                     this.selectedProject = projectId;
                     this.showStats = true;
                 }
             }
         }">

        <!-- Stats Overview (SAMA PERSIS DENGAN ADMIN) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Projects -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white card-lift overflow-hidden relative ripple-effect">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-blue-100 text-sm mb-1 font-medium">Total Projects</p>
                            <h3 class="text-4xl font-bold animate-count-up">{{ $projects->count() }}</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float">
                            <i class="fas fa-folder text-2xl icon-bounce"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-blue-100 text-sm">
                        <i class="fas fa-arrow-up mr-2"></i>
                        <span>12% from last month</span>
                    </div>
                </div>
            </div>

            <!-- Active Tasks -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6 text-white card-lift animate-scale-in overflow-hidden relative" style="animation-delay: 0.1s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse" style="animation-delay: 0.5s;"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-orange-100 text-sm mb-1 font-medium">Active Tasks</p>
                            @php
                                $totalCards = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->count()));
                                $doneCards = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'done')->count()));
                                $pendingCards = $totalCards - $doneCards;
                            @endphp
                            <h3 class="text-4xl font-bold">{{ $totalCards }}</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float" style="animation-delay: 0.5s;">
                            <i class="fas fa-tasks text-2xl icon-bounce"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-orange-100 text-sm">
                        <i class="fas fa-fire mr-2 animate-bounce-subtle"></i>
                        <span>{{ $pendingCards }} tasks pending</span>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-xl p-6 text-white card-lift animate-scale-in overflow-hidden relative" style="animation-delay: 0.2s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse" style="animation-delay: 1s;"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-emerald-100 text-sm mb-1 font-medium">Team Members</p>
                            @php
                                $uniqueMembers = $projects->flatMap->members->unique('id')->count();
                            @endphp
                            <h3 class="text-4xl font-bold">{{ $uniqueMembers }}</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float" style="animation-delay: 1s;">
                            <i class="fas fa-users text-2xl icon-bounce"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-emerald-100 text-sm">
                        <i class="fas fa-user-check mr-2"></i>
                        <span>Active members</span>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white card-lift animate-scale-in overflow-hidden relative" style="animation-delay: 0.3s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse" style="animation-delay: 1.5s;"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-purple-100 text-sm mb-1 font-medium">Completed</p>
                            @php
                                $avgProgress = $projects->count() > 0 ? round($projects->avg('progress')) : 0;
                            @endphp
                            <h3 class="text-4xl font-bold">{{ $avgProgress }}%</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float" style="animation-delay: 1.5s;">
                            <i class="fas fa-check-circle text-2xl icon-bounce"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-purple-100 text-sm">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>Great progress!</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Carousel Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-indigo-100 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-folder text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">My Projects</h2>
                        <p class="text-sm text-gray-600">Click any project to view detailed statistics</p>
                    </div>
                </div>

                <!-- Carousel Navigation -->
                <div class="flex space-x-3">
                    <button onclick="document.querySelector('.project-carousel').scrollBy({left: -380, behavior: 'smooth'})"
                            class="w-11 h-11 bg-gray-100 hover:bg-indigo-100 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    <button onclick="document.querySelector('.project-carousel').scrollBy({left: 380, behavior: 'smooth'})"
                            class="w-11 h-11 bg-gray-100 hover:bg-indigo-100 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </div>

            <!-- Project Cards Carousel -->
            @if($projects->count() > 0)
            <div class="project-carousel custom-scrollbar">
                @foreach($projects as $project)
                <div class="project-card bg-white rounded-xl shadow-md overflow-hidden border-2 border-gray-200"
                     :class="selectedProject === {{ $project->id }} ? 'selected' : ''"
                     @click="selectProject({{ $project->id }})">

                    <!-- Project Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>

                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-lg"></i>
                                </div>
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">
                                    {{ strtoupper($project->status ?? 'active') }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold mb-2 truncate">{{ $project->project_name }}</h3>
                            <p class="text-sm text-indigo-100 line-clamp-2 mb-4">
                                {{ $project->description ?? 'No description available' }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold">Progress</span>
                                    <span class="text-sm font-bold">{{ $project->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                    <div class="bg-white h-2 rounded-full transition-all duration-500 progress-bar"
                                         style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Stats -->
                    <div class="p-5 bg-white">
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg">
                                <i class="fas fa-tasks text-indigo-600 mb-1"></i>
                                <p class="text-xl font-bold text-gray-900">
                                    {{ $project->boards->sum(fn($b) => $b->cards->count()) }}
                                </p>
                                <p class="text-xs text-gray-600 font-medium">Tasks</p>
                            </div>
                            <div class="text-center p-3 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg">
                                <i class="fas fa-users text-purple-600 mb-1"></i>
                                <p class="text-xl font-bold text-gray-900">{{ $project->members->count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Members</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200 mb-4">
                            <span class="text-xs text-gray-500 flex items-center">
                                <i class="far fa-calendar mr-1 text-indigo-500"></i>
                                {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'No deadline' }}
                            </span>
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold">
                                {{ $project->boards->count() }} Boards
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-2">
                            <button @click.stop="selectProject({{ $project->id }})"
                                    class="px-3 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg text-xs font-bold transition-all flex items-center justify-center">
                                <i class="fas fa-chart-line mr-1"></i>
                                <span x-show="selectedProject !== {{ $project->id }}">Stats</span>
                                <span x-show="selectedProject === {{ $project->id }}">Hide</span>
                            </button>
                            <a href="{{ route('teamlead.projects.show', $project->id) }}"
                               class="px-3 py-2 bg-white hover:bg-gray-50 text-indigo-600 border-2 border-indigo-600 rounded-lg text-xs font-bold transition-all flex items-center justify-center">
                                <i class="fas fa-arrow-right mr-1"></i>
                                Open
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No Projects Yet</h3>
                <p class="text-gray-600">Start by creating your first project</p>
            </div>
            @endif
        </div>

        <!-- Statistics Panel -->
        <div x-show="showStats && selectedProject"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-2xl shadow-lg p-8 border border-indigo-100">

            @foreach($projects as $project)
            <div x-show="selectedProject === {{ $project->id }}" x-cloak>
                <!-- Stats Header -->
                <div class="flex items-center justify-between mb-8 pb-6 border-b-2 border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Project Statistics</h3>
                            <p class="text-sm text-gray-600">{{ $project->project_name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('teamlead.projects.show', $project->id) }}"
                       class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold transition-all flex items-center shadow-lg">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View Full Project
                    </a>
                </div>

                @php
                    $todoCount = $project->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count());
                    $inProgressCount = $project->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count());
                    $reviewCount = $project->boards->sum(fn($b) => $b->cards->where('status', 'review')->count());
                    $doneCount = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                    $totalCards = $todoCount + $inProgressCount + $reviewCount + $doneCount;
                    $totalSubtasks = $project->boards->sum(fn($b) => $b->cards->sum(fn($c) => $c->subtasks->count()));
                    $doneSubtasks = $project->boards->sum(fn($b) => $b->cards->sum(fn($c) => $c->subtasks->where('status', 'done')->count()));
                @endphp

                <!-- Main Statistics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-clipboard-list text-4xl opacity-80"></i>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">TOTAL</span>
                        </div>
                        <h4 class="text-4xl font-bold mb-2">{{ $totalCards }}</h4>
                        <p class="text-sm opacity-90 font-medium">Total Cards</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-list text-4xl opacity-80"></i>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">SUBTASKS</span>
                        </div>
                        <h4 class="text-4xl font-bold mb-2">{{ $doneSubtasks }}/{{ $totalSubtasks }}</h4>
                        <p class="text-sm opacity-90 font-medium">Subtasks Progress</p>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-users text-4xl opacity-80"></i>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">TEAM</span>
                        </div>
                        <h4 class="text-4xl font-bold mb-2">{{ $project->members->count() }}</h4>
                        <p class="text-sm opacity-90 font-medium">Team Members</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-th-large text-4xl opacity-80"></i>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">BOARDS</span>
                        </div>
                        <h4 class="text-4xl font-bold mb-2">{{ $project->boards->count() }}</h4>
                        <p class="text-sm opacity-90 font-medium">Active Boards</p>
                    </div>
                </div>

                <!-- Task Status Breakdown -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-pie text-white"></i>
                        </div>
                        Task Status Breakdown
                    </h4>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="bg-white rounded-xl p-5 text-center shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clipboard-list text-gray-600 text-xl"></i>
                            </div>
                            <p class="text-3xl font-bold text-gray-600 mb-1">{{ $todoCount }}</p>
                            <p class="text-xs text-gray-600 font-semibold">To Do</p>
                        </div>

                        <div class="bg-white rounded-xl p-5 text-center shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-tasks text-indigo-600 text-xl"></i>
                            </div>
                            <p class="text-3xl font-bold text-indigo-600 mb-1">{{ $inProgressCount }}</p>
                            <p class="text-xs text-gray-600 font-semibold">In Progress</p>
                        </div>

                        <div class="bg-white rounded-xl p-5 text-center shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-eye text-purple-600 text-xl"></i>
                            </div>
                            <p class="text-3xl font-bold text-purple-600 mb-1">{{ $reviewCount }}</p>
                            <p class="text-xs text-gray-600 font-semibold">Review</p>
                        </div>

                        <div class="bg-white rounded-xl p-5 text-center shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <p class="text-3xl font-bold text-green-600 mb-1">{{ $doneCount }}</p>
                            <p class="text-xs text-gray-600 font-semibold">Done</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Enhanced Team Lead Dashboard loaded');

    // Animate progress bars on load
    setTimeout(() => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 300);
});
</script>
@endpush
