@extends('layouts.developer')

@section('title', $board->board_name . ' - Board View')

@push('styles')
<style>
    .card-item {
        transition: all 0.3s;
    }
    .card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .kanban-column {
        min-height: 400px;
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-[1600px] mx-auto">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('developer.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold transition shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Board Info -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-2 border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $board->board_name }}</h2>
                    <p class="text-gray-600 mt-1">Project: <span class="font-semibold">{{ $project->project_name }}</span></p>
                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                        <span>
                            <i class="fas fa-clipboard-list mr-1"></i>
                            {{ $board->cards->count() }} Total Cards
                        </span>
                        <span>
                            <i class="fas fa-user-check mr-1 text-blue-500"></i>
                            {{ $myAssignments->count() }} Assigned to Me
                        </span>
                    </div>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-2 border-blue-200 rounded-xl">
                    <span class="text-xs text-blue-600 font-semibold uppercase">Read-Only View</span>
                </div>
            </div>
        </div>

        <!-- ✅ KANBAN BOARD (Read-Only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- TO DO COLUMN --}}
            <div class="bg-gray-50 rounded-2xl p-4 kanban-column">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-700 flex items-center">
                        <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                        To Do
                    </h4>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-semibold">
                        {{ $board->cards->where('status', 'todo')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($board->cards->where('status', 'todo') as $card)
                        <x-developer.card-item :card="$card" :isAssigned="$myAssignments->contains($card->id)" />
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">No cards</p>
                    @endforelse
                </div>
            </div>

            {{-- IN PROGRESS COLUMN --}}
            <div class="bg-blue-50 rounded-2xl p-4 kanban-column">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-blue-700 flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        In Progress
                    </h4>
                    <span class="text-xs bg-blue-200 text-blue-700 px-2 py-1 rounded-full font-semibold">
                        {{ $board->cards->where('status', 'in_progress')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($board->cards->where('status', 'in_progress') as $card)
                        <x-developer.card-item :card="$card" :isAssigned="$myAssignments->contains($card->id)" />
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">No cards</p>
                    @endforelse
                </div>
            </div>

            {{-- REVIEW COLUMN --}}
            <div class="bg-yellow-50 rounded-2xl p-4 kanban-column">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-yellow-700 flex items-center">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                        Review
                    </h4>
                    <span class="text-xs bg-yellow-200 text-yellow-700 px-2 py-1 rounded-full font-semibold">
                        {{ $board->cards->where('status', 'review')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($board->cards->where('status', 'review') as $card)
                        <x-developer.card-item :card="$card" :isAssigned="$myAssignments->contains($card->id)" />
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">No cards</p>
                    @endforelse
                </div>
            </div>

            {{-- DONE COLUMN --}}
            <div class="bg-green-50 rounded-2xl p-4 kanban-column">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-green-700 flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        Done
                    </h4>
                    <span class="text-xs bg-green-200 text-green-700 px-2 py-1 rounded-full font-semibold">
                        {{ $board->cards->where('status', 'done')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($board->cards->where('status', 'done') as $card)
                        <x-developer.card-item :card="$card" :isAssigned="$myAssignments->contains($card->id)" />
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">No cards</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
