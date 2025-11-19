<!-- resources/views/developer/dashboard.blade.php -->
@extends('layouts.admin')

@section('page-title', 'DASHBOARD ' . strtoupper(Auth::user()->role) . ' - Halo, ' . (Auth::user()->full_name ?: Auth::user()->username) . '!')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    * {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .table-row:hover {
        background-color: #f9fafb;
        cursor: pointer;
    }

    .progress-gradient {
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
    }

    .timer-display {
        font-family: 'Courier New', monospace;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .tab-content {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Progress bar colors */
    .bg-green-500 { background-color: #10b981; }
    .bg-orange-500 { background-color: #f97316; }
    .bg-red-500 { background-color: #ef4444; }
    .bg-blue-500 { background-color: #3b82f6; }
    .bg-purple-500 { background-color: #a855f7; }
</style>
@endpush

@section('content')
    <!-- Main Content -->
    <main class="ml-2 mt-16 p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Welcome Back, {{ Auth::user()->full_name ?: Auth::user()->username }}!
            </h1>
            <p class="text-gray-600">Selamat datang kembali di dashboard Anda</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex space-x-6 border-b border-gray-200 mb-8">
            <button onclick="switchTab('tasks')" id="tab-tasks" class="pb-3 border-b-2 border-blue-500 text-blue-600 font-semibold text-sm transition">
                <i class="fas fa-tasks mr-2"></i>Tugas Saya
            </button>
            <button onclick="switchTab('review')" id="tab-review" class="pb-3 border-b-2 border-transparent text-gray-600 hover:text-gray-800 font-medium text-sm transition">
                <i class="fas fa-hourglass-half mr-2"></i>Waiting Review
                @php
                    $reviewCount = ($myCards ?? collect())->where('status', 'review')->count();
                @endphp
                @if($reviewCount > 0)
                <span class="ml-1 px-2 py-0.5 bg-yellow-500 text-white text-xs font-bold rounded-full">{{ $reviewCount }}</span>
                @endif
            </button>
            <button onclick="switchTab('projects')" id="tab-projects" class="pb-3 border-b-2 border-transparent text-gray-600 hover:text-gray-800 font-medium text-sm transition">
                <i class="fas fa-project-diagram mr-2"></i>Proyek
            </button>
            <button onclick="switchTab('timetrack')" id="tab-timetrack" class="pb-3 border-b-2 border-transparent text-gray-600 hover:text-gray-800 font-medium text-sm transition">
                <i class="fas fa-clock mr-2"></i>Time Track
            </button>
            <button onclick="switchTab('achievements')" id="tab-achievements" class="pb-3 border-b-2 border-transparent text-gray-600 hover:text-gray-800 font-medium text-sm transition">
                <i class="fas fa-trophy mr-2"></i>Achievements
            </button>
        </div>

        <!-- Active Timer Card -->
        @if($activeTimeLog ?? false)
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-clock text-2xl"></i>
                        <h3 class="text-xl font-semibold">Timer Aktif</h3>
                    </div>
                    <p class="text-lg mb-1">{{ $activeTimeLog->card->card_title ?? 'Task in progress' }}</p>
                    <div class="timer-display" id="active-timer">00:00:00</div>
                </div>
                <div class="text-right space-y-2">
                    <form action="{{ route('developer.pause-task') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            <i class="fas fa-pause mr-2"></i>Pause Timer
                        </button>
                    </form>
                    
                    <form action="{{ route('developer.complete-task', $activeTimeLog->card->id) }}" method="POST" class="inline" onsubmit="return confirm('Submit task untuk direview oleh Team Lead?')">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition">
                            <i class="fas fa-check-circle mr-2"></i>Complete & Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Tab Content Container -->
        <div id="tab-content">
            <!-- TAB 1: TUGAS SAYA -->
            <div id="content-tasks" class="tab-content">
                @if($currentTaskDetail ?? false)
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-tasks text-blue-600 mr-2"></i>
                            TUGAS SAAT INI
                        </h2>
                        <div class="flex items-center space-x-2">
                            @if(($currentTaskDetail->card->priority ?? 'medium') === 'high')
                                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold flex items-center">
                                    <i class="fas fa-fire mr-1"></i>High Priority
                                </span>
                            @elseif(($currentTaskDetail->card->priority ?? 'medium') === 'medium')
                                <span class="px-3 py-1 bg-orange-500 text-white rounded-full text-xs font-bold flex items-center">
                                    <i class="fas fa-exclamation mr-1"></i>Medium Priority
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold flex items-center">
                                    <i class="fas fa-check mr-1"></i>Low Priority
                                </span>
                            @endif

                            @if($activeTimeLog)
                                <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold flex items-center">
                                    <i class="fas fa-play mr-1"></i>Working
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-500 text-white rounded-full text-xs font-bold flex items-center">
                                    <i class="fas fa-pause mr-1"></i>Not Started
                                </span>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $currentTaskDetail->card->card_title ?? '-' }}</h3>
                    <p class="text-gray-600 mb-4">{{ $currentTaskDetail->card->description ?? 'No description' }}</p>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-blue-600 mb-1">
                                <i class="fas fa-clock mr-2"></i>
                                <span class="text-xs font-semibold">Estimasi</span>
                            </div>
                            <p class="text-xl font-bold text-gray-800">
                                {{ ($currentTaskDetail->card->estimated_hours ?? 0) ? number_format($currentTaskDetail->card->estimated_hours, 1) . ' jam' : 'N/A' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-orange-600 mb-1">
                                <i class="fas fa-hourglass-half mr-2"></i>
                                <span class="text-xs font-semibold">Telah Bekerja</span>
                            </div>
                            <p class="text-xl font-bold text-gray-800">
                                {{ number_format($currentTaskDetail->card->actual_hours ?? 0, 1) }} jam
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-red-600 mb-1">
                                <i class="fas fa-calendar-day mr-2"></i>
                                <span class="text-xs font-semibold">Deadline</span>
                            </div>
                            @if($currentTaskDetail->card->due_date ?? false)
                                @php
                                    $dueDate = \Carbon\Carbon::parse($currentTaskDetail->card->due_date);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $now->diffInDays($dueDate, false);
                                @endphp
                                @if($diff < 0)
                                    <p class="text-xl font-bold text-red-600">Overdue</p>
                                @elseif($diff === 0)
                                    <p class="text-xl font-bold text-red-600">Today</p>
                                @else
                                    <p class="text-xl font-bold text-gray-800">{{ $dueDate->format('d M Y') }}</p>
                                @endif
                            @else
                                <p class="text-xl font-bold text-gray-400">No deadline</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 mt-4">
                        @if($activeTimeLog)
                            <button disabled class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-play mr-2"></i>Start
                            </button>
                            <form action="{{ route('developer.pause-task') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                                    <i class="fas fa-pause mr-2"></i>Pause
                                </button>
                            </form>
                            <form action="{{ route('developer.complete-task', $currentTaskDetail->card) }}" method="POST" class="flex-1" onsubmit="return confirm('Submit task untuk direview oleh Team Lead?')">
                                @csrf
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-2"></i>Complete & Review
                                </button>
                            </form>
                        @else
                            <form action="{{ route('developer.start-task', $currentTaskDetail->card) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                                    <i class="fas fa-play mr-2"></i>Start
                                </button>
                            </form>
                            <button disabled class="flex-1 bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-pause mr-2"></i>Pause
                            </button>
                            <button disabled class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2"></i>Complete & Review
                            </button>
                        @endif

                        <button onclick="toggleCommentModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                            <i class="fas fa-comment mr-2"></i>Comment
                        </button>

                        <form action="{{ route('developer.cards.block', $currentTaskDetail->card) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin block task ini?')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                                <i class="fas fa-ban mr-2"></i>Block
                            </button>
                        </form>
                    </div>

                    <!-- Current Task Comments -->
                    @if(isset($currentTaskComments) && $currentTaskComments->isNotEmpty())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-comments text-blue-600 mr-2"></i>
                            Recent Comments ({{ $currentTaskComments->count() }})
                        </h4>
                        <div class="space-y-3">
                            @foreach($currentTaskComments as $comment)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($comment->user->full_name ?: $comment->user->username, 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <p class="font-semibold text-sm text-gray-800">
                                                {{ $comment->user->full_name ?: $comment->user->username }}
                                            </p>
                                            <span class="text-xs text-gray-500">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 break-words">{{ $comment->comment_text }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Comment Modal -->
                <div id="commentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-comment-dots text-blue-600 mr-2"></i>
                                Add Comment
                            </h3>
                            <button onclick="toggleCommentModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <form action="{{ route('cards.comment', $currentTaskDetail->card ?? 0) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Your Comment</label>
                                <textarea name="comment_text" 
                                          rows="4" 
                                          required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                          placeholder="Write your comment here..."></textarea>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-3">
                                <button type="button" 
                                        onclick="toggleCommentModal()" 
                                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Submit Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
                    <div class="text-center py-8">
                        <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Current Task</h3>
                        <p class="text-gray-500">Select a task from the list below to start working</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- TAB 2: WAITING REVIEW -->
            <div id="content-review" class="tab-content hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">⏳ Waiting for Team Lead Review</h2>
                    <p class="text-gray-600">Tasks yang sudah Anda submit dan menunggu approval dari Team Lead</p>
                </div>

                @php
                    $reviewCards = ($myCards ?? collect())->where('status', 'review');
                @endphp

                @forelse($reviewCards as $card)
                <div class="bg-yellow-50 rounded-xl shadow-sm p-6 mb-4 border-l-4 border-yellow-500">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-xl font-bold text-gray-800">{{ $card->card_title }}</h3>
                                @if($card->priority === 'high')
                                    <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                                        <i class="fas fa-fire mr-1"></i>High
                                    </span>
                                @elseif($card->priority === 'medium')
                                    <span class="px-2 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">
                                        <i class="fas fa-exclamation mr-1"></i>Medium
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                        <i class="fas fa-check mr-1"></i>Low
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 mb-3">{{ Str::limit($card->description, 150) }}</p>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>
                                    <i class="fas fa-project-diagram mr-1 text-blue-600"></i>
                                    {{ $card->board->project->project_name ?? 'Unknown Project' }}
                                </span>
                                <span>
                                    <i class="fas fa-clock mr-1 text-yellow-600"></i>
                                    Submitted {{ $card->updated_at->diffForHumans() }}
                                </span>
                                @if($card->due_date)
                                <span>
                                    <i class="fas fa-calendar mr-1 text-red-600"></i>
                                    Due: {{ \Carbon\Carbon::parse($card->due_date)->format('d M Y') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="ml-4">
                            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-yellow-200 grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Estimated</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $card->estimated_hours ? number_format($card->estimated_hours, 1) . 'h' : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Actual Time</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ number_format($card->actual_hours ?? 0, 1) }}h
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full inline-flex items-center">
                                <i class="fas fa-hourglass-half mr-1"></i>In Review
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-200">
                    <i class="fas fa-check-circle text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Tasks in Review</h3>
                    <p class="text-gray-500">Semua task Anda sudah direview atau belum ada yang di-submit</p>
                </div>
                @endforelse
            </div>

            <!-- TAB 3: PROJECTS -->
            <div id="content-projects" class="tab-content hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">📁 My Projects</h2>
                    <p class="text-gray-600">Semua project yang Anda ikuti</p>
                </div>

                @if($myProjects && $myProjects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($myProjects as $project)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden">
                        <!-- Project Thumbnail -->
                        @if($project->thumbnail)
                        <div class="h-40 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $project->thumbnail) }}')"></div>
                        @else
                        <div class="h-40 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-folder-open text-white text-4xl"></i>
                        </div>
                        @endif

                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $project->project_name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $project->description ?: 'No description' }}</p>

                            <!-- Project Stats -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <div class="flex items-center text-blue-600 mb-1">
                                        <i class="fas fa-tasks mr-2 text-sm"></i>
                                        <span class="text-xs font-semibold">Tasks</span>
                                    </div>
                                    <p class="text-xl font-bold text-gray-800">
                                        {{ $project->boards->sum(function($board) { return $board->cards->count(); }) }}
                                    </p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3">
                                    <div class="flex items-center text-green-600 mb-1">
                                        <i class="fas fa-users mr-2 text-sm"></i>
                                        <span class="text-xs font-semibold">Members</span>
                                    </div>
                                    <p class="text-xl font-bold text-gray-800">
                                        {{ $project->members->count() }}
                                    </p>
                                </div>
                            </div>

                            <!-- Project Info (NO BUTTON) -->
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between text-xs text-gray-600">
                                    <span>
                                <i class="fas fa-calendar mr-1 text-blue-600"></i>
                                @if($project->created_at)
                                    Created {{ $project->created_at->diffForHumans() }}
                                @else
                                    No date
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-200">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Projects Yet</h3>
                    <p class="text-gray-500">Anda belum tergabung dalam project apapun</p>
                </div>
                @endif
            </div>


            <!-- TAB 4: TIME TRACK -->
            <div id="content-timetrack" class="tab-content hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">⏱️ Time Tracking</h2>
                    <p class="text-gray-600">Riwayat waktu kerja Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-clock text-3xl opacity-80"></i>
                            <span class="text-sm font-semibold opacity-90">Today</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ number_format($todayTotalHours ?? 0, 1) }}h</p>
                        <p class="text-sm opacity-80">Total Hours Today</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-calendar-week text-3xl opacity-80"></i>
                            <span class="text-sm font-semibold opacity-90">This Week</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ number_format($totalHoursWorked ?? 0, 1) }}h</p>
                        <p class="text-sm opacity-80">Total Hours</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-tasks text-3xl opacity-80"></i>
                            <span class="text-sm font-semibold opacity-90">Completed</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ $completedTasks ?? 0 }}</p>
                        <p class="text-sm opacity-80">Tasks Done</p>
                    </div>
                </div>

                @if($todayTimeLogs && $todayTimeLogs->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-history mr-2 text-blue-600"></i>
                            Recent Time Logs
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($todayTimeLogs as $log)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 mb-1">{{ $log->card->card_title ?? 'Unknown Task' }}</h4>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock mr-1 text-blue-600"></i>
                                        {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }} - 
                                        {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('H:i') : 'In Progress' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ $log->duration_minutes ? number_format($log->duration_minutes / 60, 1) . 'h' : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-200">
                    <i class="fas fa-clock text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Time Logs Today</h3>
                    <p class="text-gray-500">Mulai bekerja pada task untuk mencatat waktu</p>
                </div>
                @endif
            </div>

            <!-- TAB 5: ACHIEVEMENTS -->
            <div id="content-achievements" class="tab-content hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">🏆 Achievements & Performance</h2>
                    <p class="text-gray-600">Pencapaian dan statistik performa Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 mb-1">{{ $rating ?? 0 }}/5</p>
                        <p class="text-sm text-gray-600 font-semibold">Overall Rating</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 mb-1">{{ $qualityScore ?? 0 }}%</p>
                        <p class="text-sm text-gray-600 font-semibold">Quality Score</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-check text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($onTimeRate ?? 0, 1) }}%</p>
                        <p class="text-sm text-gray-600 font-semibold">On-Time Rate</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($productivityRating ?? 0, 1) }}/5</p>
                        <p class="text-sm text-gray-600 font-semibold">Productivity</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                        This Month Performance
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-blue-600 mb-2">{{ $completedThisMonth ?? 0 }}</p>
                            <p class="text-sm text-gray-600 font-semibold">Tasks Completed</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-green-600 mb-2">{{ number_format($averageTimePerTask ?? 0, 1) }}h</p>
                            <p class="text-sm text-gray-600 font-semibold">Avg Time/Task</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-purple-600 mb-2">{{ number_format($totalHoursWorked ?? 0, 1) }}h</p>
                            <p class="text-sm text-gray-600 font-semibold">Total Hours</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                        Achievement Badges
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(($completedTasks ?? 0) >= 1)
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                            <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-rocket text-white text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Fast Starter</p>
                            <p class="text-xs text-gray-600">First Task Done</p>
                        </div>
                        @endif

                        @if(($completedTasks ?? 0) >= 10)
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-medal text-white text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Task Master</p>
                            <p class="text-xs text-gray-600">10 Tasks Completed</p>
                        </div>
                        @endif

                        @if(($totalHoursWorked ?? 0) >= 40)
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                            <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-clock text-white text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Time Keeper</p>
                            <p class="text-xs text-gray-600">40+ Hours Logged</p>
                        </div>
                        @endif

                        @if(($qualityScore ?? 0) >= 90)
                        <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-star text-white text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Quality Pro</p>
                            <p class="text-xs text-gray-600">90%+ Quality Score</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
// Tab switching
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600', 'font-semibold');
        tab.classList.add('border-transparent', 'text-gray-600', 'font-medium');
    });

    document.getElementById('content-' + tabName).classList.remove('hidden');

    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-600', 'font-medium');
    activeTab.classList.add('border-blue-500', 'text-blue-600', 'font-semibold');

    localStorage.setItem('activeTab', tabName);
}

document.addEventListener('DOMContentLoaded', function() {
    const savedTab = localStorage.getItem('activeTab') || 'tasks';
    switchTab(savedTab);
});

// Timer
let timerInterval;
let startTime;

@if($activeTimeLog ?? false)
    startTime = new Date('{{ $activeTimeLog->start_time }}').getTime();
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
@endif

function updateTimer() {
    const now = new Date().getTime();
    const distance = now - startTime;

    const hours = Math.floor(distance / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    const timerElement = document.getElementById('active-timer');
    if(timerElement) {
        timerElement.textContent =
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
    }
}

function toggleCommentModal() {
    const modal = document.getElementById('commentModal');
    modal.classList.toggle('hidden');
}

document.getElementById('commentModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        toggleCommentModal();
    }
});
</script>
@endpush
