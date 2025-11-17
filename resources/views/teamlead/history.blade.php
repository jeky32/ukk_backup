@extends('layouts.teamlead')

@section('title', 'Task History')
@section('page-title', 'Task History')
@section('page-subtitle', 'Review all task activities and status changes')

@section('content')
    <div class="ml-[10px] p-8">
        <!-- Page Header -->
        <div class="bg-white rounded-xl p-6 mb-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <h2 class="font-bold text-2xl mb-0">Task History</h2>
                    <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-sm font-semibold ml-3">{{ $stats['total'] }} Total</span>
                </div>
                <button onclick="exportHistory()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export History
                </button>
            </div>

            <div class="flex gap-3 mt-4">
                <input type="text" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Search history..." id="searchInput">
                <select class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white min-w-[150px] text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="COMPLETED">Completed</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                    <option value="CANCELLED">Cancelled</option>
                </select>
                <select class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white min-w-[150px] text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" id="projectFilter">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold mb-1">Completed Tasks</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold mb-1">Approved</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold mb-1">Rejected</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['rejected'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-gray-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold mb-1">Cancelled</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['cancelled'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-ban text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-bold text-lg mb-4">Recent Activity</h3>

            @forelse($historyComments as $comment)
            <!-- Timeline Item -->
            <div class="history-item relative pb-8 border-l-2 border-gray-200 ml-6 pl-8 {{ $loop->last ? '' : '' }}" 
                 data-status="{{ $comment->status }}"
                 data-project="{{ $comment->card->board->project->id }}"
                 data-card-title="{{ $comment->card->title }}"
                 data-content="{{ $comment->clean_content }}">
                <div class="absolute -left-3 top-0 w-6 h-6 rounded-full border-4 border-white
                    {{ $comment->status === 'COMPLETED' ? 'bg-green-500' : '' }}
                    {{ $comment->status === 'APPROVED' ? 'bg-blue-500' : '' }}
                    {{ $comment->status === 'REJECTED' ? 'bg-red-500' : '' }}
                    {{ $comment->status === 'CANCELLED' ? 'bg-gray-500' : '' }}">
                </div>
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase
                                {{ $comment->status === 'COMPLETED' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $comment->status === 'APPROVED' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $comment->status === 'REJECTED' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $comment->status === 'CANCELLED' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ $comment->status }}
                            </span>
                            <span class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-2">{{ $comment->card->title }}</h4>
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($comment->clean_content ?: 'No additional notes', 150) }}</p>

                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                @if($comment->user->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" 
                                         alt="{{ $comment->user->full_name }}"
                                         class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-xs">
                                        {{ strtoupper(substr($comment->user->full_name ?: $comment->user->username, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="text-gray-700 font-medium">{{ $comment->user->full_name ?: $comment->user->username }}</span>
                            </div>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-600">
                                <i class="fas fa-folder mr-1 text-indigo-600"></i>
                                {{ $comment->card->board->project->project_name }}
                            </span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-600">
                                <i class="fas fa-columns mr-1 text-indigo-600"></i>
                                {{ $comment->card->board->board_name }}
                            </span>
                        </div>
                    </div>
                    <button class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg text-sm font-semibold hover:bg-indigo-50 transition-colors" 
                            onclick="viewDetail({{ $comment->id }}, '{{ $comment->card->id }}')">
                        <i class="fas fa-eye mr-1"></i>View
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">No History Found</h4>
                <p class="text-sm text-gray-500">There are no task history records yet</p>
            </div>
            @endforelse

            @if($historyComments->count() > 0)
            <!-- Pagination or Load More can be added here -->
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-[1000] items-center justify-center hidden" id="detailModal">
        <div class="bg-white rounded-xl w-11/12 max-w-[700px] max-h-[90vh] overflow-y-auto p-8">
            <div class="mb-6 pb-4 border-b border-gray-200">
                <span class="float-right text-2xl cursor-pointer text-gray-500 hover:text-gray-800" onclick="closeDetailModal()">&times;</span>
                <h3 class="font-bold text-xl mb-2">Task Details</h3>
                <div class="mt-2">
                    <span id="modalStatus" class="px-3 py-1 rounded-md text-xs font-bold uppercase"></span>
                </div>
            </div>

            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        function viewDetail(commentId, cardId) {
            // Show modal
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Load card details via AJAX
            fetch(`/teamlead/card/${cardId}/detail`)
                .then(response => response.json())
                .then(data => {
                    updateModalContent(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalContent').innerHTML = '<p class="text-red-500">Failed to load details</p>';
                });
        }

        function updateModalContent(card) {
            const statusBadge = document.getElementById('modalStatus');
            const modalContent = document.getElementById('modalContent');

            // Update status badge
            let statusClass = '';
            switch(card.status) {
                case 'COMPLETED':
                    statusClass = 'bg-green-100 text-green-700';
                    break;
                case 'APPROVED':
                    statusClass = 'bg-blue-100 text-blue-700';
                    break;
                case 'REJECTED':
                    statusClass = 'bg-red-100 text-red-700';
                    break;
                case 'CANCELLED':
                    statusClass = 'bg-gray-100 text-gray-700';
                    break;
            }
            statusBadge.className = `px-3 py-1 rounded-md text-xs font-bold uppercase ${statusClass}`;
            statusBadge.textContent = card.status;

            // Update content
            modalContent.innerHTML = `
                <h5 class="font-bold mb-2">${card.title}</h5>
                <p class="text-gray-600 text-sm mb-4">${card.description || 'No description'}</p>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h6 class="font-semibold text-sm mb-3">Task Information</h6>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Assignee:</span>
                            <span class="font-semibold">${card.assignee || 'Not assigned'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Project:</span>
                            <span class="font-semibold">${card.project}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Board:</span>
                            <span class="font-semibold">${card.board}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-semibold">${card.card_status}</span>
                        </div>
                        ${card.due_date ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Due Date:</span>
                            <span class="font-semibold">${card.due_date}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                ${card.comments && card.comments.length > 0 ? `
                <h6 class="font-semibold text-sm mb-2">Activity Log</h6>
                <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                    ${card.comments.map(comment => `
                        <div class="flex gap-3 text-sm">
                            <div class="w-2 h-2 rounded-full bg-indigo-600 mt-1.5 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="font-medium">${comment.user}</p>
                                <p class="text-gray-600 text-xs">${comment.content}</p>
                                <p class="text-xs text-gray-500 mt-1">${comment.time}</p>
                            </div>
                        </div>
                    `).join('')}
                </div>
                ` : ''}

                <button class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors" onclick="closeDetailModal()">
                    Close
                </button>
            `;
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.history-item');

            items.forEach(item => {
                const title = item.dataset.cardTitle.toLowerCase();
                const content = item.dataset.content.toLowerCase();

                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Filter by status
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const selectedStatus = e.target.value;
            const items = document.querySelectorAll('.history-item');

            if (selectedStatus === '') {
                items.forEach(item => item.style.display = 'block');
            } else {
                items.forEach(item => {
                    if (item.dataset.status === selectedStatus) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });

        // Filter by project
        document.getElementById('projectFilter').addEventListener('change', function(e) {
            const selectedProject = e.target.value;
            const items = document.querySelectorAll('.history-item');

            if (selectedProject === '') {
                items.forEach(item => item.style.display = 'block');
            } else {
                items.forEach(item => {
                    if (item.dataset.project === selectedProject) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });

        // Export history function
        function exportHistory() {
            window.location.href = '{{ route("teamlead.history.export") }}';
        }
    </script>
@endpush
@endsection