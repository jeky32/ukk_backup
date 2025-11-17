@extends('layouts.teamlead')

@section('title', 'Project Detail - ' . $project->project_name)
@section('page-title', $project->project_name)
@section('page-subtitle', $project->description)

@push('styles')
<style>
    .board-scroll {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding: 1rem 0;
        scroll-behavior: smooth;
    }

    .board-scroll::-webkit-scrollbar {
        height: 8px;
    }

    .board-scroll::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }

    .board-scroll::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 4px;
    }

    .board-column {
        min-width: 300px;
        max-width: 300px;
    }

    .card-item {
        transition: all 0.2s ease;
    }

    .card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Header Berwarna -->
    <div class="bg-blue-500 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4 flex-1">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-folder-open text-white text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold mb-1">{{ $project->project_name }}</h1>
                    <p class="text-sm text-blue-100">{{ $project->description }}</p>

                    @php
                        $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                        $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                        $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                    @endphp

                    <div class="flex items-center space-x-6 mt-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium">Progress:</span>
                            <span class="text-xl font-bold">{{ $progress }}%</span>
                        </div>
                        @if($project->deadline)
                        <div class="flex items-center space-x-2 text-sm bg-white/20 rounded-lg px-3 py-1">
                            <i class="far fa-calendar"></i>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('teamlead.projects.index') }}"
               class="px-5 py-2.5 bg-white hover:bg-blue-50 text-blue-600 rounded-lg font-semibold transition-colors flex items-center text-sm shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Statistics Berwarna -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $statCards = [
                ['label' => 'Total Tasks', 'value' => $stats['total_tasks'] ?? 0, 'icon' => 'fa-tasks', 'bg' => 'bg-blue-500', 'badge' => 'bg-blue-100 text-blue-700'],
                ['label' => 'In Progress', 'value' => $stats['in_progress'] ?? 0, 'icon' => 'fa-spinner', 'bg' => 'bg-orange-500', 'badge' => 'bg-orange-100 text-orange-700'],
                ['label' => 'Review', 'value' => $stats['review'] ?? 0, 'icon' => 'fa-eye', 'bg' => 'bg-teal-500', 'badge' => 'bg-teal-100 text-teal-700'],
                ['label' => 'Completed', 'value' => $stats['done'] ?? 0, 'icon' => 'fa-check-circle', 'bg' => 'bg-green-500', 'badge' => 'bg-green-100 text-green-700']
            ];
        @endphp

        @foreach($statCards as $stat)
        <div class="{{ $stat['bg'] }} rounded-lg shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-3">
                <i class="fas {{ $stat['icon'] }} text-2xl opacity-80"></i>
                <span class="px-2 py-1 bg-white/20 rounded-full text-xs font-bold">STAT</span>
            </div>
            <p class="text-3xl font-bold mb-1">{{ $stat['value'] }}</p>
            <p class="text-sm opacity-90 font-medium">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Team Members dengan Warna -->
    <div class="bg-white rounded-lg shadow-md border-2 border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-users text-white"></i>
            </div>
            Team Members
            <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                {{ $project->members->count() }}
            </span>
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($project->members as $member)
                @php
                    $memberColors = ['bg-blue-500', 'bg-green-500', 'bg-orange-500', 'bg-teal-500', 'bg-pink-500', 'bg-indigo-500'];
                    $memberColor = $memberColors[$loop->index % count($memberColors)];
                @endphp
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-blue-300 transition-colors">
                <div class="w-10 h-10 {{ $memberColor }} rounded-full flex items-center justify-center text-white font-bold shadow-md">
                    {{ strtoupper(substr($member->full_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $member->full_name }}</p>
                    <p class="text-xs text-gray-600 font-medium">{{ ucfirst($member->pivot->role ?? $member->role) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Boards dengan Warna Berbeda -->
    <div class="bg-white rounded-lg shadow-md border-2 border-gray-200 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-columns text-white"></i>
                </div>
                Project Boards
                <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                    {{ $project->boards->count() }}
                </span>
            </h3>

            <div class="flex space-x-2">
                <button onclick="document.querySelector('.board-scroll').scrollBy({left: -320, behavior: 'smooth'})"
                        class="w-9 h-9 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="document.querySelector('.board-scroll').scrollBy({left: 320, behavior: 'smooth'})"
                        class="w-9 h-9 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="board-scroll">
            @foreach($project->boards as $board)
                @php
                    $boardColors = [
                        'To Do' => ['bg' => 'bg-gray-500', 'light' => 'bg-gray-50', 'border' => 'border-gray-200', 'icon' => 'clipboard-list'],
                        'In Progress' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50', 'border' => 'border-blue-200', 'icon' => 'spinner'],
                        'Review' => ['bg' => 'bg-orange-500', 'light' => 'bg-orange-50', 'border' => 'border-orange-200', 'icon' => 'eye'],
                        'Done' => ['bg' => 'bg-green-500', 'light' => 'bg-green-50', 'border' => 'border-green-200', 'icon' => 'check-circle']
                    ];
                    $color = $boardColors[$board->board_name] ?? ['bg' => 'bg-gray-500', 'light' => 'bg-gray-50', 'border' => 'border-gray-200', 'icon' => 'clipboard'];
                @endphp

                <div class="board-column">
                    <div class="{{ $color['bg'] }} rounded-t-lg p-4 shadow-md">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-{{ $color['icon'] }}"></i>
                                <h4 class="font-bold">{{ $board->board_name }}</h4>
                            </div>
                            <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">
                                {{ $board->cards->count() }}
                            </span>
                        </div>
                    </div>

                    <div class="{{ $color['light'] }} rounded-b-lg p-3 space-y-2 min-h-[300px] border-l-2 border-r-2 border-b-2 {{ $color['border'] }}">
                        @forelse($board->cards as $card)
                        <div class="card-item bg-white rounded-lg border-2 {{ $color['border'] }} p-3 shadow-sm">
                            <h5 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">
                                {{ $card->card_title }}
                            </h5>

                            @if($card->description)
                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $card->description }}</p>
                            @endif

                            <div class="flex items-center justify-between text-xs">
                                @if($card->assignedMembers && $card->assignedMembers->count() > 0)
                                <div class="flex -space-x-1">
                                    @foreach($card->assignedMembers->take(3) as $member)
                                        @php
                                            $avatarColors = ['bg-blue-500', 'bg-green-500', 'bg-orange-500', 'bg-teal-500', 'bg-pink-500'];
                                            $avatarColor = $avatarColors[$loop->index % count($avatarColors)];
                                        @endphp
                                    <div class="w-6 h-6 {{ $avatarColor }} rounded-full flex items-center justify-center text-white font-bold text-xs border-2 border-white shadow-sm"
                                         title="{{ $member->full_name }}">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                    </div>
                                    @endforeach
                                    @if($card->assignedMembers->count() > 3)
                                    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold text-xs border-2 border-white shadow-sm">
                                        +{{ $card->assignedMembers->count() - 3 }}
                                    </div>
                                    @endif
                                </div>
                                @else
                                <span class="text-gray-400">Unassigned</span>
                                @endif

                                @if($card->subtasks && $card->subtasks->count() > 0)
                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-700 font-medium">
                                    <i class="fas fa-list-ul mr-1"></i>
                                    {{ $card->subtasks->where('status', 'done')->count() }}/{{ $card->subtasks->count() }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm font-medium">No tasks</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
