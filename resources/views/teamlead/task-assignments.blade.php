@extends('layouts.teamlead')

@section('title', 'Task Assignments')
@section('page-title', 'TASK ASSIGNMENTS')
@section('page-subtitle', 'Manage task assignments across your projects')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Unassigned Tasks -->
            <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">Needs Attention</span>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $unassigned->count() }}</h3>
                <p class="text-sm opacity-90 font-medium">Unassigned Tasks</p>
            </div>

            <!-- Assigned Tasks -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">Active</span>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $assigned->count() }}</h3>
                <p class="text-sm opacity-90 font-medium">Assigned Tasks</p>
            </div>
        </div>

        <!-- Unassigned Tasks Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 mb-8">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-3"></i>
                    Unassigned Tasks
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Tasks that need to be assigned to team members
                </p>
            </div>

            <div class="p-6">
                @if($unassigned->count() > 0)
                    <div class="space-y-4">
                        @foreach($unassigned as $card)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border-l-4 border-orange-500 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $card->card_title }}
                                        </h3>
                                        @if($card->card_description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                {{ Str::limit($card->card_description, 150) }}
                                            </p>
                                        @endif
                                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <i class="fas fa-folder mr-1"></i>
                                                {{ $card->board->project->project_name }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-columns mr-1"></i>
                                                {{ $card->board->board_name }}
                                            </span>
                                            @if($card->priority)
                                                <span class="px-2 py-1 rounded-full font-semibold
                                                    {{ $card->priority === 'high' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                    {{ $card->priority === 'medium' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                                    {{ $card->priority === 'low' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}">
                                                    {{ ucfirst($card->priority) }} Priority
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.projects.show', $card->board->project) }}" 
                                       class="ml-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Assign
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-3xl"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 font-semibold">All tasks are assigned!</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Great job managing your team</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assigned Tasks Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-user-check text-green-600 mr-3"></i>
                    Assigned Tasks
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Tasks currently assigned to team members
                </p>
            </div>

            <div class="p-6">
                @if($assigned->count() > 0)
                    <div class="space-y-4">
                        @foreach($assigned as $card)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border-l-4 border-green-500 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $card->card_title }}
                                        </h3>
                                        @if($card->card_description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                {{ Str::limit($card->card_description, 150) }}
                                            </p>
                                        @endif
                                        
                                        <!-- Assigned Members -->
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Assigned to:</span>
                                            <div class="flex -space-x-2">
                                                @foreach($card->assignedMembers->take(3) as $member)
                                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800" 
                                                         title="{{ $member->full_name ?? $member->username }}">
                                                        {{ strtoupper(substr($member->full_name ?? $member->username, 0, 2)) }}
                                                    </div>
                                                @endforeach
                                                @if($card->assignedMembers->count() > 3)
                                                    <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800">
                                                        +{{ $card->assignedMembers->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <i class="fas fa-folder mr-1"></i>
                                                {{ $card->board->project->project_name }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-columns mr-1"></i>
                                                {{ $card->board->board_name }}
                                            </span>
                                            <span class="px-2 py-1 rounded-full font-semibold
                                                {{ $card->status === 'done' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                                {{ $card->status === 'in_progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                                {{ $card->status === 'review' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                                {{ $card->status === 'todo' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.projects.show', $card->board->project) }}" 
                                       class="ml-4 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-semibold transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 dark:text-gray-500 text-3xl"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 font-semibold">No assigned tasks yet</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Start assigning tasks to your team</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
