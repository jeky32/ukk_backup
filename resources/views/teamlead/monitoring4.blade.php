@extends('layouts.teamlead')

@section('title', 'Team Lead Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->full_name)

@push('styles')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
	</script>
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
                    $progress = $totalCards > 0 ? round(($doneCount / $totalCards) * 100) : 0;
                @endphp

                <!-- PROJECT OVERVIEW SECTION (NEW STYLE) -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300 mb-6">
                    <div class="flex flex-col md:flex-row gap-6 mb-6">
                        <div class="w-full md:w-32 h-32 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 rounded-2xl flex items-center justify-center text-white flex-shrink-0 shadow-lg">
                            <i class="fas fa-rocket text-5xl"></i>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $project->project_name }}</h2>
                            <div class="flex flex-wrap gap-4 md:gap-6 mb-4">
                                <div class="flex items-center gap-2 text-gray-600 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                    <i class="fas fa-users text-primary-600"></i>
                                    <span class="font-medium">{{ $project->members->count() }} Team Members</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                    <i class="fas fa-calendar text-primary-600"></i>
                                    <span class="font-medium">Started: {{-- $project->created_at->format('M d, Y') --}}</span>
                                </div>
                                @if($project->deadline)
                                <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-white px-4 py-2 rounded-lg font-bold text-xs flex items-center gap-2 shadow-md">
                                    <i class="fas fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                </div>
                                @endif
                            </div>

                            <div class="mt-5 bg-gray-50 p-4 rounded-xl">
                                <div class="flex justify-between mb-3 text-sm font-semibold">
                                    <span class="text-gray-700">Overall Progress</span>
                                    <span class="text-primary-600 text-lg">{{ $progress }}%</span>
                                </div>
                                <div class="h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-primary-500 via-primary-600 to-primary-700 rounded-full shadow-sm progress-bar" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Cards Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl text-center border-t-4 border-gray-600 shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-xs font-bold text-gray-600 uppercase mb-2 tracking-wide">To Do</h4>
                            <div class="text-4xl font-extrabold text-gray-900">{{ $todoCount }}</div>
                            <div class="mt-2 text-xs text-gray-500">tasks pending</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl text-center border-t-4 border-primary-600 shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-xs font-bold text-primary-700 uppercase mb-2 tracking-wide">In Progress</h4>
                            <div class="text-4xl font-extrabold text-primary-900">{{ $inProgressCount }}</div>
                            <div class="mt-2 text-xs text-primary-600">active now</div>
                        </div>
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-5 rounded-xl text-center border-t-4 border-amber-500 shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-xs font-bold text-amber-700 uppercase mb-2 tracking-wide">Review</h4>
                            <div class="text-4xl font-extrabold text-amber-900">{{ $reviewCount }}</div>
                            <div class="mt-2 text-xs text-amber-600">awaiting review</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl text-center border-t-4 border-green-500 shadow-md hover:shadow-lg transition-shadow">
                            <h4 class="text-xs font-bold text-green-700 uppercase mb-2 tracking-wide">Done</h4>
                            <div class="text-4xl font-extrabold text-green-900">{{ $doneCount }}</div>
                            <div class="mt-2 text-xs text-green-600">completed</div>
                        </div>
                    </div>
                </div>

                <!-- TEAM MEMBERS SECTION (NEW STYLE) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-users text-primary-600"></i>
                                Team Members
                            </h3>
                            <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-bold">{{ $project->members->count() }} Active</span>
                        </div>

                        <div class="space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar">
                            @foreach($project->members->take(10) as $member)
                            <div class="flex gap-3 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border-l-4 border-primary-500 hover:shadow-md transition-shadow">
                                <div class="relative flex-shrink-0">
                                    @if($member->avatar)
                                    <img src="{{ asset('storage/' . $member->avatar) }}" 
                                        alt="{{ $member->full_name }}"
                                        class="w-14 h-14 rounded-full object-cover shadow-md">
                                    @else
                                    <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($member->full_name ?: $member->username, 0, 2)) }}
                                    </div>
                                    @endif
                                    <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full ring-2 ring-green-200"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $member->full_name ?: $member->username }}</h4>
                                    <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                        <i class="fas fa-envelope text-primary-600 text-xs"></i>
                                        {{ $member->email }}
                                    </p>
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $member->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $member->role === 'teamlead' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $member->role === 'developer' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $member->role === 'designer' ? 'bg-pink-100 text-pink-700' : '' }}
                                        {{ $member->role === 'member' ? 'bg-gray-100 text-gray-700' : '' }}">
                                        <i class="fas fa-user-tag mr-1"></i>
                                        {{ ucfirst($member->role) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- BOARDS SECTION -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-th-large text-primary-600"></i>
                                Boards
                            </h3>
                            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">{{ $project->boards->count() }} Total</span>
                        </div>

                        <div class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar">
                            @forelse($project->boards as $board)
                            <a href="{{ route('admin.boards.show', ['project' => $project, 'board' => $board]) }}"
                            class="block p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl hover:shadow-md transition-all border-l-4 border-indigo-500 group">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition mb-2 flex items-center">
                                            <i class="fas fa-columns mr-2 text-indigo-600"></i>
                                            {{ $board->board_name }}
                                        </h4>
                                        @php
                                            $boardCards = $board->cards;
                                            $boardTodo = $boardCards->where('status', 'todo')->count();
                                            $boardInProgress = $boardCards->where('status', 'in_progress')->count();
                                            $boardReview = $boardCards->where('status', 'review')->count();
                                            $boardDone = $boardCards->where('status', 'done')->count();
                                            $boardTotal = $boardCards->count();
                                            $boardProgress = $boardTotal > 0 ? round(($boardDone / $boardTotal) * 100) : 0;
                                        @endphp
                                        
                                        <div class="flex items-center space-x-3 text-xs text-gray-600 mb-2">
                                            <span class="flex items-center">
                                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-1"></div>
                                                {{ $boardTodo }} To Do
                                            </span>
                                            <span class="flex items-center">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-1"></div>
                                                {{ $boardInProgress }} Progress
                                            </span>
                                            <span class="flex items-center">
                                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-1"></div>
                                                {{ $boardReview }} Review
                                            </span>
                                            <span class="flex items-center">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                                {{ $boardDone }} Done
                                            </span>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-semibold text-gray-600">Progress</span>
                                                <span class="text-xs font-bold text-indigo-600">{{ $boardProgress }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2 rounded-full transition-all progress-bar" style="width: {{ $boardProgress }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition ml-3">
                                        <i class="fas fa-arrow-right text-indigo-600 group-hover:text-white transition"></i>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-th-large text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-500">No boards created yet</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- STATISTICS SUMMARY (NEW STYLE) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
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
            </div>
            @endforeach
        </div>

    </div>
	
    <div class="max-w-7xl mx-auto p-2 md:p-4 lg:p-6">
	
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Team Monitoring Dashboard</h1>
                    <p class="text-gray-600 text-sm">Overview proyek dan status tim Anda secara real-time</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- SECTION 1: PROJECT OVERVIEW -->
            <div class="lg:col-span-3 bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex flex-col md:flex-row gap-6 mb-6">
                    <div class="w-full md:w-32 h-32 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 rounded-2xl flex items-center justify-center text-white flex-shrink-0 shadow-lg">
                        <i class="fas fa-rocket text-5xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">E-Commerce Platform Redesign</h2>
                        <div class="flex flex-wrap gap-4 md:gap-6 mb-4">
                            <div class="flex items-center gap-2 text-gray-600 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                <i class="fas fa-users text-primary-600"></i>
                                <span class="font-medium">8 Team Members</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                <i class="fas fa-calendar text-primary-600"></i>
                                <span class="font-medium">Started: Oct 1, 2024</span>
                            </div>
                            <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-white px-4 py-2 rounded-lg font-bold text-xs flex items-center gap-2 shadow-md">
                                <i class="fas fa-clock"></i> 5 days left
                            </div>
                        </div>

                        <div class="mt-5 bg-gray-50 p-4 rounded-xl">
                            <div class="flex justify-between mb-3 text-sm font-semibold">
                                <span class="text-gray-700">Overall Progress</span>
                                <span class="text-primary-600 text-lg">75%</span>
                            </div>
                            <div class="h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-primary-500 via-primary-600 to-primary-700 rounded-full shadow-sm" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl text-center border-t-4 border-gray-600 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-gray-600 uppercase mb-2 tracking-wide">To Do</h4>
                        <div class="text-4xl font-extrabold text-gray-900">3</div>
                        <div class="mt-2 text-xs text-gray-500">tasks pending</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl text-center border-t-4 border-primary-600 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-primary-700 uppercase mb-2 tracking-wide">In Progress</h4>
                        <div class="text-4xl font-extrabold text-primary-900">4</div>
                        <div class="mt-2 text-xs text-primary-600">active now</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-5 rounded-xl text-center border-t-4 border-amber-500 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-amber-700 uppercase mb-2 tracking-wide">Review</h4>
                        <div class="text-4xl font-extrabold text-amber-900">2</div>
                        <div class="mt-2 text-xs text-amber-600">awaiting review</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl text-center border-t-4 border-green-500 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-green-700 uppercase mb-2 tracking-wide">Done</h4>
                        <div class="text-4xl font-extrabold text-green-900">12</div>
                        <div class="mt-2 text-xs text-green-600">completed</div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: TEAM WORKLOAD -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-users text-primary-600"></i>
                        Team Members
                    </h3>
                    <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-bold">4 Active</span>
                </div>

                <div class="space-y-4">
                    <div class="flex gap-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-4 border-green-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                AR
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-3 border-white rounded-full ring-2 ring-green-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Ahmad Rizki</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-code text-primary-600"></i>
                                Working on API Integration
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 90%"></div>
                            </div>
                            <p class="text-xs font-bold text-green-700 mt-1.5">Productivity: 90%</p>
                        </div>
                    </div>

                    <div class="flex gap-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-4 border-green-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                SN
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-3 border-white rounded-full ring-2 ring-green-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Siti Nurhaliza</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-vial text-purple-600"></i>
                                Testing Login Module
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 85%"></div>
                            </div>
                            <p class="text-xs font-bold text-green-700 mt-1.5">Productivity: 85%</p>
                        </div>
                    </div>

                    <div class="flex gap-3 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-l-4 border-amber-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                BS
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-amber-500 border-3 border-white rounded-full ring-2 ring-amber-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Budi Santoso</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-palette text-indigo-600"></i>
                                UI Design Review
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-amber-400 to-amber-600 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs font-bold text-amber-700 mt-1.5">Productivity: 75%</p>
                        </div>
                    </div>

                    <div class="flex gap-3 p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-l-4 border-red-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-pink-600 to-pink-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                DL
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-red-500 border-3 border-white rounded-full ring-2 ring-red-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Dewi Lestari</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-tasks text-pink-600"></i>
                                Sprint Planning
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-red-400 to-red-600 rounded-full" style="width: 60%"></div>
                            </div>
                            <p class="text-xs font-bold text-red-700 mt-1.5">Productivity: 60%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PRIORITY TASKS -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-fire text-red-500"></i>
                        Priority Tasks
                    </h3>
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">4 Urgent</span>
                </div>

                <div class="space-y-3">
                    <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-l-4 border-red-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Complete Payment Gateway Integration</h4>
                            <span class="bg-red-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">High</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    AR
                                </div>
                                <span class="font-medium">Ahmad Rizki</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-clock text-red-500"></i> Today
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-700 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-l-4 border-red-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Fix Critical Bug in Checkout Flow</h4>
                            <span class="bg-red-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">High</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    SN
                                </div>
                                <span class="font-medium">Siti Nurhaliza</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-clock text-red-500"></i> Today
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-700 rounded-full" style="width: 40%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-l-4 border-amber-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Update User Dashboard Design</h4>
                            <span class="bg-amber-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">Medium</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    BS
                                </div>
                                <span class="font-medium">Budi Santoso</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-calendar-day text-amber-500"></i> Tomorrow
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-amber-700 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-l-4 border-amber-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Database Performance Optimization</h4>
                            <span class="bg-amber-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">Medium</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-pink-600 to-pink-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    DL
                                </div>
                                <span class="font-medium">Dewi Lestari</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-calendar-day text-amber-500"></i> Tomorrow
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-amber-700 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: ACTIVITY FEED -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-rss text-primary-600"></i>
                        Recent Activity
                    </h3>
                    <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-bold">Live</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary-200 via-primary-300 to-transparent"></div>

                    <div class="space-y-1">
                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    AR
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Ahmad Rizki</strong> completed task <span class="text-primary-600 font-semibold">"API Documentation"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 2 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    SN
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Siti Nurhaliza</strong> moved <span class="text-purple-600 font-semibold">"Login Module"</span> to Review
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 3 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    BS
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Budi Santoso</strong> commented on <span class="text-indigo-600 font-semibold">"UI Design"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 5 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-pink-600 to-pink-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    DL
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Dewi Lestari</strong> started <span class="text-pink-600 font-semibold">"Database Migration"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 6 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    AR
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Ahmad Rizki</strong> created new task <span class="text-primary-600 font-semibold">"Security Audit"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> Yesterday
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: BLOCKERS -->
            <!-- SECTION: BLOCKED CARDS (TEAM MEMBERS BLOCKED) -->
            @if($blockedCards->count() > 0)
            <div class="lg:col-span-3 mt-6">
                <div class="bg-gradient-to-br from-red-50 to-rose-100 border-3 border-red-500 rounded-2xl p-6 shadow-xl">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white flex-shrink-0 shadow-lg">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-900 mb-1 flex items-center gap-2">
                                Team Members Blocked
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">{{ $blockedCards->count() }} Urgent</span>
                            </h3>
                            <p class="text-sm text-red-800 font-medium">{{ $blockedCards->count() }} team members need your immediate help to continue their tasks</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($blockedCards->take(6) as $card)
                        <div class="bg-white p-4 rounded-xl flex flex-col gap-3 shadow-md hover:shadow-lg transition-shadow border border-red-200">
                            <div class="flex items-center gap-3">
                                @if($card->assignedUser)
                                    @if($card->assignedUser->avatar)
                                    <img src="{{ asset('storage/' . $card->assignedUser->avatar) }}" 
                                        alt="{{ $card->assignedUser->full_name }}"
                                        class="w-12 h-12 rounded-full object-cover shadow-md">
                                    @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                        {{ strtoupper(substr($card->assignedUser->full_name ?: $card->assignedUser->username, 0, 2)) }}
                                    </div>
                                    @endif
                                @else
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">
                                        {{ $card->assignedUser ? ($card->assignedUser->full_name ?: $card->assignedUser->username) : 'Unassigned' }}
                                    </h4>
                                    <p class="text-xs text-gray-600 flex items-center gap-1 truncate">
                                        <i class="fas fa-exclamation-circle text-red-500"></i>
                                        {{ $card->title }}
                                    </p>
                                    <p class="text-xs text-gray-500 flex items-center gap-1 mt-1 truncate">
                                        <i class="fas fa-project-diagram text-gray-400"></i>
                                        {{ $card->board->project->project_name }}
                                    </p>
                                </div>
                            </div>
                            
                            <a href="{{ route('admin.boards.show', ['project' => $card->board->project, 'board' => $card->board]) }}#card-{{ $card->id }}" 
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                                <i class="fas fa-hands-helping"></i> Provide Help
                            </a>
                        </div>
                        @endforeach
                    </div>

                    @if($blockedCards->count() > 6)
                    <div class="mt-4 text-center">
                        <button onclick="showAllBlockedCards()" class="text-red-700 hover:text-red-900 font-bold text-sm flex items-center justify-center mx-auto gap-2">
                            <i class="fas fa-chevron-down"></i>
                            Show {{ $blockedCards->count() - 6 }} more blocked tasks
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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