@extends('layouts.teamlead')

@section('title', $project->project_name . ' - Project Cards')
@section('page-title', $project->project_name)
@section('page-subtitle', 'All Cards')

@push('styles')
<style>
    .card-item {
        transition: all 0.3s;
        cursor: pointer;
    }
    .card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .kanban-column {
        min-height: 500px;
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-[1600px] mx-auto">

        <!-- Back Button & Add Card Button -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('teamlead.projects.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold transition shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Projects
            </a>

            <button onclick="openAddCardModal()"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold transition shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Add New Card
            </button>
        </div>

        <!-- Project Info -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-2 border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $project->project_name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $project->description }}</p>
                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                        <span>
                            <i class="fas fa-columns mr-1"></i>
                            {{ $project->boards->count() }} Boards
                        </span>
                        <span>
                            <i class="fas fa-clipboard-list mr-1"></i>
                            {{ $allCards->count() }} Total Cards
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    @if($project->deadline)
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                    </div>
                    @endif
                    <div class="mt-2">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ KANBAN BOARD - ALL CARDS FROM ALL BOARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- TO DO COLUMN --}}
            <div class="bg-gray-50 rounded-2xl p-4 kanban-column">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-700 flex items-center">
                        <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                        To Do
                    </h4>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-semibold">
                        {{ $allCards->where('status', 'todo')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($allCards->where('status', 'todo') as $card)
                        <x-teamlead.card-item :card="$card" />
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
                        {{ $allCards->where('status', 'in_progress')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($allCards->where('status', 'in_progress') as $card)
                        <x-teamlead.card-item :card="$card" />
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
                        {{ $allCards->where('status', 'review')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($allCards->where('status', 'review') as $card)
                        <x-teamlead.card-item :card="$card" />
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
                        {{ $allCards->where('status', 'done')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($allCards->where('status', 'done') as $card)
                        <x-teamlead.card-item :card="$card" />
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">No cards</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>

{{-- ✅ MODAL CREATE CARD (FIXED) --}}
<div id="addCardModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h3 class="text-xl font-bold text-gray-800">Add New Card</h3>
            <button onclick="closeAddCardModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- ✅ FIX: Dynamic form action berdasarkan board yang dipilih --}}
        <form id="addCardForm" action="" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Board Selection -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-columns text-purple-500 mr-1"></i>
                    Select Board *
                </label>
                <select name="board_id"
                        id="board_id"
                        required
                        onchange="updateFormAction()"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <option value="">-- Select Board --</option>
                    @foreach($project->boards as $board)
                        <option value="{{ $board->id }}">{{ $board->board_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-heading text-blue-500 mr-1"></i>
                    Card Title *
                </label>
                <input type="text"
                       name="card_title"
                       required
                       placeholder="Enter card title"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-purple-500 mr-1"></i>
                    Description
                </label>
                <textarea name="description"
                          rows="4"
                          placeholder="Add card description..."
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-red-500 mr-1"></i>
                        Due Date
                    </label>
                    <input type="date"
                           name="due_date"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-flag text-orange-500 mr-1"></i>
                        Priority
                    </label>
                    <select name="priority"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-tasks text-indigo-500 mr-1"></i>
                    Status
                </label>
                <select name="status"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="todo" selected>To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="review">Review</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <!-- Assign To Developers -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-users text-green-500 mr-1"></i>
                    Assign To (Developers)
                </label>
                <select name="assigned_to[]"
                        multiple
                        size="5"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                    @forelse($developers ?? [] as $developer)
                        <option value="{{ $developer->id }}">
                            {{ $developer->full_name }} ({{ ucfirst($developer->role) }})
                        </option>
                    @empty
                        <option disabled>No developers available</option>
                    @endforelse
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Hold Ctrl/Cmd to select multiple
                </p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button"
                        onclick="closeAddCardModal()"
                        class="px-6 py-2.5 border-2 border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold transition shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create Card
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAddCardModal() {
    document.getElementById('addCardModal').classList.remove('hidden');
}

function closeAddCardModal() {
    document.getElementById('addCardModal').classList.add('hidden');
}

// ✅ FIX: Update form action berdasarkan board yang dipilih
function updateFormAction() {
    const boardId = document.getElementById('board_id').value;
    const form = document.getElementById('addCardForm');
    
    if (boardId) {
        form.action = `/teamlead/boards/${boardId}/cards`;
    }
}

// ✅ Set default action saat modal dibuka (pakai board pertama)
document.addEventListener('DOMContentLoaded', function() {
    const boardSelect = document.getElementById('board_id');
    if (boardSelect && boardSelect.options.length > 1) {
        boardSelect.selectedIndex = 1; // Pilih board pertama (skip placeholder)
        updateFormAction();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAddCardModal();
});

document.getElementById('addCardModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAddCardModal();
});
</script>
@endpush
