<div class="space-y-6">
    <!-- Task List dengan Blocker Alert -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">My Tasks</h2>
            <div class="flex items-center space-x-2 text-sm">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                    {{ $todoTasks->count() ?? 0 }} Active
                </span>
                @php
                    $blockedTasks = $todoTasks->filter(fn($t) => ($t->card->status ?? '') === 'blocker');
                @endphp
                @if($blockedTasks->count() > 0)
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-semibold animate-pulse">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ $blockedTasks->count() }} Blocked
                </span>
                @endif
            </div>
        </div>

        @forelse($todoTasks ?? [] as $task)
        <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition
                    {{ ($task->card->status ?? '') === 'blocker' ? 'bg-red-50 border-red-300' : 'bg-white' }}">

            <!-- Blocker Banner -->
            @if(($task->card->status ?? '') === 'blocker')
            <div class="bg-red-500 text-white px-4 py-2 rounded-lg mb-3 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-ban text-xl mr-3 animate-pulse"></i>
                    <div>
                        <p class="font-bold">BLOCKED TASK</p>
                        <p class="text-xs opacity-90">This task has been blocked and requires attention</p>
                    </div>
                </div>
                <button onclick="openSolveBlockerModal({{ $task->card->id }}, '{{ addslashes($task->card->card_title) }}')"
                        class="bg-white text-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-wrench mr-2"></i>Solve Blocker
                </button>
            </div>
            @endif

            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <h3 class="font-bold text-gray-800 text-lg">{{ $task->card->card_title ?? 'Untitled' }}</h3>

                        <!-- Priority Badge -->
                        <span class="px-2 py-1 rounded-full text-xs font-bold
                            {{ $task->card->priority === 'high' ? 'bg-red-500 text-white' :
                               ($task->card->priority === 'medium' ? 'bg-orange-500 text-white' : 'bg-green-500 text-white') }}">
                            {{ strtoupper($task->card->priority ?? 'LOW') }}
                        </span>

                        <!-- Status Badge -->
                        @if(($task->card->status ?? '') !== 'blocker')
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $task->card->status ?? 'todo')) }}
                        </span>
                        @endif
                    </div>

                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $task->card->description ?? 'No description' }}</p>

                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                        <span class="flex items-center">
                            <i class="far fa-folder mr-1"></i>
                            {{ $task->card->board->project->project_name ?? 'No Project' }}
                        </span>
                        @if($task->card->due_date)
                        <span class="flex items-center">
                            <i class="far fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($task->card->due_date)->format('d M Y') }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="ml-4 flex flex-col space-y-2">
                    @if(($task->card->status ?? '') === 'blocker')
                        <!-- Blocked Task Actions -->
                        <button onclick="openSolveBlockerModal({{ $task->card->id }}, '{{ addslashes($task->card->card_title) }}')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                            <i class="fas fa-wrench mr-1"></i>Solve
                        </button>
                    @else
                        <!-- Normal Task Actions -->
                        <form action="{{ route('developer.start-task', $task->card_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                                <i class="fas fa-play mr-1"></i>Start
                            </button>
                        </form>

                        <button onclick="openBlockModal({{ $task->card_id }}, '{{ addslashes($task->card->card_title) }}')"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                            <i class="fas fa-ban mr-1"></i>Block
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-medium">No tasks available</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Block Task -->
<div id="blockModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Block Task</h3>
            <button onclick="closeBlockModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="mb-4">
            <p class="text-gray-600 mb-2">Task: <span id="blockTaskTitle" class="font-semibold"></span></p>
            <p class="text-sm text-gray-500">Please provide a reason for blocking this task:</p>
        </div>

        <form id="blockForm" method="POST">
            @csrf
            <textarea name="blocker_reason" rows="4" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 mb-4"
                      placeholder="Describe the issue preventing you from working on this task..."></textarea>

            <div class="flex space-x-2">
                <button type="button" onclick="closeBlockModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-ban mr-2"></i>Block Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Solve Blocker -->
<div id="solveBlockerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Solve Blocker</h3>
            <button onclick="closeSolveBlockerModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-700 font-semibold mb-1">
                <i class="fas fa-check-circle mr-2"></i>Blocker Resolved
            </p>
            <p class="text-sm text-green-600">Task: <span id="solveTaskTitle" class="font-semibold"></span></p>
        </div>

        <form id="solveForm" method="POST">
            @csrf
            <label class="block text-sm font-semibold text-gray-700 mb-2">Solution Description:</label>
            <textarea name="solution_notes" rows="4" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 mb-4"
                      placeholder="Explain how you solved the blocker..."></textarea>

            <div class="flex space-x-2">
                <button type="button" onclick="closeSolveBlockerModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-check mr-2"></i>Mark as Resolved
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openBlockModal(cardId, taskTitle) {
    document.getElementById('blockTaskTitle').textContent = taskTitle;
    document.getElementById('blockForm').action = `/developer/cards/${cardId}/block`;
    document.getElementById('blockModal').classList.remove('hidden');
}

function closeBlockModal() {
    document.getElementById('blockModal').classList.add('hidden');
}

function openSolveBlockerModal(cardId, taskTitle) {
    document.getElementById('solveTaskTitle').textContent = taskTitle;
    document.getElementById('solveForm').action = `/developer/cards/${cardId}/solve-blocker`;
    document.getElementById('solveBlockerModal').classList.remove('hidden');
}

function closeSolveBlockerModal() {
    document.getElementById('solveBlockerModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('blockModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeBlockModal();
});

document.getElementById('solveBlockerModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeSolveBlockerModal();
});
</script>
@endpush
