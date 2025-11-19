@extends('layouts.teamlead')

@section('title', $card->card_title . ' - Card Detail')
@section('page-title', $card->card_title)
@section('page-subtitle', $card->board->board_name . ' - ' . $card->board->project->project_name)

@push('styles')
<style>
    .chat-container {
        max-height: 500px;
        overflow-y: auto;
    }
    .chat-message {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <!-- Back Button & Actions -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('teamlead.projects.show', $card->board->project_id) }}"
               class="flex items-center text-indigo-600 hover:text-indigo-800 font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Project
            </a>

            <div class="flex items-center space-x-3">
                <!-- ✅ FIX: Edit Button -->
                <a href="{{ route('teamlead.cards.edit', $card) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition shadow-sm">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>

                <!-- ✅ FIX: Delete Button -->
                <form action="{{ route('teamlead.cards.destroy', $card) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this card?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition shadow-sm">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card Header -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-indigo-100">
                    <!-- Status & Priority Badges -->
                    <div class="flex items-center flex-wrap gap-3 mb-4">
                        @php
                            $statusColors = [
                                'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'review' => 'bg-purple-100 text-purple-700 border-purple-200',
                                'done' => 'bg-green-100 text-green-700 border-green-200',
                                'blocked' => 'bg-red-100 text-red-700 border-red-200',
                            ];
                            $priorityColors = [
                                'low' => 'bg-green-100 text-green-700',
                                'medium' => 'bg-orange-100 text-orange-700',
                                'high' => 'bg-red-100 text-red-700',
                            ];
                        @endphp

                        <span class="px-4 py-2 text-sm font-bold rounded-xl border-2 {{ $statusColors[$card->status] ?? 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                        </span>

                        <span class="px-4 py-2 text-sm font-bold rounded-xl {{ $priorityColors[$card->priority] ?? 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-flag mr-1"></i>
                            {{ ucfirst($card->priority) }} Priority
                        </span>

                        @if($card->due_date)
                            @php
                                $daysUntil = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($card->due_date), false);
                                if ($daysUntil < 0) {
                                    $dueBadgeClass = 'bg-red-100 text-red-800 border-2 border-red-300';
                                    $dueText = abs($daysUntil) . ' days Overdue';
                                } elseif ($daysUntil <= 3) {
                                    $dueBadgeClass = 'bg-orange-100 text-orange-800 border-2 border-orange-300';
                                    $dueText = 'Due in ' . $daysUntil . ' days';
                                } else {
                                    $dueBadgeClass = 'bg-gray-100 text-gray-700';
                                    $dueText = 'Due ' . \Carbon\Carbon::parse($card->due_date)->format('M d, Y');
                                }
                            @endphp
                            <span class="px-4 py-2 text-sm font-bold rounded-xl {{ $dueBadgeClass }}">
                                <i class="far fa-clock mr-1"></i>
                                {{ $dueText }}
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($card->description)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                            Description
                        </h3>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $card->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Time Tracking -->
                    @if($card->estimated_hours || $card->actual_hours)
                    <div class="grid grid-cols-3 gap-4 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border-2 border-indigo-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                <i class="far fa-clock mr-1"></i>Estimated
                            </p>
                            <p class="text-2xl font-bold text-gray-800">{{ $card->estimated_hours ?? 0 }}<span class="text-sm">h</span></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                <i class="fas fa-check-circle mr-1"></i>Actual
                            </p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $card->actual_hours ?? 0 }}<span class="text-sm">h</span></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                <i class="fas fa-hourglass-half mr-1"></i>Remaining
                            </p>
                            <p class="text-2xl font-bold text-green-600">{{ max(0, ($card->estimated_hours ?? 0) - ($card->actual_hours ?? 0)) }}<span class="text-sm">h</span></p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Subtasks / Checklist -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-indigo-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-check-square mr-2 text-indigo-600"></i>
                            Subtasks
                            @if($card->subtasks && $card->subtasks->count() > 0)
                                <span class="ml-2 text-sm font-normal text-gray-500">
                                    ({{ $card->subtasks->where('status', 'done')->count() }}/{{ $card->subtasks->count() }})
                                </span>
                            @endif
                        </h3>
                    </div>

                    <!-- Progress Bar -->
                    @if($card->subtasks && $card->subtasks->count() > 0)
                        @php
                            $progress = round(($card->subtasks->where('status', 'done')->count() / $card->subtasks->count()) * 100);
                        @endphp
                        <div class="mb-4 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-indigo-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Overall Progress</span>
                                <span class="text-2xl font-bold {{ $progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 mb-3">
                                <div class="h-4 rounded-full transition-all duration-500 {{ $progress == 100 ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-indigo-500 to-blue-600' }}" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Subtasks List -->
                        <div class="space-y-2">
                            @foreach($card->subtasks->sortBy('position') as $subtask)
                            <div class="group flex items-start space-x-3 p-4 rounded-xl hover:bg-gray-50 transition border border-gray-200 hover:border-indigo-300">
                                <input type="checkbox"
                                       {{ $subtask->status === 'done' ? 'checked' : '' }}
                                       disabled
                                       class="mt-1 w-5 h-5 text-indigo-600 rounded border-gray-300 cursor-not-allowed">

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold {{ $subtask->status === 'done' ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                        {{ $subtask->subtask_title }}
                                    </p>
                                    @if($subtask->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $subtask->description }}</p>
                                    @endif
                                    
                                    <div class="flex flex-wrap gap-2 mt-2 text-xs text-gray-500">
                                        @if($subtask->estimated_hours)
                                            <span><i class="fas fa-clock mr-1"></i>Est: {{ $subtask->estimated_hours }}h</span>
                                        @endif
                                        @if($subtask->actual_hours)
                                            <span><i class="fas fa-check-circle mr-1"></i>Actual: {{ $subtask->actual_hours }}h</span>
                                        @endif
                                    </div>
                                </div>

                                <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $subtask->status === 'done' ? 'bg-green-100 text-green-700' : ($subtask->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : ($subtask->status === 'review' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $subtask->status === 'review' ? 'Review' : ucfirst(str_replace('_', ' ', $subtask->status)) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 font-medium">No subtasks yet</p>
                            <p class="text-sm text-gray-400 mt-2">Developers will create subtasks for this card</p>
                        </div>
                    @endif
                </div>

                <!-- Activity / Comments Section -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-indigo-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="far fa-comments mr-2 text-indigo-600"></i>
                        Activity
                        <span class="ml-2 text-sm font-normal text-gray-500">({{ $card->comments->count() }})</span>
                    </h3>

                    <!-- Add Comment Form -->
                    <form action="{{ route('teamlead.cards.comment', $card) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex space-x-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&size=128&background=6366f1&color=fff"
                                 alt="{{ auth()->user()->full_name }}"
                                 class="w-10 h-10 rounded-full flex-shrink-0 shadow-sm">
                            <div class="flex-1">
                                <textarea name="comment_text"
                                          rows="3"
                                          placeholder="Write a comment..."
                                          required
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition"></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                            class="px-5 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                        <i class="far fa-paper-plane mr-2"></i>Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div class="space-y-4 chat-container">
                        @forelse($card->comments as $comment)
                        <div class="flex space-x-3 chat-message">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->full_name) }}&size=128&background=random"
                                 alt="{{ $comment->user->full_name }}"
                                 class="w-10 h-10 rounded-full flex-shrink-0 shadow-sm">
                            <div class="flex-1">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-sm font-bold text-gray-800">
                                            {{ $comment->user->full_name }}
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $comment->comment_text }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="far fa-comment-dots text-gray-300 text-4xl mb-3"></i>
                            <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar (1/3) -->
            <div class="space-y-6">
                
                <!-- Assigned Developers -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-indigo-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-indigo-600"></i>
                        Assigned Developers
                    </h3>
                    <div class="space-y-3">
                        @forelse($card->assignments as $assignment)
                        <div class="flex items-center space-x-3 p-3 rounded-xl hover:bg-indigo-50 transition border border-gray-200">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($assignment->user->full_name) }}&size=128&background=6366f1&color=fff"
                                     alt="{{ $assignment->user->full_name }}"
                                     class="w-10 h-10 rounded-full shadow-sm">
                                @if($assignment->assignment_status === 'in_progress')
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">{{ $assignment->user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($assignment->user->role) }}</p>
                                <p class="text-xs font-medium mt-1 {{ $assignment->assignment_status === 'in_progress' ? 'text-yellow-600' : ($assignment->assignment_status === 'completed' ? 'text-green-600' : 'text-gray-600') }}">
                                    @if($assignment->assignment_status === 'assigned')
                                        <i class="fas fa-circle text-xs mr-1"></i>Assigned
                                    @elseif($assignment->assignment_status === 'in_progress')
                                        <i class="fas fa-spinner text-xs mr-1"></i>Working
                                    @else
                                        <i class="fas fa-check-circle text-xs mr-1"></i>Completed
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6 bg-gray-50 rounded-xl">
                            <i class="fas fa-user-slash text-gray-300 text-3xl mb-2"></i>
                            <p class="text-xs text-gray-500">No one assigned</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Card Details -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-indigo-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                        Card Details
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-2">Created by</p>
                            <div class="flex items-center space-x-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($card->creator->full_name ?? 'Unknown') }}&size=128&background=6366f1&color=fff"
                                     alt="{{ $card->creator->full_name ?? 'Unknown' }}"
                                     class="w-7 h-7 rounded-full shadow-sm">
                                <p class="text-sm font-semibold text-gray-800">{{ $card->creator->full_name ?? 'Unknown' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Created</p>
                            <p class="text-sm text-gray-800">{{ $card->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Card ID</p>
                            <p class="text-sm font-mono font-bold text-indigo-600">#{{ $card->id }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Last updated</p>
                            <p class="text-sm text-gray-800">{{ $card->updated_at->diffForHumans() }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Board</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $card->board->board_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.addEventListener('load', function() {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});
</script>
@endpush
