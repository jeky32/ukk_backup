@extends('layouts.teamlead')

@section('title', 'Tasks Review')
@section('page-title', 'Tasks Pending Review')
@section('page-subtitle', 'Review and approve submitted tasks')

@push('styles')
<style>
    .task-card {
        transition: all 0.3s ease;
    }
    .task-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="p-8">
        <!-- Page Header -->
        <div class="bg-white rounded-xl p-6 mb-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <h2 class="font-bold text-2xl mb-0">Tasks Pending Review</h2>
                    <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-semibold ml-3">
                        {{ $reviewCards->count() }}
                    </span>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <input type="text" 
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       placeholder="Search tasks..." 
                       id="searchInput">
                <select class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white min-w-[180px] text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                        id="filterDropdown">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Task Cards -->
        <div class="tasks-container">
            @forelse($reviewCards as $card)
            <div class="task-card bg-white rounded-xl p-6 mb-5 shadow-sm hover:shadow-md" 
                 data-project-id="{{ $card->board->project->id }}">
                
                <!-- Card Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $card->card_title }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($card->priority == 'high')
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">
                                    HIGH PRIORITY
                                </span>
                            @elseif($card->priority == 'medium')
                                <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">
                                    MEDIUM PRIORITY
                                </span>
                            @else
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">
                                    LOW PRIORITY
                                </span>
                            @endif
                            <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">
                                PENDING REVIEW
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Assignees -->
                <div class="task-content mb-5">
                    @foreach($card->assignments as $assignment)
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr($assignment->user->full_name ?? $assignment->user->username, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">
                                {{ $assignment->user->full_name ?? $assignment->user->username }}
                            </div>
                            <div class="text-gray-500 text-sm">
                                Submitted {{ $card->submitted_time->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Description -->
                    <p class="text-gray-800 mb-4 leading-relaxed">
                        {{ $card->description ?? 'No description provided.' }}
                    </p>

                    <!-- Subtasks -->
                    @if($card->subtasks->count() > 0)
                    <div class="mb-4">
                        <h5 class="font-semibold text-gray-700 mb-2">Subtasks ({{ $card->subtasks->count() }})</h5>
                        <div class="space-y-2">
                            @foreach($card->subtasks->take(3) as $subtask)
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-{{ $subtask->status == 'done' ? 'check-circle text-green-500' : 'circle text-gray-400' }}"></i>
                                <span class="{{ $subtask->status == 'done' ? 'line-through text-gray-500' : 'text-gray-700' }}">
                                    {{ $subtask->subtask_title }}
                                </span>
                            </div>
                            @endforeach
                            @if($card->subtasks->count() > 3)
                            <div class="text-xs text-indigo-600 font-semibold">
                                +{{ $card->subtasks->count() - 3 }} more subtasks
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Work Time -->
                    <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock text-indigo-600"></i>
                            <span>Time: {{ $card->total_work_time }}</span>
                        </div>
                        @if($card->due_date)
                        <div class="flex items-center gap-2">
                            <i class="far fa-calendar text-indigo-600"></i>
                            <span>Due: {{ \Carbon\Carbon::parse($card->due_date)->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Latest Comment -->
                    @if($card->last_assignee_comment)
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-comment text-indigo-600 mt-1"></i>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 mb-1">
                                    Latest note from {{ $card->last_assignee_comment->user->full_name ?? $card->last_assignee_comment->user->username }}:
                                </div>
                                <p class="text-sm text-gray-700">
                                    "{{ Str::limit($card->last_assignee_comment->comment_text, 150) }}"
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Project Tag -->
                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-semibold">
                        <i class="fas fa-folder mr-1"></i>{{ $card->board->project->project_name }}
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2.5 border border-indigo-600 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition-colors" 
                            onclick="openDetailModal({{ $card->id }})">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </button>
                    <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="done">
                        <button type="submit" 
                                class="w-full px-4 py-2.5 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>Approve
                        </button>
                    </form>
                    <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="todo">
                        <button type="submit" 
                                class="w-full px-4 py-2.5 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                            <i class="fas fa-times-circle mr-2"></i>Reject
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="text-center py-20 bg-white rounded-xl">
                <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                <h3 class="text-gray-800 font-bold text-xl mb-2">All caught up!</h3>
                <p class="text-gray-500">No tasks pending review at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-[1000] items-center justify-center hidden" id="detailModal">
        <div class="bg-white rounded-xl w-11/12 max-w-[800px] max-h-[90vh] overflow-y-auto p-8">
            <!-- Modal content will be loaded dynamically -->
            <div id="modalContent"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.task-card');

    cards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const description = card.querySelector('p').textContent.toLowerCase();

        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Filter by project
document.getElementById('filterDropdown').addEventListener('change', function(e) {
    const selectedProject = e.target.value;
    const cards = document.querySelectorAll('.task-card');

    if (selectedProject === '') {
        cards.forEach(card => card.style.display = 'block');
    } else {
        cards.forEach(card => {
            if (card.dataset.projectId === selectedProject) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
});

// Modal functions
function openDetailModal(cardId) {
    fetch(`/teamlead/review/${cardId}/detail`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalContent').innerHTML = generateModalContent(data);
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load task details');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function generateModalContent(card) {
    let subtasksHtml = '';
	
    if (card.subtasks && card.subtasks.length > 0) {
        subtasksHtml = `
            <h5 class="font-bold mb-3 mt-6">Subtasks</h5>
            <div class="space-y-2 mb-4">
                ${card.subtasks.map(subtask => `
                    <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                        <i class="fas fa-${subtask.status === 'done' ? 'check-circle text-green-500' : 'circle text-gray-400'}"></i>
                        <span class="${subtask.status === 'done' ? 'line-through text-gray-500' : 'text-gray-700'}">${subtask.subtask_title}</span>
                    </div>
                `).join('')}
            </div>
        `;
    }
	
    let commentsHtml = '';
    if (card.comments && card.comments.length > 0) {
        commentsHtml = `
            <h5 class="font-bold mb-3 mt-6">Comments</h5>
            <div class="space-y-3 mb-4">
                ${card.comments.map(comment => `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-semibold">
                                ${comment.user.full_name ? comment.user.full_name.substring(0, 2).toUpperCase() : 'U'}
                            </div>
                            <div>
                                <div class="font-semibold text-sm">${comment.user.full_name || comment.user.username}</div>
                                <div class="text-xs text-gray-500">${comment.created_at_human}</div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">${comment.comment_text}</p>
                    </div>
                `).join('')}
            </div>
        `;
    }

    return `
        <div class="mb-6 pb-4 border-b border-gray-200">
            <span class="float-right text-2xl cursor-pointer text-gray-500 hover:text-gray-800" onclick="closeDetailModal()">&times;</span>
            <h3 class="font-bold text-xl mb-2">${card.card_title}</h3>
            <div class="mt-2">
                <span class="bg-${card.priority === 'high' ? 'red' : card.priority === 'medium' ? 'amber' : 'green'}-100 text-${card.priority === 'high' ? 'red' : card.priority === 'medium' ? 'amber' : 'green'}-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">${card.priority} PRIORITY</span>
                <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-md text-xs font-semibold ml-2 uppercase">PENDING REVIEW</span>
            </div>
        </div>

        <div class="modal-body">
            <h5 class="font-bold mb-3">Description</h5>
            <p class="text-gray-800 leading-relaxed mb-4">
                ${card.description || 'No description provided.'}
            </p>

            <h5 class="font-bold mb-3 mt-6">Assignees</h5>
            ${card.assignments.map(assignment => `
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                        ${assignment.user.full_name ? assignment.user.full_name.substring(0, 2).toUpperCase() : 'U'}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">${assignment.user.full_name || assignment.user.username}</div>
                        <div class="text-gray-500 text-sm">${assignment.user.role}</div>
                    </div>
                </div>
            `).join('')}

            ${subtasksHtml}
            ${commentsHtml}

            <div class="flex gap-3 mt-6">
                <form action="/panel/teamlead/card/${card.id}/status" method="POST" class="flex-1">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="status" value="done">
                    <button type="submit" class="w-full px-4 py-3.5 bg-green-500 text-white rounded-lg font-semibold text-base hover:bg-green-600 transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>Approve & Mark as Done
                    </button>
                </form>
                <form action="/panel/teamlead/card/${card.id}/status" method="POST" class="flex-1">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="status" value="todo">
                    <button type="submit" class="w-full px-4 py-3.5 bg-amber-500 text-white rounded-lg font-semibold text-base hover:bg-amber-600 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Request Revision
                    </button>
                </form>
                <button class="px-6 py-3.5 bg-white text-gray-800 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors" onclick="closeDetailModal()">Cancel</button>
            </div>
        </div>
    `;
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>
@endpush
@endsection