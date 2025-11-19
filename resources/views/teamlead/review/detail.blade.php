@extends('layouts.teamlead')

@section('title', 'Review Detail - ' . $card->card_title)
@section('page-title', 'Review Task Detail')
@section('page-subtitle', $card->card_title)

@push('styles')
<style>
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        height: calc(100% - 40px);
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-5xl mx-auto">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('teamlead.review') }}" 
               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Review List
            </a>
        </div>

        <!-- Main Card Info -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border-2 border-yellow-200">
            
            <!-- Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $card->card_title }}</h1>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                            <i class="fas fa-clock mr-1"></i>In Review
                        </span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <span>
                            <i class="fas fa-project-diagram text-blue-500 mr-1"></i>
                            {{ $card->board->project->project_name }}
                        </span>
                        <span>
                            <i class="fas fa-columns text-purple-500 mr-1"></i>
                            {{ $card->board->board_name }}
                        </span>
                    </div>
                </div>

                <!-- Priority Badge -->
                <span class="px-4 py-2 rounded-xl text-sm font-bold
                             {{ $card->priority === 'high' ? 'bg-red-100 text-red-700' : 
                                ($card->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                    <i class="fas fa-flag mr-1"></i>
                    {{ ucfirst($card->priority) }} Priority
                </span>
            </div>

            <!-- Description -->
            @if($card->description)
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-700 mb-2">Description:</h3>
                <p class="text-gray-700 leading-relaxed">{{ $card->description }}</p>
            </div>
            @endif

            <!-- Meta Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                
                <!-- Assigned To -->
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-700 mb-2">ASSIGNED TO</p>
                    <div class="space-y-2">
                        @foreach($card->assignments as $assignment)
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($assignment->user->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $assignment->user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($assignment->user->role) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submitted At -->
                <div class="bg-yellow-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-yellow-700 mb-2">SUBMITTED</p>
                    <p class="text-lg font-bold text-gray-800">{{ $card->updated_at->diffForHumans() }}</p>
                    <p class="text-xs text-gray-500">{{ $card->updated_at->format('d M Y, H:i') }}</p>
                </div>

                <!-- Due Date -->
                <div class="bg-red-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-red-700 mb-2">DUE DATE</p>
                    @if($card->due_date)
                    <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($card->due_date)->format('d M Y') }}</p>
                    <p class="text-xs {{ \Carbon\Carbon::parse($card->due_date)->isPast() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ \Carbon\Carbon::parse($card->due_date)->diffForHumans() }}
                    </p>
                    @else
                    <p class="text-sm text-gray-500">No deadline set</p>
                    @endif
                </div>

            </div>

            <!-- Subtasks Progress -->
            @if($card->subtasks->count() > 0)
            <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Subtasks Progress
                    </h3>
                    <span class="text-sm font-bold text-gray-700">
                        {{ $card->subtasks->where('status', 'done')->count() }}/{{ $card->subtasks->count() }} Completed
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                    <div class="bg-gradient-to-r from-green-500 to-teal-500 h-3 rounded-full transition-all" 
                         style="width: {{ ($card->subtasks->where('status', 'done')->count() / $card->subtasks->count()) * 100 }}%">
                    </div>
                </div>

                <!-- Subtasks List -->
                <div class="space-y-2">
                    @foreach($card->subtasks as $subtask)
                    <div class="flex items-center space-x-3 bg-white rounded-lg p-3">
                        <i class="fas {{ $subtask->status === 'done' ? 'fa-check-circle text-green-500' : 'fa-circle text-gray-300' }}"></i>
                        <span class="flex-1 text-sm {{ $subtask->status === 'done' ? 'line-through text-gray-500' : 'text-gray-800 font-medium' }}">
                            {{ $subtask->subtask_title }}
                        </span>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                                     {{ $subtask->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($subtask->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center space-x-4 pt-6 border-t-2 border-gray-200">
                
                <!-- Approve -->
                <form action="{{ route('teamlead.approve.task', $card->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to approve this task?')"
                            class="w-full bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white py-3 rounded-xl font-bold text-lg transition shadow-lg">
                        <i class="fas fa-check-circle mr-2"></i>Approve Task
                    </button>
                </form>

                <!-- Reject -->
                <button onclick="openRejectModal()"
                        class="flex-1 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white py-3 rounded-xl font-bold text-lg transition shadow-lg">
                    <i class="fas fa-times-circle mr-2"></i>Reject & Request Revision
                </button>

            </div>

        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-comments text-blue-500 mr-2"></i>
                Comments & Activity
            </h2>

            <!-- Comments Timeline -->
            <div class="space-y-4">
                @forelse($card->comments as $comment)
                <div class="timeline-item relative flex items-start space-x-4 pb-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg z-10">
                        {{ strtoupper(substr($comment->user->full_name, 0, 1)) }}
                    </div>

                    <!-- Comment Content -->
                    <div class="flex-1 bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-bold text-gray-800">{{ $comment->user->full_name }}</p>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $comment->comment }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-6">No comments yet</p>
                @endforelse
            </div>

            <!-- Add Comment Form -->
            <form action="{{ route('teamlead.cards.comment', $card->id) }}" method="POST" class="mt-6 pt-6 border-t-2 border-gray-200">
                @csrf
                <label class="block text-sm font-bold text-gray-700 mb-2">Add Comment:</label>
                <textarea name="comment" 
                          required
                          rows="3"
                          placeholder="Write your comment or question here..."
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                <button type="submit" 
                        class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold transition">
                    <i class="fas fa-paper-plane mr-2"></i>Post Comment
                </button>
            </form>
        </div>

    </div>
</div>

<!-- ✅ MODAL REJECT REASON -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Reject Task</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <p class="text-gray-600 mb-4">Please explain what needs to be fixed or improved.</p>

        <form action="{{ route('teamlead.reject.task', $card->id) }}" method="POST">
            @csrf
            
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Reason for Rejection <span class="text-red-500">*</span>
            </label>
            <textarea name="reason" 
                      required
                      rows="5"
                      placeholder="Describe what needs to be revised..."
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"></textarea>

            <div class="flex space-x-3 mt-4">
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2.5 rounded-xl font-semibold transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Reject & Send Back
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRejectModal();
});
</script>
@endpush
