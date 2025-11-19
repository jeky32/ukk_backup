@extends('layouts.teamlead')

@section('title', $board->board_name . ' - Board')
@section('page-title', $board->board_name)
@section('page-subtitle', 'Project: ' . $project->project_name)

@push('styles')
<style>
    .kanban-column {
        min-width: 300px;
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
    }

    .kanban-scroll {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
    }

    .card-item {
        background: white;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 12px;
        border: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
        position: relative;
    }

    .card-item:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        transform: translateY(-2px);
    }

    .card-item:hover .card-actions {
        opacity: 1;
    }

    .card-actions {
        position: absolute;
        top: 8px;
        right: 8px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .priority-badge {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-low { background: #d1fae5; color: #065f46; }
    .priority-medium { background: #fed7aa; color: #92400e; }
    .priority-high { background: #fecaca; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="px-4 py-6" x-data="{
    showAssignModal: false,
    selectedCard: null,
    selectedCardTitle: '',
    currentAssignments: []
}">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border-2 border-indigo-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('teamlead.projects.show', $project->id) }}"
                       class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Project
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $board->board_name }}</h1>
                <p class="text-gray-600">{{ $board->description ?? 'No description' }}</p>
            </div>

            <button onclick="openModalAddCard()"
                    class="px-5 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-semibold transition shadow-lg">
                <i class="fas fa-plus mr-2"></i>Add Card
            </button>
        </div>

        <!-- Board Stats -->
        <div class="grid grid-cols-5 gap-4 mt-4">
            @php
                $statusCounts = [
                    'todo' => $board->cards->where('status', 'todo')->count(),
                    'in_progress' => $board->cards->where('status', 'in_progress')->count(),
                    'review' => $board->cards->where('status', 'review')->count(),
                    'done' => $board->cards->where('status', 'done')->count(),
                    'blocked' => $board->cards->where('status', 'blocked')->count(),
                ];
            @endphp
            <div class="text-center p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                <p class="text-2xl font-bold text-gray-600">{{ $statusCounts['todo'] }}</p>
                <p class="text-xs text-gray-600 font-medium">To Do</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-xl border-2 border-blue-200">
                <p class="text-2xl font-bold text-blue-600">{{ $statusCounts['in_progress'] }}</p>
                <p class="text-xs text-blue-600 font-medium">In Progress</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-xl border-2 border-yellow-200">
                <p class="text-2xl font-bold text-yellow-600">{{ $statusCounts['review'] }}</p>
                <p class="text-xs text-yellow-600 font-medium">Review</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-xl border-2 border-green-200">
                <p class="text-2xl font-bold text-green-600">{{ $statusCounts['done'] }}</p>
                <p class="text-xs text-green-600 font-medium">Done</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-xl border-2 border-red-200">
                <p class="text-2xl font-bold text-red-600">{{ $statusCounts['blocked'] }}</p>
                <p class="text-xs text-red-600 font-medium">Blocked</p>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-indigo-100">
        <div class="kanban-scroll">
            @foreach(['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'done' => 'Done', 'blocked' => 'Blocked'] as $status => $label)
                <div class="kanban-column">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-indigo-200">
                        <h3 class="font-bold text-gray-900">{{ $label }}</h3>
                        <span class="text-xs font-bold text-gray-500 bg-gray-200 px-2 py-1 rounded-full">
                            {{ $board->cards->where('status', $status)->count() }}
                        </span>
                    </div>

                    <div class="space-y-3 min-h-[400px]">
                        @foreach($board->cards->where('status', $status) as $card)
                        <div class="card-item">
                            <!-- ✅ Card Actions (Assign Button) -->
                            <div class="card-actions">
                                <button @click="
                                    selectedCard = {{ $card->id }};
                                    selectedCardTitle = '{{ addslashes($card->card_title) }}';
                                    currentAssignments = {{ $card->assignments->map(fn($a) => ['id' => $a->user_id, 'name' => $a->user->full_name ?? $a->user->username])->toJson() }};
                                    showAssignModal = true;
                                "
                                        class="px-2 py-1 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-xs font-semibold transition shadow"
                                        onclick="event.stopPropagation()">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>

                            <a href="{{ route('teamlead.cards.show', $card->id) }}" class="block">
                                <h4 class="font-semibold text-gray-800 text-sm mb-2 pr-8">{{ $card->card_title }}</h4>

                                <div class="flex items-center justify-between text-xs mb-2">
                                    <span class="priority-badge priority-{{ $card->priority }}">
                                        {{ ucfirst($card->priority) }}
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
                                <div class="flex items-center -space-x-2 mb-2">
                                    @foreach($card->assignments->take(3) as $assignment)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignment->user->full_name ?? $assignment->user->username) }}&size=32&background=random"
                                         class="w-6 h-6 rounded-full border-2 border-white"
                                         title="{{ $assignment->user->full_name ?? $assignment->user->username }}">
                                    @endforeach
                                    @if($card->assignments->count() > 3)
                                    <div class="w-6 h-6 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600">
                                        +{{ $card->assignments->count() - 3 }}
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-semibold">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Not assigned
                                    </span>
                                </div>
                                @endif
                            </a>
                        </div>
                        @endforeach

                        @if($board->cards->where('status', $status)->count() === 0)
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p class="text-xs">No cards</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ✅ ASSIGN MODAL -->
    <div x-show="showAssignModal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center"
         x-transition
         @click.away="showAssignModal = false"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-500 to-blue-500">
                <h3 class="text-lg font-bold text-white">Assign Task to Developer</h3>
                <p class="text-sm text-blue-100 mt-1" x-text="selectedCardTitle"></p>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 max-h-96 overflow-y-auto">
                @if($developers->count() > 0)
                <form method="POST" :action="'/teamlead/cards/' + selectedCard + '/assign'" id="assignForm">
                    @csrf

                    <!-- Current Assignments -->
                    <div x-show="currentAssignments.length > 0" class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Currently Assigned:
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="assignment in currentAssignments" :key="assignment.id">
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    <span x-text="assignment.name"></span>
                                    <form :action="'/teamlead/cards/' + selectedCard + '/remove-assignment/' + assignment.id"
                                          method="POST"
                                          class="inline ml-2"
                                          @submit.prevent="if(confirm('Remove this assignment?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </form>
                                </span>
                            </template>
                        </div>
                        <hr class="my-4">
                    </div>

                    <!-- Developer List -->
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Select Developers to Assign:
                    </label>
                    <div class="space-y-2">
                        @foreach($developers as $developer)
                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                            <input type="checkbox"
                                   name="user_ids[]"
                                   value="{{ $developer->id }}"
                                   class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow">
                                    {{ strtoupper(substr($developer->username, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $developer->full_name ?? $developer->username }}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-code mr-1"></i>
                                        {{ ucfirst($developer->role) }}
                                    </p>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </form>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600">No developers available in this project.</p>
                    <p class="text-sm text-gray-400 mt-1">Please add developers to the project first.</p>
                </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                <button type="button"
                        @click="showAssignModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition">
                    Cancel
                </button>
                @if($developers->count() > 0)
                <button type="submit"
                        form="assignForm"
                        class="px-4 py-2 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg hover:from-purple-600 hover:to-blue-600 font-semibold transition shadow">
                    <i class="fas fa-user-plus mr-2"></i>
                    Assign
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ✅ MODAL ADD CARD -->
<div id="modalAddCard" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
        <form action="{{ route('teamlead.cards.store', ['board' => $board->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="board_id" value="{{ $board->id }}">

            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-plus-circle text-indigo-600 mr-2"></i>Add New Card
                </h3>
                <button type="button" onclick="closeModalAddCard()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="px-6 py-4 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading text-indigo-500 mr-2"></i>
                        Card Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="card_title" required
                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                           placeholder="Enter card title">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left text-purple-500 mr-2"></i>Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                              placeholder="Add card description..."></textarea>
                </div>

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

                <!-- ✅ Status Locked -->
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
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                        New cards are automatically set to "To Do"
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-green-500 mr-2"></i>Assign To
                    </label>
                    @php
                        $developersForCreate = $project->members->filter(fn($m) => in_array($m->role, ['developer', 'designer']));
                    @endphp
                    @if($developersForCreate->count() > 0)
                        <div class="space-y-2 max-h-48 overflow-y-auto border-2 border-gray-200 rounded-xl p-3 bg-gray-50">
                            @foreach($developersForCreate as $member)
                                <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white transition cursor-pointer">
                                    <input type="checkbox" name="assigned_to[]" value="{{ $member->id }}"
                                           class="w-4 h-4 text-indigo-600 rounded">
                                    <div class="flex items-center space-x-2">
                                        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($member->full_name ?? $member->username, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $member->full_name ?? $member->username }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($member->role) }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>No developers available
                        </div>
                    @endif
                </div>
            </div>

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
function openModalAddCard() {
    document.getElementById('modalAddCard').classList.remove('hidden');
}

function closeModalAddCard() {
    document.getElementById('modalAddCard').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModalAddCard();
    }
});
</script>
@endpush
