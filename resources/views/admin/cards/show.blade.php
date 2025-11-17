@extends('layouts.admin')

@section('title', $card->card_title . ' - Card Detail')

@push('styles')
<style>
.chat-container { height: 400px; overflow-y: auto; }
.glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.05); }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 bg-gradient-to-br from-gray-50 via-blue-50/20 to-purple-50/10 min-h-screen">
    <!-- Back Button & Actions -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.boards.show', [$card->board->project_id, $card->board_id]) }}"
           class="flex items-center space-x-2 px-4 py-2.5 bg-white rounded-xl shadow-md hover:shadow-lg text-gray-700 hover:text-gray-900 border border-gray-200 font-medium transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Board</span>
        </a>

        <div class="flex items-center space-x-3">
            <form action="{{ route('admin.cards.duplicate', $card) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-white text-gray-700 rounded-xl hover:bg-gray-50 shadow-md hover:shadow-lg border border-gray-200 font-medium transition">
                    <i class="fas fa-copy mr-2"></i>Duplicate
                </button>
            </form>

            <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this card?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 shadow-md hover:shadow-lg shadow-red-500/30 font-medium transition">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Header -->
            <div class="glass-card rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <!-- Status & Priority -->
                <div class="flex items-center flex-wrap gap-3 mb-5">
                    @php
                        $statusColors = [
                            'todo' => 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-blue-500/30',
                            'in_progress' => 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-yellow-500/30',
                            'review' => 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-purple-500/30',
                            'done' => 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-green-500/30',
                        ];
                        $priorityColors = [
                            'low' => 'bg-green-100 text-green-700 border-2 border-green-200',
                            'medium' => 'bg-yellow-100 text-yellow-700 border-2 border-yellow-200',
                            'high' => 'bg-red-100 text-red-700 border-2 border-red-200',
                        ];
                    @endphp

                    <span class="px-4 py-2 text-sm font-bold rounded-xl shadow-md {{ $statusColors[$card->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                    </span>

                    <span class="px-4 py-2 text-sm font-bold rounded-xl {{ $priorityColors[$card->priority] ?? 'bg-gray-100 text-gray-700' }}">
                        <i class="fas fa-flag mr-1"></i>{{ ucfirst($card->priority) }} Priority
                    </span>

                    @if($card->due_date)
                    <span class="px-4 py-2 text-sm font-bold rounded-xl {{ $card->is_overdue ? 'bg-red-100 text-red-700 border-2 border-red-200' : ($card->is_due_soon ? 'bg-orange-100 text-orange-700 border-2 border-orange-200' : 'bg-gray-100 text-gray-700 border-2 border-gray-200') }}">
                        <i class="far fa-clock mr-1"></i>{{ $card->due_date->format('M d, Y') }}
                    </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <div class="flex items-center space-x-2 mb-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                            <i class="fas fa-align-left text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Description</h3>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $card->description ?: 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <!-- Time Tracking -->
                @if($card->estimated_hours || $card->actual_hours > 0)
                <div class="grid grid-cols-3 gap-4 p-5 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl border-2 border-blue-200 shadow-inner">
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-2 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <i class="far fa-clock text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-xs text-gray-600 mb-1 font-medium">Estimated</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $card->estimated_hours ?? 0 }}<span class="text-sm">h</span></p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-2 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                        </div>
                        <p class="text-xs text-gray-600 mb-1 font-medium">Logged</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $card->actual_hours }}<span class="text-sm">h</span></p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-2 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-battery-three-quarters text-green-600 text-xl"></i>
                        </div>
                        <p class="text-xs text-gray-600 mb-1 font-medium">Remaining</p>
                        <p class="text-2xl font-bold text-green-600">{{ $card->hours_remaining ?? 0 }}<span class="text-sm">h</span></p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Subtasks / Checklist -->
            <div class="glass-card rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Checklist</h3>
                            <p class="text-xs text-gray-500 font-medium">{{ $card->completed_subtasks_count ?? 0 }}/{{ $card->total_subtasks_count ?? 0 }} completed</p>
                        </div>
                    </div>
                    <button onclick="openAddSubtaskModal()"
                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transition">
                        <i class="fas fa-plus mr-1"></i>Add Item
                    </button>
                </div>

                <!-- Progress Bar -->
                @if($card->subtasks->count() > 0)
                <div class="mb-5 bg-white rounded-xl p-4 border-2 border-gray-100 shadow-inner">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-gray-700">Progress</span>
                        <span class="text-lg font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $card->subtasks_progress ?? 0 }}%</span>
                    </div>
                    <div class="relative w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 h-3 rounded-full shadow-lg transition-all"
                             style="width: {{ $card->subtasks_progress ?? 0 }}%"></div>
                    </div>
                </div>

                <!-- Subtasks List -->
                <div class="space-y-3">
                    @foreach($card->subtasks as $subtask)
                    <div class="group bg-white rounded-xl p-4 hover:bg-gray-50 border-2 border-gray-100 hover:border-blue-200 hover:shadow-md transition">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <input type="checkbox"
                                       {{ $subtask->status === 'done' ? 'checked' : '' }}
                                       onchange="toggleSubtask({{ $subtask->id }})"
                                       class="w-6 h-6 text-blue-600 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-blue-500 cursor-pointer transition">
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold {{ $subtask->status === 'done' ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                    {{ $subtask->subtask_title }}
                                </p>
                                @if($subtask->description)
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ Str::limit($subtask->description, 80) }}</p>
                                @endif

                                <div class="flex items-center flex-wrap gap-3 mt-3">
                                    @if($subtask->estimated_hours)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-lg border border-gray-200">
                                        <i class="far fa-clock mr-1"></i>{{ $subtask->estimated_hours }}h
                                    </span>
                                    @endif

                                    @if($subtask->actual_hours > 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-lg border border-blue-200">
                                        <i class="fas fa-hourglass-half mr-1"></i>{{ $subtask->actual_hours }}h logged
                                    </span>
                                    @endif

                                    @if($subtask->comments->count() > 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-lg border border-purple-200">
                                        <i class="far fa-comment mr-1"></i>{{ $subtask->comments->count() }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <button onclick="deleteSubtask({{ $subtask->id }})"
                                    class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 hover:bg-red-50 text-gray-400 hover:text-red-600 transition">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border-2 border-dashed border-gray-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-2xl flex items-center justify-center shadow-md">
                        <i class="fas fa-clipboard-list text-gray-300 text-3xl"></i>
                    </div>
                    <p class="text-sm text-gray-600 font-medium mb-3">No checklist items yet</p>
                    <button onclick="openAddSubtaskModal()"
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transition">
                        <i class="fas fa-plus mr-2"></i>Add your first item
                    </button>
                </div>
                @endif
            </div>

            <!-- Activity / Comments Section -->
            <div class="glass-card rounded-2xl shadow-lg p-6">
                <div class="flex items-center space-x-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="far fa-comments text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Activity</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ $card->comments->count() }} comments</p>
                    </div>
                </div>

                <form action="{{ route('admin.cards.comments.store', $card) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex space-x-3">
                        <img src="https://i.pravatar.cc/150?img=1"
                             alt="{{ Auth::user()->full_name }}"
                             class="w-12 h-12 rounded-xl flex-shrink-0 shadow-md ring-2 ring-white object-cover">
                        <div class="flex-1">
                            <textarea name="comment_text"
                                      rows="3"
                                      placeholder="Write a comment..."
                                      required
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none font-medium transition"></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit"
                                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transition">
                                    <i class="far fa-paper-plane mr-2"></i>Send
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="space-y-4 chat-container">
                    @forelse($card->comments as $comment)
                    <div class="flex space-x-3">
                        <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                             alt="{{ $comment->user->full_name }}"
                             class="w-12 h-12 rounded-xl flex-shrink-0 shadow-md ring-2 ring-white object-cover">
                        <div class="flex-1">
                            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border-2 border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-bold text-gray-800">{{ $comment->user->full_name }}</p>
                                    <span class="text-xs font-medium text-gray-500 bg-white px-2.5 py-1 rounded-lg">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $comment->comment_text }}</p>
                            </div>

                            <button class="text-xs text-gray-500 hover:text-blue-600 mt-2 font-semibold transition">
                                <i class="fas fa-reply mr-1"></i>Reply
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-purple-50 rounded-xl border-2 border-dashed border-gray-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-2xl flex items-center justify-center shadow-md">
                            <i class="far fa-comment-dots text-gray-300 text-3xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">No comments yet. Be the first to comment!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assigned To -->
            <div class="glass-card rounded-2xl shadow-lg p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-user-friends text-white text-sm"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Assignee</h3>
                </div>
                <div class="space-y-3">
                    @forelse($card->assignedMembers as $member)
                    <div class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-gray-50 to-blue-50 hover:from-blue-50 hover:to-blue-100 border-2 border-gray-100 hover:border-blue-200 hover:shadow-md transition">
                        <div class="relative flex-shrink-0">
                            <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                                 alt="{{ $member->full_name }}"
                                 class="w-12 h-12 rounded-xl shadow-md ring-2 ring-white object-cover">
                            @php
                                $assignment = $card->assignments->where('user_id', $member->id)->first();
                            @endphp
                            @if($assignment && $assignment->assignment_status === 'in_progress')
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full shadow-md"></span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800">{{ $member->full_name }}</p>
                            @if($assignment)
                            <p class="text-xs font-semibold mt-0.5">
                                @if($assignment->assignment_status === 'assigned')
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-md">
                                        <i class="fas fa-circle text-xs mr-1"></i>Assigned
                                    </span>
                                @elseif($assignment->assignment_status === 'in_progress')
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-md">
                                        <i class="fas fa-circle text-xs mr-1"></i>Working
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-md">
                                        <i class="fas fa-check-circle text-xs mr-1"></i>Completed
                                    </span>
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 bg-gradient-to-br from-gray-50 to-green-50 rounded-xl border-2 border-dashed border-gray-300">
                        <div class="w-12 h-12 mx-auto mb-3 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-user-plus text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-xs text-gray-600 font-medium mb-2">No one assigned</p>
                        <button class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white text-xs font-bold rounded-lg hover:from-green-700 hover:to-green-800 shadow-md shadow-green-500/30 transition">
                            <i class="fas fa-plus mr-1"></i>Add Assignee
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Card Details -->
            <div class="glass-card rounded-2xl shadow-lg p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Details</h3>
                </div>
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-3 border-2 border-gray-100">
                        <p class="text-xs text-gray-500 mb-2 font-semibold">Created by</p>
                        <div class="flex items-center space-x-2">
                            <img src="https://i.pravatar.cc/150?img=99"
                                 alt="{{ $card->creator->full_name }}"
                                 class="w-8 h-8 rounded-lg shadow-md ring-2 ring-white object-cover">
                            <p class="text-sm font-bold text-gray-800">{{ $card->creator->full_name }}</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl p-3 border-2 border-gray-100">
                        <p class="text-xs text-gray-500 mb-1 font-semibold">Created</p>
                        <p class="text-sm font-bold text-gray-800">{{ $card->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-indigo-50 rounded-xl p-3 border-2 border-gray-100">
                        <p class="text-xs text-gray-500 mb-1 font-semibold">Task ID</p>
                        <p class="text-sm font-mono font-bold text-gray-800">#{{ $card->id }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-green-50 rounded-xl p-3 border-2 border-gray-100">
                        <p class="text-xs text-gray-500 mb-1 font-semibold">Last updated</p>
                        <p class="text-sm font-bold text-gray-800">{{ $card->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Labels / Tags -->
            <div class="glass-card rounded-2xl shadow-lg p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-tags text-white text-sm"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Labels</h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-2 text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl shadow-md shadow-red-500/30">
                        <i class="fas fa-flag mr-1"></i>High priority
                    </span>
                    <span class="px-3 py-2 text-xs font-bold bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-md shadow-purple-500/30">
                        <i class="fas fa-globe mr-1"></i>Website
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subtask Modal -->
<div id="addSubtaskModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg border-2 border-gray-100 overflow-hidden">
        <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Add Checklist Item</h3>
                        <p class="text-xs text-blue-100 mt-0.5">Create a new task for this card</p>
                    </div>
                </div>
                <button onclick="closeAddSubtaskModal()"
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <form action="{{ route('admin.cards.tasks.store', $card) }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 mb-3">
                    <span class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heading text-blue-600 text-xs"></i>
                    </span>
                    <span>Task Title *</span>
                </label>
                <input type="text"
                       name="subtask_title"
                       required
                       placeholder="e.g., Review code, Write documentation, Test feature"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium transition placeholder-gray-400">
            </div>

            <div>
                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 mb-3">
                    <span class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-align-left text-purple-600 text-xs"></i>
                    </span>
                    <span>Description</span>
                    <span class="text-gray-400 font-normal text-xs">(Optional)</span>
                </label>
                <textarea name="description"
                          rows="3"
                          placeholder="Add more details about this task..."
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-medium transition resize-none placeholder-gray-400"></textarea>
            </div>

            <div>
                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 mb-3">
                    <span class="w-6 h-6 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="far fa-clock text-yellow-600 text-xs"></i>
                    </span>
                    <span>Estimated Hours</span>
                    <span class="text-gray-400 font-normal text-xs">(Optional)</span>
                </label>
                <div class="relative">
                    <input type="number"
                           name="estimated_hours"
                           min="0"
                           step="0.5"
                           placeholder="0"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 font-medium transition placeholder-gray-400">
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 font-semibold text-sm">hours</span>
                </div>
            </div>

            <div>
                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 mb-3">
                    <span class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-flag text-red-600 text-xs"></i>
                    </span>
                    <span>Priority</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="priority" value="low" class="peer sr-only" checked>
                        <div class="px-4 py-2 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm transition peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 hover:border-green-300">
                            <i class="fas fa-flag mr-1"></i>Low
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="priority" value="medium" class="peer sr-only">
                        <div class="px-4 py-2 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm transition peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 hover:border-yellow-300">
                            <i class="fas fa-flag mr-1"></i>Med
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="priority" value="high" class="peer sr-only">
                        <div class="px-4 py-2 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm transition peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 hover:border-red-300">
                            <i class="fas fa-flag mr-1"></i>High
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t-2 border-gray-100">
                <button type="button"
                        onclick="closeAddSubtaskModal()"
                        class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 shadow-lg shadow-blue-500/30 font-bold transition">
                    <i class="fas fa-plus mr-2"></i>Create Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAddSubtaskModal() {
    document.getElementById('addSubtaskModal').classList.remove('hidden');
}

function closeAddSubtaskModal() {
    document.getElementById('addSubtaskModal').classList.add('hidden');
}

function toggleSubtask(subtaskId) {
    console.log('Toggle subtask:', subtaskId);
}

function deleteSubtask(subtaskId) {
    if (confirm('Are you sure you want to delete this subtask?')) {
        console.log('Delete subtask:', subtaskId);
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAddSubtaskModal();
});

document.getElementById('addSubtaskModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAddSubtaskModal();
});
</script>
@endpush
