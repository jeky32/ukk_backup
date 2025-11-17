@extends('layouts.admin')

@section('title', 'Project Details - ' . $project->project_name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50">
    <!-- Header Section with Beautiful Design -->
    <div class="mb-8 animate-fade-in-down">
        <a href="{{ route('admin.projects.index') }}"
           class="inline-flex items-center space-x-2 px-4 py-2 bg-white/80 backdrop-blur-xl text-gray-700 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 border border-indigo-100 mb-6">
            <i class="fas fa-arrow-left"></i>
            <span class="font-medium">Back to Projects</span>
        </a>

        <!-- Project Header Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-indigo-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center shadow-2xl">
                                <i class="fas fa-project-diagram text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold text-white drop-shadow-lg">
                                    {{ $project->project_name }}
                                </h1>
                                <p class="text-indigo-100 text-sm flex items-center mt-1">
                                    <i class="fas fa-user mr-2"></i>
                                    Created by {{ $project->creator->full_name }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <button class="px-6 py-3 bg-white/20 backdrop-blur-xl text-white rounded-xl hover:bg-white/30 transition-all duration-300 flex items-center space-x-2 border border-white/30">
                        <i class="fas fa-edit"></i>
                        <span class="font-medium">Edit</span>
                    </button>
                </div>
            </div>

            <!-- Quick Stats Inside Card -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Created Date -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-calendar-plus text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold uppercase">Created</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ $project->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-600 font-semibold uppercase mb-1">Deadline</p>
                                @if($project->deadline)
                                    <p class="text-lg font-bold text-gray-800">
                                        {{ date('M d, Y', strtotime($project->deadline)) }}
                                    </p>
                                    @php
                                        $daysUntil = now()->diffInDays($project->deadline, false);
                                    @endphp
                                    @if($daysUntil < 0)
                                        <span class="text-xs text-red-600 font-semibold flex items-center mt-1">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ abs($daysUntil) }} days overdue
                                        </span>
                                    @else
                                        <span class="text-xs text-green-600 font-semibold flex items-center mt-1">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $daysUntil }} days remaining
                                        </span>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500 italic">No deadline</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Total Members -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold uppercase">Total Members</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ $project->members->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-2xl p-6 border border-slate-200">
                    <h3 class="text-sm font-bold text-gray-700 uppercase mb-3 flex items-center">
                        <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                        Project Description
                    </h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $project->description }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-indigo-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-5">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    Team Members
                </h2>
                <button class="px-5 py-2 bg-white/20 backdrop-blur-xl text-white rounded-lg hover:bg-white/30 transition-all duration-300 text-sm font-medium border border-white/30">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Member
                </button>
            </div>
        </div>

        <div class="p-8">
            @if($project->members->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($project->members as $member)
                        <div class="bg-gradient-to-br from-white to-indigo-50 rounded-2xl p-6 border-2 border-indigo-100 hover:border-indigo-300 hover:shadow-2xl transform hover:scale-105 transition-all duration-300 group">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-white text-xl font-bold">
                                        {{ strtoupper(substr($member->user->full_name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                        {{ $member->user->full_name }}
                                    </h3>
                                    <span class="inline-block mt-1 px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-xs font-semibold border border-indigo-200">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-indigo-100">
                                <button class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    View Profile
                                </button>
                                <button class="text-red-600 hover:text-red-700 text-sm">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-6 animate-float">
                        <i class="fas fa-users text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No Team Members Yet</h3>
                    <p class="text-gray-500 mb-6">Start building your team</p>
                    <button class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 font-medium">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add First Member
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out;
    }

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

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endsection
