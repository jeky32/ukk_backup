@extends('layouts.teamlead')

@section('title', 'Monitoring - All Projects')
@section('page-title', 'Project Monitoring')
@section('page-subtitle', 'Real-time task progress tracking')

@push('styles')
<style>
    .progress-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
        background: white;
        border-radius: 12px;
    }
    .progress-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    .progress-card.low-progress {
        border-left-color: #ef4444;
    }
    .progress-card.medium-progress {
        border-left-color: #f59e0b;
    }
    .progress-card.high-progress {
        border-left-color: #10b981;
    }
    .progress-card.complete {
        border-left-color: #6366f1;
    }
    .stat-badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
    }
    .progress {
        height: 28px;
        border-radius: 14px;
        background: #e5e7eb;
        overflow: hidden;
    }
    .progress-bar {
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: width 0.5s ease;
    }
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
                        Monitoring Subtasks
                    </h2>
                    <p class="text-gray-600">Real-time task progress tracking across all projects</p>
                </div>
            </div>
        </div>

        @if(isset($error))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                <div>
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

            <!-- Total Cards -->
            <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">Total Cards</p>
                        <h3 class="text-4xl font-bold">{{ isset($allCardsWithProgress) ? count($allCardsWithProgress) : 0 }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-tasks text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Cards Completed -->
            <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-semibold mb-1">Cards Completed</p>
                        <h3 class="text-4xl font-bold">
                            {{ isset($allCardsWithProgress) ? collect($allCardsWithProgress)->where('progress', 100)->count() : 0 }}
                        </h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="stat-card bg-gradient-to-br from-yellow-500 to-orange-500 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-semibold mb-1">In Progress</p>
                        <h3 class="text-4xl font-bold">
                            {{ isset($allCardsWithProgress) ? collect($allCardsWithProgress)->where('progress', '>', 0)->where('progress', '<', 100)->count() : 0 }}
                        </h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-spinner text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Not Started -->
            <div class="stat-card bg-gradient-to-br from-red-500 to-pink-500 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-semibold mb-1">Not Started</p>
                        <h3 class="text-4xl font-bold">
                            {{ isset($allCardsWithProgress) ? collect($allCardsWithProgress)->where('progress', 0)->count() : 0 }}
                        </h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-pause-circle text-3xl"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Cards List with Progress -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                <h5 class="text-xl font-bold text-white mb-0">
                    <i class="fas fa-list-check mr-2"></i>
                    Daftar Cards dan Progress Subtasks
                </h5>
            </div>
            <div class="p-6">
                @if(isset($allCardsWithProgress) && count($allCardsWithProgress) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($allCardsWithProgress as $item)
                            @php
                                $card = $item['card'];
                                $board = $item['board'];
                                $project = $item['project'];
                                $progress = $item['progress'];
                                $subtasksCount = $item['subtasks_count'];

                                // Determine card class based on progress
                                if ($progress == 100) {
                                    $cardClass = 'complete';
                                    $progressColor = 'bg-indigo-500';
                                } elseif ($progress >= 50) {
                                    $cardClass = 'high-progress';
                                    $progressColor = 'bg-green-500';
                                } elseif ($progress > 0) {
                                    $cardClass = 'medium-progress';
                                    $progressColor = 'bg-yellow-500';
                                } else {
                                    $cardClass = 'low-progress';
                                    $progressColor = 'bg-red-500';
                                }

                                // Check due date status
                                $dueStatus = '';
                                $dueBadgeClass = '';
                                if (isset($card->due_date) && $card->due_date) {
                                    $daysUntil = now()->diffInDays($card->due_date, false);
                                    if ($daysUntil < 0) {
                                        $dueStatus = 'Overdue';
                                        $dueBadgeClass = 'bg-red-500';
                                    } elseif ($daysUntil <= 3) {
                                        $dueStatus = 'Due Soon';
                                        $dueBadgeClass = 'bg-yellow-500';
                                    }
                                }
                            @endphp

                            <div class="progress-card {{ $cardClass }} h-full">
                                <div class="p-6">
                                    <!-- Project Badge -->
                                    <div class="mb-3">
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">
                                            <i class="fas fa-folder"></i> {{ $project->project_name }}
                                        </span>
                                    </div>

                                    <!-- Card Title and Badges -->
                                    <div class="flex justify-between items-start mb-3">
                                        <h6 class="font-bold text-gray-800 text-base flex-grow pr-2">{{ $card->card_title }}</h6>
                                        <div class="flex gap-1 flex-wrap">
                                            @if(isset($card->priority))
                                                <span class="px-2 py-1 rounded-lg text-xs font-bold
                                                    @if($card->priority == 'high') bg-red-100 text-red-700
                                                    @elseif($card->priority == 'medium') bg-yellow-100 text-yellow-700
                                                    @else bg-green-100 text-green-700
                                                    @endif
                                                " title="Priority">
                                                    <i class="fas fa-flag"></i>
                                                </span>
                                            @endif
                                            @if($dueStatus)
                                                <span class="px-2 py-1 {{ $dueBadgeClass }} text-white rounded-lg text-xs font-bold" title="{{ $dueStatus }}">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold
                                            @if($card->status == 'done') bg-green-100 text-green-700
                                            @elseif($card->status == 'in_progress') bg-blue-100 text-blue-700
                                            @else bg-gray-100 text-gray-700
                                            @endif
                                        ">
                                            @if($card->status == 'done')
                                                <i class="fas fa-check-circle"></i> Done
                                            @elseif($card->status == 'in_progress')
                                                <i class="fas fa-spinner"></i> In Progress
                                            @else
                                                <i class="fas fa-circle"></i> To Do
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Board Name -->
                                    <p class="text-gray-600 text-sm mb-3">
                                        <i class="fas fa-columns text-indigo-500"></i> {{ $board->board_name }}
                                    </p>

                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs font-semibold text-gray-600">Progress</span>
                                            <span class="text-xs font-bold text-gray-800">{{ $progress }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="{{ $progressColor }} text-white font-bold rounded-full transition-all duration-500"
                                                 style="width: {{ $progress }}%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                                {{ $progress }}%
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subtasks Statistics -->
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1">Total Subtasks</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $subtasksCount['total'] }}</p>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3">
                                            <p class="text-xs text-green-600 mb-1">Completed</p>
                                            <p class="text-xl font-bold text-green-700">{{ $subtasksCount['done'] }}</p>
                                        </div>
                                    </div>

                                    <!-- Subtasks by Status -->
                                    <div class="flex gap-2 mb-4 flex-wrap">
                                        @if($subtasksCount['todo'] > 0)
                                            <span class="stat-badge bg-gray-100 text-gray-700">
                                                <i class="fas fa-circle"></i> Todo: {{ $subtasksCount['todo'] }}
                                            </span>
                                        @endif
                                        @if($subtasksCount['in_progress'] > 0)
                                            <span class="stat-badge bg-yellow-100 text-yellow-700">
                                                <i class="fas fa-play-circle"></i> In Progress: {{ $subtasksCount['in_progress'] }}
                                            </span>
                                        @endif
                                        @if($subtasksCount['review'] > 0)
                                            <span class="stat-badge bg-blue-100 text-blue-700">
                                                <i class="fas fa-eye"></i> Review: {{ $subtasksCount['review'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Assigned User -->
                                    @if(isset($card->assignments) && $card->assignments->isNotEmpty())
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-600 mb-1">
                                                <i class="fas fa-user text-indigo-500"></i> Assigned to:
                                            </p>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($card->assignments->take(3) as $assignment)
                                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold">
                                                        {{ $assignment->user->username ?? $assignment->user->full_name }}
                                                    </span>
                                                @endforeach
                                                @if($card->assignments->count() > 3)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold">
                                                        +{{ $card->assignments->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Due Date -->
                                    @if(isset($card->due_date) && $card->due_date)
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-600">
                                                <i class="fas fa-calendar text-indigo-500"></i>
                                                Due: {{ \Carbon\Carbon::parse($card->due_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <div class="mt-4">
                                    <a href="{{ route('teamlead.card.detail', $card->id) }}"
                                    class="block w-full text-center py-3 bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white rounded-xl font-semibold transition-all shadow-md hover:shadow-lg">
                                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                                    </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada cards</h3>
                        <p class="text-gray-500">Mulai dengan membuat cards di projects Anda</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
