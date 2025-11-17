<!-- resources/views/admin/projects/show.blade.php -->
@extends('layouts.admin')

@section('title', $project->project_name . ' - Project Detail')
@section('page-title', $project->project_name)
@section('page-subtitle', 'Project Overview & Boards')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Dashboard
        </a>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.projects.edit', $project) }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-edit mr-2"></i>Edit Project
            </a>
            <button onclick="openAddBoardModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Add Board
            </button>
        </div>
    </div>

    <!-- Project Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Description -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Description</h3>
                <p class="text-gray-600">{{ $project->description ?: 'No description provided.' }}</p>

                <!-- Project Meta -->
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div>
                        <p class="text-sm text-gray-500">Created By</p>
                        <p class="text-sm font-semibold text-gray-800"><?php //{{ $project->creator->full_name }} ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created At</p>
                        <p class="text-sm font-semibold text-gray-800">
                            <?php //{{ $project->created_at->format('M d, Y') }} ?>
                        </p>
                    </div>
                    @if($project->deadline)
                    <div>
                        <p class="text-sm text-gray-500">Deadline</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Team Members -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Team Members</h3>
                <div class="space-y-3">
                    @foreach($project->members as $member)
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                             alt="{{ $member->full_name }}"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?php //{{ $member->full_name }} ?></p>
                            <p class="text-xs text-gray-500">{{ $member->email }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Project Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 mb-1">Total Boards</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $project->boards->count() }}</h3>
        </div>

        @php
            $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
            $todoCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count());
            $inProgressCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count());
            $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
        @endphp

        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600 mb-1">To Do</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $todoCards }}</h3>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
            <p class="text-sm text-gray-600 mb-1">In Progress</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $inProgressCards }}</h3>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-600 mb-1">Completed</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $doneCards }}</h3>
        </div>
    </div>

    <!-- Boards List -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Boards</h2>

        @if($project->boards->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($project->boards as $board)
            <a href="{{ route('admin.boards.show', [$project->id, $board->id]) }}"
               class="block bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition group">
                <div class="p-6">
                    <!-- Board Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-columns text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition">
                                    {{ $board->board_name }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    {{ $board->cards->count() }} cards
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Board Stats -->
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <div>
                            <div class="text-lg font-bold text-gray-800">
                                {{ $board->cards->where('status', 'todo')->count() }}
                            </div>
                            <div class="text-xs text-gray-500">To Do</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-blue-600">
                                {{ $board->cards->where('status', 'in_progress')->count() }}
                            </div>
                            <div class="text-xs text-gray-500">Progress</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-yellow-600">
                                {{ $board->cards->where('status', 'review')->count() }}
                            </div>
                            <div class="text-xs text-gray-500">Review</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-600">
                                {{ $board->cards->where('status', 'done')->count() }}
                            </div>
                            <div class="text-xs text-gray-500">Done</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $boardTotal = $board->cards->count();
                        $boardDone = $board->cards->where('status', 'done')->count();
                        $boardProgress = $boardTotal > 0 ? round(($boardDone / $boardTotal) * 100) : 0;
                    @endphp

                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-600">Completion</span>
                            <span class="text-xs font-bold text-gray-800">{{ $boardProgress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all"
                                 style="width: {{ $boardProgress }}%"></div>
                        </div>
                    </div>

                    <!-- View Board Link -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-sm text-blue-600 group-hover:text-blue-800 font-medium">
                            Open Board <i class="fas fa-arrow-right ml-1"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="fas fa-columns text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No boards yet</h3>
            <p class="text-sm text-gray-500 mb-4">Create your first board to start organizing tasks</p>
            <button onclick="openAddBoardModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Create Board
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Add Board Modal -->
<div id="addBoardModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Create New Board</h3>
            <button onclick="closeAddBoardModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.boards.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Board Name *</label>
                <input type="text"
                       name="board_name"
                       required
                       placeholder="e.g., Sprint 1, Marketing Campaign"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                <textarea name="description"
                          rows="3"
                          placeholder="Brief description of this board..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button"
                        onclick="closeAddBoardModal()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create Board
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAddBoardModal() {
    document.getElementById('addBoardModal').classList.remove('hidden');
}

function closeAddBoardModal() {
    document.getElementById('addBoardModal').classList.add('hidden');
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddBoardModal();
    }
});
</script>
@endpush
