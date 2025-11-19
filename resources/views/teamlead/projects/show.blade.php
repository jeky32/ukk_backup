@extends('layouts.teamlead')

@section('title', $project->project_name . ' - Project')
@section('page-title', $project->project_name)
@section('page-subtitle', 'Manage boards and monitor progress')

@push('styles')
<style>
    .board-column {
        min-width: 320px;
        max-width: 320px;
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
    }

    .board-scroll {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }

    .board-scroll::-webkit-scrollbar {
        height: 8px;
    }

    .board-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .board-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .card-item {
        background: white;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 12px;
        border: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }

    .card-item:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        transform: translateY(-2px);
    }

    .status-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-todo { background: #e5e7eb; color: #374151; }
    .status-in_progress { background: #dbeafe; color: #1e40af; }
    .status-review { background: #fef3c7; color: #92400e; }
    .status-done { background: #d1fae5; color: #065f46; }
    .status-blocker { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="px-4 py-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border-2 border-indigo-100">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-project-diagram text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->project_name }}</h1>
                    <p class="text-gray-600">{{ $project->description }}</p>
                </div>
            </div>
            <a href="{{ route('teamlead.dashboard') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 mt-4">
            @php
                $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
            @endphp
            <div class="text-center p-4 bg-blue-50 rounded-xl">
                <p class="text-3xl font-bold text-blue-600">{{ $project->boards->count() }}</p>
                <p class="text-sm text-gray-600 font-medium">Boards</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-xl">
                <p class="text-3xl font-bold text-purple-600">{{ $totalCards }}</p>
                <p class="text-sm text-gray-600 font-medium">Total Tasks</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-xl">
                <p class="text-3xl font-bold text-green-600">{{ $doneCards }}</p>
                <p class="text-sm text-gray-600 font-medium">Completed</p>
            </div>
            <div class="text-center p-4 bg-indigo-50 rounded-xl">
                <p class="text-3xl font-bold text-indigo-600">{{ $progress }}%</p>
                <p class="text-sm text-gray-600 font-medium">Progress</p>
            </div>
        </div>
    </div>

    <!-- Kanban Boards -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-indigo-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold flex items-center">
                <i class="fas fa-columns mr-3 text-indigo-600"></i>
                Boards & Tasks
            </h2>

            <div class="flex items-center space-x-3">
                <!-- ✅ BUTTON ADD CARD -->
                <button onclick="openModalAddCard()"
                        class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-semibold transition shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Add Card
                </button>

                <!-- Scroll Navigation -->
                <button onclick="document.querySelector('.board-scroll').scrollBy({left: -380, behavior: 'smooth'})"
                        class="w-10 h-10 bg-gray-100 hover:bg-indigo-100 rounded-xl flex items-center justify-center transition">
                    <i class="fas fa-chevron-left text-gray-600"></i>
                </button>
                <button onclick="document.querySelector('.board-scroll').scrollBy({left: 380, behavior: 'smooth'})"
                        class="w-10 h-10 bg-gray-100 hover:bg-indigo-100 rounded-xl flex items-center justify-center transition">
                    <i class="fas fa-chevron-right text-gray-600"></i>
                </button>
            </div>
        </div>

        <!-- ✅ BOARDS HORIZONTAL SCROLL -->
        <div class="board-scroll">
            @forelse($project->boards as $board)
                <!-- Board Column -->
                <div class="board-column">
                    <!-- Board Header -->
                    <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-indigo-200">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $board->board_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $board->cards->count() }} tasks</p>
                        </div>
                        <a href="{{ route('teamlead.boards.show', [$project->id, $board->id]) }}"
                           class="w-9 h-9 bg-indigo-100 hover:bg-indigo-200 rounded-lg flex items-center justify-center transition">
                            <i class="fas fa-arrow-right text-indigo-600"></i>
                        </a>
                    </div>

                    <!-- Cards Preview (max 5) -->
                    <div class="space-y-3 mb-3 max-h-96 overflow-y-auto">
                        @foreach($board->cards->take(5) as $card)
                        <a href="{{ route('teamlead.cards.show', $card->id) }}" class="block">
                            <div class="card-item">
                                <!-- Card Title -->
                                <h4 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2">
                                    {{ $card->card_title }}
                                </h4>

                                <!-- Card Meta -->
                                <div class="flex items-center justify-between text-xs">
                                    <span class="status-badge status-{{ $card->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                                    </span>

                                    @if($card->due_date)
                                    <span class="text-gray-500">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($card->due_date)->format('M d') }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Assigned Users -->
                                @if($card->assignments && $card->assignments->count() > 0)
                                <div class="flex -space-x-2 mt-2">
                                    @foreach($card->assignments->take(3) as $assignment)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignment->user->full_name) }}&size=32&background=random"
                                         class="w-6 h-6 rounded-full border-2 border-white"
                                         title="{{ $assignment->user->full_name }}">
                                    @endforeach
                                    @if($card->assignments->count() > 3)
                                    <div class="w-6 h-6 bg-gray-400 rounded-full border-2 border-white flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">+{{ $card->assignments->count() - 3 }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </a>
                        @endforeach

                        @if($board->cards->count() > 5)
                        <div class="text-center py-2">
                            <a href="{{ route('teamlead.boards.show', [$project->id, $board->id]) }}"
                               class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                                View {{ $board->cards->count() - 5 }} more →
                            </a>
                        </div>
                        @endif

                        @if($board->cards->count() === 0)
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-xs">No cards yet</p>
                        </div>
                        @endif
                    </div>

                    <!-- View All Button -->
                    <a href="{{ route('teamlead.boards.show', [$project->id, $board->id]) }}"
                       class="block w-full py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-center text-sm font-semibold transition">
                        <i class="fas fa-eye mr-2"></i>View Board
                    </a>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-columns text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Boards Yet</h3>
                    <p class="text-gray-500 mb-6">Start by creating a card - boards are automatically managed</p>
                    <button onclick="openModalAddCard()"
                            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-semibold transition shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Create First Card
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Board Count Info -->
        @if($project->boards->count() > 0)
        <div class="mt-4 text-center text-sm text-gray-500">
            Showing {{ $project->boards->count() }} {{ Str::plural('board', $project->boards->count()) }}
        </div>
        @endif
    </div>
</div>

<!-- ✅ MODAL ADD CARD -->
<div id="modalAddCard" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
        <form action="{{ route('teamlead.cards.store', ['board' => 'auto']) }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-plus-circle text-indigo-600 mr-2"></i>Add New Card
                </h3>
                <button type="button" onclick="closeModalAddCard()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-5">
                <!-- Card Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading text-indigo-500 mr-2"></i>
                        Card Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="card_title" required
                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                           placeholder="Enter card title">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left text-purple-500 mr-2"></i>Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                              placeholder="Add card description..."></textarea>
                </div>

                <!-- Due Date & Priority -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-red-500 mr-2"></i>Due Date
                        </label>
                        <input type="date" name="due_date" min="{{ date('Y-m-d') }}"
                               class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag text-orange-500 mr-2"></i>Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" required
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                            <option value="medium" selected>Medium</option>
                            <option value="low">Low</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <!-- ✅ Status (Locked to "To Do") -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tasks text-purple-500 mr-2"></i>Status
                    </label>
                    <div class="px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl flex items-center justify-between">
                        <span class="flex items-center text-gray-700 font-semibold">
                            <span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span>To Do
                        </span>
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="hidden" name="status" value="todo">
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                        New cards are automatically set to "To Do" status
                    </p>
                </div>

                <!-- ✅✅ Assign To (FIXED) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-green-500 mr-2"></i>Assign To Developer
                    </label>
                    @php
                        // ✅ FIXED: $project->members sudah return User, bukan pivot
                        $developers = $project->members()->whereIn('users.role', ['developer', 'designer'])->get();
                    @endphp
                    @if($developers->count() > 0)
                        <div class="space-y-2 max-h-48 overflow-y-auto border-2 border-gray-200 rounded-xl p-3 bg-gray-50">
                            @foreach($developers as $developer)
                                <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white transition cursor-pointer">
                                    <input type="checkbox" name="assigned_to[]" value="{{ $developer->id }}"
                                           class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                    <div class="flex items-center space-x-2">
                                        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($developer->full_name ?? $developer->username, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $developer->full_name ?? $developer->username }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($developer->role) }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>No developers available. Please add developers to project first.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end space-x-3 rounded-b-2xl">
                <button type="button" onclick="closeModalAddCard()"
                        class="px-5 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-xl transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl transition shadow-lg">
                    <i class="fas fa-save mr-2"></i>Create Card
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Modal Functions
function openModalAddCard() {
    document.getElementById('modalAddCard').classList.remove('hidden');
}

function closeModalAddCard() {
    document.getElementById('modalAddCard').classList.add('hidden');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModalAddCard();
    }
});

// Close modal on backdrop click
document.getElementById('modalAddCard').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalAddCard();
    }
});

// Smooth scroll behavior
document.addEventListener('DOMContentLoaded', function() {
    const boardScroll = document.querySelector('.board-scroll');
    if (boardScroll) {
        const firstIncomplete = boardScroll.querySelector('.board-column');
        if (firstIncomplete) {
            firstIncomplete.scrollIntoView({ behavior: 'smooth', inline: 'start' });
        }
    }
});
</script>
@endpush
