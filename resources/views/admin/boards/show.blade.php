@extends('layouts.admin')

@section('title', $board->board_name . ' - Board')

@section('page-title', $board->board_name)
@section('page-subtitle', $project->project_name)

@push('styles')
<style>
    .board-column {
        min-height: 500px;
        background: linear-gradient(to bottom, rgba(255,255,255,0.95), rgba(255,255,255,0.98));
    }
    .card-item {
        cursor: pointer;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #f3f4f6;
    }
    .column-header {
        backdrop-filter: blur(8px);
        background: rgba(255,255,255,0.9);
        border-bottom: 2px solid;
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20 min-h-screen">
    <!-- Board Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.projects.show', $project) }}"
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        {{ $board->board_name }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $project->project_name }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Team Members -->
                <div class="flex -space-x-3">
                    @foreach($project->members->take(5) as $member)
                    <div class="relative group">
                        <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                             alt="{{ $member->full_name }}"
                             class="w-10 h-10 rounded-full border-3 border-white shadow-md ring-2 ring-gray-100">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-10 transition">
                            {{ $member->full_name }}
                        </div>
                    </div>
                    @endforeach
                    @if($project->members->count() > 5)
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 border-3 border-white shadow-md flex items-center justify-center ring-2 ring-gray-100">
                        <span class="text-xs font-bold text-white">+{{ $project->members->count() - 5 }}</span>
                    </div>
                    @endif
                </div>

                <!-- Add List Button -->
                <button onclick="openAddListModal()"
                        class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 font-medium transition">
                    <i class="fas fa-plus"></i>
                    <span>Add List</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Kanban Board with 4 Columns -->
    <div class="flex space-x-5 overflow-x-auto pb-4" x-data="kanbanBoard()">
        @foreach([
            'todo' => ['label' => 'To Do', 'color' => 'gray'],
            'in_progress' => ['label' => 'In Progress', 'color' => 'blue'],
            'review' => ['label' => 'Review', 'color' => 'purple'],
            'done' => ['label' => 'Done', 'color' => 'green']
        ] as $status => $config)
        <div class="flex-shrink-0 w-80">
            <div class="bg-white rounded-2xl shadow-md border border-{{ $config['color'] }}-200 overflow-hidden">
                <div class="column-header px-5 py-4 bg-gradient-to-r from-{{ $config['color'] }}-50 to-{{ $config['color'] }}-100/50 border-{{ $config['color'] }}-300 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-3 h-3 bg-gradient-to-br from-{{ $config['color'] }}-500 to-{{ $config['color'] }}-700 rounded-full shadow-md"></div>
                                @if($status !== 'todo')
                                <div class="absolute inset-0 bg-{{ $config['color'] }}-400 rounded-full opacity-50 blur-sm"></div>
                                @endif
                            </div>
                            <h3 class="font-bold text-{{ $config['color'] }}-900 text-lg">{{ $config['label'] }}</h3>
                            <span class="px-2.5 py-1 text-xs font-bold bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 rounded-lg border border-{{ $config['color'] }}-200">
                                {{ $board->cards->where('status', $status)->count() }}
                            </span>
                        </div>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-400 hover:text-{{ $config['color'] }}-600 transition">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>

                <div class="p-4 space-y-3 board-column bg-gradient-to-b from-{{ $config['color'] }}-50/30 to-white" id="{{ $status }}-cards">
                    @foreach($board->cards->where('status', $status) as $card)
                        <a href="{{ route('admin.cards.show', $card->id) }}" class="block">
                            @include('components.card-item', ['card' => $card])
                        </a>
                    @endforeach
                </div>

                <div class="p-4 pt-0">
                    <button onclick="openAddCardModal('{{ $status }}')"
                            class="w-full px-4 py-3 bg-{{ $config['color'] }}-50 border-2 border-dashed border-{{ $config['color'] }}-300 rounded-xl text-{{ $config['color'] }}-700 hover:border-{{ $config['color'] }}-400 hover:bg-{{ $config['color'] }}-100 font-medium transition">
                        <i class="fas fa-plus mr-2"></i>Add Card
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Card Modal -->
<div id="addCardModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h3 class="text-xl font-bold text-gray-800">Add New Card</h3>
            <button onclick="closeAddCardModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.cards.store') }}" method="POST" id="addCardForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="board_id" value="{{ $board->id }}">
            <input type="hidden" name="status" id="card_status">

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-heading text-blue-500 mr-1"></i>
                    Card Title *
                </label>
                <input type="text"
                       name="card_title"
                       required
                       placeholder="Enter card title"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left text-purple-500 mr-1"></i>
                    Description
                </label>
                <textarea name="description"
                          rows="4"
                          placeholder="Add card description..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar text-red-500 mr-1"></i>
                        Due Date
                    </label>
                    <input type="date"
                           name="due_date"
                           id="dueDateInput"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-flag text-orange-500 mr-1"></i>
                        Priority
                    </label>
                    <select name="priority"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <!-- Assigned To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-users text-green-500 mr-1"></i>
                    Assign To
                </label>
                <select name="assigned_to[]"
                        multiple
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    @foreach($project->members as $member)
                        <option value="{{ $member->user_id }}">{{ $member->user->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button"
                        onclick="closeAddCardModal()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create Card
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add List Modal -->
<div id="addListModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 border border-gray-200">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Add New List</h3>
            <button onclick="closeAddListModal()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.boards.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">List Name *</label>
                <input type="text"
                       name="board_name"
                       required
                       placeholder="e.g., Testing, Deployment"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium transition">
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button"
                        onclick="closeAddListModal()"
                        class="px-6 py-2.5 border-2 border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 font-medium transition">
                    Create List
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Due date validation
document.addEventListener('DOMContentLoaded', function() {
    const dueDateInput = document.getElementById('dueDateInput');

    if (dueDateInput) {
        const today = new Date().toISOString().split('T')[0];
        dueDateInput.setAttribute('min', today);

        dueDateInput.addEventListener('change', function() {
            if (this.value && this.value < today) {
                alert('⚠️ Tanggal tidak boleh di masa lalu!');
                this.value = '';
            }
        });
    }

    const form = document.getElementById('addCardForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const selectedDate = dueDateInput.value;
            const today = new Date().toISOString().split('T')[0];

            if (selectedDate && selectedDate < today) {
                e.preventDefault();
                alert('❌ Tanggal deadline tidak boleh di masa lalu!');
                dueDateInput.focus();
                return false;
            }
        });
    }
});

// Modal functions
function openAddCardModal(status) {
    document.getElementById('card_status').value = status;
    document.getElementById('addCardModal').classList.remove('hidden');
}

function closeAddCardModal() {
    document.getElementById('addCardModal').classList.add('hidden');
}

function openAddListModal() {
    document.getElementById('addListModal').classList.remove('hidden');
}

function closeAddListModal() {
    document.getElementById('addListModal').classList.add('hidden');
}

function kanbanBoard() {
    return {
        init() {
            console.log('Kanban board initialized with 4 columns');
        }
    }
}

// Close modals with ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddCardModal();
        closeAddListModal();
    }
});

// Close modals on backdrop click
document.getElementById('addCardModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAddCardModal();
});

document.getElementById('addListModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAddListModal();
});
</script>
@endpush
