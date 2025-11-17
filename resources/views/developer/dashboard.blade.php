<!-- resources/views/developer/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'My Dashboard - ' . Auth::user()->role)
@section('page-title', 'DASHBOARD ' . strtoupper(Auth::user()->role) . ' - Halo, ' . Auth::user()->full_name . '!')
@section('page-subtitle', 'Kelola tugas dan waktu kerja Anda')

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
</style>
@endpush

@section('content')
    <!-- Main Content -->
    <main class="ml-2 mt-16 p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome</h1>
            <p class="text-gray-600">Selamat datang kembali di dashboard Anda</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex space-x-6 border-b border-gray-200 mb-8">
            <button onclick="switchTab('tasks')" id="tab-tasks" class="pb-3 border-b-2 border-blue-500 text-blue-600 font-semibold text-sm transition">
                <i class="fas fa-tasks mr-2"></i>Tugas Saya
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

        <!-- Active Timer Card (if exists) -->
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
                <div class="text-right">
                    <form action="{{ route('developer.pause-task') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            <i class="fas fa-stop mr-2"></i>Stop Timer
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Tab Content Container -->
        <div id="tab-content">
            <!-- ============================================ -->
            <!-- TAB 1: TUGAS SAYA (Default Active) -->
            <!-- ============================================ -->
            <div id="content-tasks" class="tab-content">
                <!-- Current Task Card -->
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
                            <form action="{{ route('developer.complete-task', $currentTaskDetail->card) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                                    <i class="fas fa-check mr-2"></i>Complete
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
                                <i class="fas fa-check mr-2"></i>Complete
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
                </div>

                <!-- Comment Modal -->
                <div id="commentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl shadow-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Comments</h3>
                            <button onclick="toggleCommentModal()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <!-- All Comments -->
                        <div class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                            @forelse($currentTaskDetail->card->comments ?? [] as $comment)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($comment->user->full_name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="font-semibold text-gray-800 text-sm">{{ $comment->user->full_name ?? 'Unknown' }}</span>
                                            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $comment->comment_text ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-gray-400 py-8">No comments yet</p>
                            @endforelse
                        </div>

                        <!-- Add Comment Form -->
                        <form action="{{ route('cards.comment', $currentTaskDetail->card) }}" method="POST" class="border-t pt-4">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Add Comment</label>
                                <textarea name="comment_text" rows="3" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Write your comment..."></textarea>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="toggleCommentModal()"
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold text-sm">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold text-sm">
                                    <i class="fas fa-paper-plane mr-2"></i>Post Comment
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

                <!-- Task List Table -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Tugas Saya</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tugas</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Proyek</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Priority</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todoTasks ?? [] as $assignment)
                                <tr class="border-b border-gray-100 table-row transition">
                                    <td class="px-4 py-3">
                                        @php
                                            $statusConfig = [
                                                'assigned' => ['icon' => 'circle', 'color' => 'gray', 'label' => 'Assigned'],
                                                'in_progress' => ['icon' => 'spinner', 'color' => 'yellow', 'label' => 'Working'],
                                                'completed' => ['icon' => 'check-circle', 'color' => 'green', 'label' => 'Completed'],
                                            ];
                                            $status = $statusConfig[$assignment->assignment_status ?? 'assigned'] ?? $statusConfig['assigned'];
                                        @endphp
                                        <span class="text-{{ $status['color'] }}-600 font-semibold text-sm flex items-center">
                                            <i class="fas fa-{{ $status['icon'] }} mr-2 text-xs"></i>
                                            {{ $status['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $assignment->card->card_title ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $assignment->card->board->project->project_name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($assignment->card->priority ?? 'low') === 'high')
                                            <span class="px-2 py-1 bg-red-500 text-white rounded-full text-xs font-bold inline-flex items-center">
                                                <i class="fas fa-fire mr-1"></i>High
                                            </span>
                                        @elseif(($assignment->card->priority ?? 'low') === 'medium')
                                            <span class="px-2 py-1 bg-orange-500 text-white rounded-full text-xs font-bold inline-flex items-center">
                                                <i class="fas fa-exclamation mr-1"></i>Medium
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-green-500 text-white rounded-full text-xs font-bold inline-flex items-center">
                                                <i class="fas fa-check mr-1"></i>Low
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold">
                                        @if($assignment->card->due_date ?? false)
                                            @php
                                                $dueDate = \Carbon\Carbon::parse($assignment->card->due_date);
                                                $today = \Carbon\Carbon::today();
                                                $diff = $today->diffInDays($dueDate, false);
                                            @endphp
                                            @if($diff < 0)
                                                <span class="text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>Overdue</span>
                                            @elseif($diff === 0)
                                                <span class="text-red-600"><i class="fas fa-clock mr-1"></i>Today</span>
                                            @elseif($diff === 1)
                                                <span class="text-orange-600">Tomorrow</span>
                                            @else
                                                <span class="text-green-600">{{ $diff }} days</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">No deadline</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-tasks text-4xl mb-2"></i>
                                        <p>Tidak ada tugas todo saat ini</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grid 2 Columns: Time Tracking & Productivity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Time Tracking Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 card-hover transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                                    TIME TRACKING
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ now()->isoFormat('dddd, D MMM YYYY') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            @forelse($todayTimeLogs ?? [] as $log)
                            <div class="py-3 border-b border-gray-100">
                                <p class="font-semibold text-gray-800 text-sm">{{ $log->card->card_title ?? 'Unknown Task' }}</p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-gray-600">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }} -
                                        {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('H:i') : 'In Progress' }}
                                    </span>
                                    <span class="text-sm font-semibold text-blue-600">
                                        {{ $log->duration_minutes ? number_format($log->duration_minutes / 60, 1) : '0.0' }}h
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="py-8 text-center text-gray-400">
                                <i class="fas fa-clock text-4xl mb-2"></i>
                                <p class="text-sm">Belum ada time log hari ini</p>
                            </div>
                            @endforelse
                        </div>

                        @if(isset($todayTimeLogs) && $todayTimeLogs->isNotEmpty())
                        <div class="bg-blue-50 p-3 rounded-lg border-t-2 border-blue-500">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-blue-900">TOTAL TODAY</span>
                                <span class="text-xl font-bold text-blue-600">{{ number_format($todayTotalHours ?? 0, 1) }}h</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Productivity Stats Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 card-hover transition">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                            PRODUCTIVITY
                            <span class="ml-2 text-xs font-normal text-gray-500">(This Month)</span>
                        </h2>

                        <div class="space-y-4">
                            <!-- Tugas Selesai -->
                            <div class="py-3 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        Tugas Selesai
                                    </span>
                                    <span class="text-2xl font-bold text-gray-800">{{ $completedThisMonth ?? 0 }}</span>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="py-3 border-b border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-2"></i>
                                        Rating
                                    </span>
                                    <span class="text-2xl font-bold text-gray-800">{{ number_format($productivityRating ?? 0, 1) }}/5</span>
                                </div>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($productivityRating ?? 0))
                                            <i class="fas fa-star text-yellow-400 text-lg"></i>
                                        @elseif($i - ($productivityRating ?? 0) < 1)
                                            <i class="fas fa-star-half-alt text-yellow-400 text-lg"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400 text-lg"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            <!-- On Time -->
                            <div class="py-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-bullseye text-green-600 mr-2"></i>
                                        On Time
                                    </span>
                                    <span class="text-xl font-bold {{ ($onTimeRate ?? 0) >= 80 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ number_format($onTimeRate ?? 0, 0) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $onTimeRate ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Feed -->
                <div class="bg-white rounded-xl shadow-sm p-6 mt-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bell text-blue-600 mr-2"></i>
                        UPDATE TERBARU
                    </h2>

                    <div class="space-y-4">
                        @forelse($importantUpdates ?? [] as $update)
                        <div class="flex items-start space-x-3 py-3 border-b border-gray-100">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($update->user->full_name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">{{ $update->user->full_name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $update->comment_text ?? '' }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $update->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="py-8 text-center text-gray-400">
                            <i class="fas fa-bell-slash text-4xl mb-2"></i>
                            <p class="text-sm">Belum ada update</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

           <!-- ============================================ -->
            <!-- TAB 2: PROYEK (UPDATED WITH LINKS) -->
            <!-- ============================================ -->
            <div id="content-projects" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($myProjects ?? [] as $project)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition card-hover">
                        <!-- Header dengan gradient & status badge -->
                        <div class="h-32 bg-gradient-to-br from-blue-500 to-purple-600 p-4 flex items-center justify-center relative">
                            <i class="fas fa-project-diagram text-6xl text-white opacity-20"></i>
                            
                            @if($project->status === 'approved')
                                <span class="absolute top-2 right-2 px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold">
                                    <i class="fas fa-check-circle mr-1"></i>Approved
                                </span>
                            @elseif($project->status === 'pending')
                                <span class="absolute top-2 right-2 px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($project->status === 'completed')
                                <span class="absolute top-2 right-2 px-3 py-1 bg-blue-500 text-white rounded-full text-xs font-bold">
                                    <i class="fas fa-flag-checkered mr-1"></i>Completed
                                </span>
                            @elseif($project->status === 'rejected')
                                <span class="absolute top-2 right-2 px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold">
                                    <i class="fas fa-times-circle mr-1"></i>Rejected
                                </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $project->project_name ?? 'Untitled Project' }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($project->description ?? 'No description', 100) }}</p>
                            
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <div class="flex items-center text-blue-600 mb-1">
                                        <i class="fas fa-tasks mr-2 text-sm"></i>
                                        <span class="text-xs font-semibold">Tasks</span>
                                    </div>
                                    <p class="text-xl font-bold text-gray-800">
                                        {{ $project->boards->sum(fn($board) => $board->cards->count()) }}
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

                            <!-- Deadline with warning -->
                            @if($project->deadline ?? false)
                            <div class="flex items-center text-xs text-gray-500 mb-4 bg-gray-50 rounded-lg p-2">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span class="font-semibold">Deadline:</span>
                                <span class="ml-1">{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</span>
                                
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($project->deadline), false);
                                @endphp
                                
                                @if($daysLeft < 0)
                                    <span class="ml-2 text-red-600 font-bold">(Overdue!)</span>
                                @elseif($daysLeft <= 3)
                                    <span class="ml-2 text-orange-600 font-bold">({{ $daysLeft }}d left)</span>
                                @endif
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                <!-- View Project Board Button -->
                                @if($project->boards->isNotEmpty())
                                    @php
                                        $firstBoard = $project->boards->first();
                                    @endphp
                                    <a href="{{ route('admin.boards.show', [$project->id, $firstBoard->id]) }}" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>View Project Board
                                    </a>
                                @else
                                    <button onclick="alert('Proyek ini belum memiliki board. Hubungi Project Manager untuk membuat board.')" 
                                            class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg font-semibold text-sm cursor-not-allowed flex items-center justify-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>No Board Available
                                    </button>
                                @endif

                                <!-- Quick Actions: My Tasks & Members -->
                                <div class="flex space-x-2">
                                    <button onclick="filterTasksByProject({{ $project->id }}, '{{ addslashes($project->project_name) }}')" 
                                            class="flex-1 bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded-lg font-semibold text-xs transition">
                                        <i class="fas fa-tasks mr-1"></i>My Tasks
                                    </button>

                                    <button onclick="showProjectMembers({{ $project->id }}, '{{ addslashes($project->project_name) }}')" 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg font-semibold text-xs transition">
                                        <i class="fas fa-users mr-1"></i>Members
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-project-diagram text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Proyek</h3>
                        <p class="text-gray-500">Anda belum tergabung dalam proyek apapun</p>
                    </div>
                    @endforelse
                </div>
            </div>

<!-- Modal: Project Members -->
<div id="membersModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="membersModalTitle">Project Members</h3>
            <button onclick="closeMembersModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div id="membersModalContent" class="space-y-3">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

            </div>

            <!-- ============================================ -->
            <!-- TAB 3: TIME TRACK -->
            <!-- ============================================ -->
            <div id="content-timetrack" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Today's Summary -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Hari Ini</h3>
                                <p class="text-sm opacity-80">{{ now()->isoFormat('dddd, D MMM') }}</p>
                            </div>
                            <i class="fas fa-clock text-4xl opacity-20"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ number_format($todayTotalHours ?? 0, 1) }}</div>
                        <p class="text-sm opacity-80">Total Hours</p>
                    </div>

                    <!-- Week Summary -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Minggu Ini</h3>
                                <p class="text-sm opacity-80">Last 7 days</p>
                            </div>
                            <i class="fas fa-calendar-week text-4xl opacity-20"></i>
                        </div>
                        @php
                            $weekHours = \App\Models\TimeLog::where('user_id', Auth::id())
                                ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                                ->whereNotNull('duration_minutes')
                                ->sum('duration_minutes') / 60;
                        @endphp
                        <div class="text-4xl font-bold mb-2">{{ number_format($weekHours, 1) }}</div>
                        <p class="text-sm opacity-80">Total Hours</p>
                    </div>

                    <!-- Month Summary -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Bulan Ini</h3>
                                <p class="text-sm opacity-80">{{ now()->format('F Y') }}</p>
                            </div>
                            <i class="fas fa-calendar text-4xl opacity-20"></i>
                        </div>
                        @php
                            $monthHours = \App\Models\TimeLog::where('user_id', Auth::id())
                                ->whereYear('start_time', now()->year)
                                ->whereMonth('start_time', now()->month)
                                ->whereNotNull('duration_minutes')
                                ->sum('duration_minutes') / 60;
                        @endphp
                        <div class="text-4xl font-bold mb-2">{{ number_format($monthHours, 1) }}</div>
                        <p class="text-sm opacity-80">Total Hours</p>
                    </div>
                </div>

                <!-- Time Log History -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Time Log</h2>
                    <div class="space-y-3">
                        @forelse($myTimeLogs->take(15) ?? [] as $log)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 hover:bg-gray-50 transition px-2 rounded">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm">{{ $log->card->card_title ?? 'Unknown Task' }}</p>
                                <p class="text-xs text-gray-500">{{ $log->card->board->project->project_name ?? '-' }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($log->start_time)->format('d M Y, H:i') }}
                                    @if($log->end_time)
                                        - {{ \Carbon\Carbon::parse($log->end_time)->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-blue-600">
                                    {{ number_format(($log->duration_minutes ?? 0) / 60, 1) }}h
                                </span>
                                <p class="text-xs text-gray-500">
                                    {{ $log->duration_minutes ?? 0 }} min
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-clock text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada riwayat time log</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- TAB 4: ACHIEVEMENTS -->
            <!-- ============================================ -->
            <div id="content-achievements" class="tab-content hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Achievements</h2>
                    <p class="text-gray-600">Badges yang telah Anda raih</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Achievement Cards -->
                    @if(($completedThisMonth ?? 0) >= 10)
                    <div class="bg-white rounded-xl shadow-sm border-2 border-purple-200 p-6 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-award text-3xl text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">10+ Tasks Master</h3>
                        <p class="text-sm text-gray-600 mb-4 text-center">Menyelesaikan 10+ tasks dalam sebulan</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-purple-600 font-semibold px-3 py-1 bg-purple-100 rounded-full">
                                <i class="fas fa-check mr-1"></i>UNLOCKED
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 text-center mt-2">{{ now()->format('M Y') }}</p>
                    </div>
                    @endif

                    @if(($onTimeRate ?? 0) >= 90)
                    <div class="bg-white rounded-xl shadow-sm border-2 border-blue-200 p-6 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-rocket text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">On-Time Champion</h3>
                        <p class="text-sm text-gray-600 mb-4 text-center">90%+ delivery tepat waktu</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-blue-600 font-semibold px-3 py-1 bg-blue-100 rounded-full">
                                <i class="fas fa-check mr-1"></i>UNLOCKED
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 text-center mt-2">{{ now()->format('M Y') }}</p>
                    </div>
                    @endif

                    @if(($productivityRating ?? 0) >= 4.5)
                    <div class="bg-white rounded-xl shadow-sm border-2 border-green-200 p-6 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-medal text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">Productivity Star</h3>
                        <p class="text-sm text-gray-600 mb-4 text-center">Rating produktivitas 4.5+/5</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-green-600 font-semibold px-3 py-1 bg-green-100 rounded-full">
                                <i class="fas fa-check mr-1"></i>UNLOCKED
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 text-center mt-2">{{ now()->format('M Y') }}</p>
                    </div>
                    @endif

                    @if(($averageTimePerTask ?? 0) > 0 && ($averageTimePerTask ?? 0) <= 2)
                    <div class="bg-white rounded-xl shadow-sm border-2 border-orange-200 p-6 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-bolt text-3xl text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">Speed Demon</h3>
                        <p class="text-sm text-gray-600 mb-4 text-center">Rata-rata ≤2 jam per task</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-orange-600 font-semibold px-3 py-1 bg-orange-100 rounded-full">
                                <i class="fas fa-check mr-1"></i>UNLOCKED
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 text-center mt-2">{{ now()->format('M Y') }}</p>
                    </div>
                    @endif

                    <!-- Locked Achievements -->
                    <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 p-6 opacity-60">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-lock text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-600 mb-2 text-center">Early Bird</h3>
                        <p class="text-sm text-gray-500 mb-4 text-center">Start task sebelum jam 8 pagi, 5 hari berturut-turut</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-gray-400 font-semibold px-3 py-1 bg-gray-100 rounded-full">
                                <i class="fas fa-lock mr-1"></i>LOCKED
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 p-6 opacity-60">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-lock text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-600 mb-2 text-center">Night Owl</h3>
                        <p class="text-sm text-gray-500 mb-4 text-center">Bekerja hingga malam (after 10 PM)</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-gray-400 font-semibold px-3 py-1 bg-gray-100 rounded-full">
                                <i class="fas fa-lock mr-1"></i>LOCKED
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 p-6 opacity-60">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-lock text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-600 mb-2 text-center">Marathon Runner</h3>
                        <p class="text-sm text-gray-500 mb-4 text-center">Bekerja 8+ jam dalam sehari</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-xs text-gray-400 font-semibold px-3 py-1 bg-gray-100 rounded-full">
                                <i class="fas fa-lock mr-1"></i>LOCKED
                            </span>
                        </div>
                    </div>
                </div>

               <!-- Progress Section - FIX PHP 8.2 ERROR -->
                <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Your Progress</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            @php
                                // Fix: Tidak pakai nested ternary tanpa kurung
                                $achievementCount = 1;
                                if (($completedThisMonth ?? 0) >= 10) {
                                    $achievementCount = 4;
                                } elseif (($completedThisMonth ?? 0) >= 5) {
                                    $achievementCount = 2;
                                }
                            @endphp
                            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $achievementCount }}</div>
                            <p class="text-sm text-gray-600">Achievements Unlocked</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600 mb-1">{{ $completedThisMonth ?? 0 }}</div>
                            <p class="text-sm text-gray-600">Tasks Completed</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-1">{{ number_format($productivityRating ?? 0, 1) }}/5</div>
                            <p class="text-sm text-gray-600">Productivity Score</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600', 'font-semibold');
        tab.classList.add('border-transparent', 'text-gray-600', 'font-medium');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-600', 'font-medium');
    activeTab.classList.add('border-blue-500', 'text-blue-600', 'font-semibold');
    
    // Save to localStorage
    localStorage.setItem('activeTab', tabName);
}

// Restore active tab on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTab = localStorage.getItem('activeTab') || 'tasks';
    switchTab(savedTab);
});

// Timer functionality
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

// Close modal when clicking outside
document.getElementById('commentModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        toggleCommentModal();
    }
});

// Filter tasks by project
function filterTasksByProject(projectId, projectName) {
    switchTab('tasks');
    
    setTimeout(() => {
        alert('Filtering tasks for: ' + projectName + '\n\nFeature will show only tasks from this project.');
        // TODO: Implement actual filtering logic
    }, 300);
}

// Show project members modal
function showProjectMembers(projectId, projectName) {
    const modal = document.getElementById('membersModal');
    const title = document.getElementById('membersModalTitle');
    const content = document.getElementById('membersModalContent');
    
    title.textContent = projectName + ' - Team Members';
    content.innerHTML = '<p class="text-center text-gray-500 py-4">Loading members...</p>';
    
    modal.classList.remove('hidden');
    
    // Simulate loading (you can replace with AJAX call)
    setTimeout(() => {
        content.innerHTML = `
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-users text-4xl mb-2"></i>
                <p class="text-sm">Member list feature - Coming Soon</p>
                <p class="text-xs mt-2">You can implement AJAX call to load members here</p>
            </div>
        `;
    }, 500);
}

function closeMembersModal() {
    document.getElementById('membersModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('membersModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeMembersModal();
    }
});

</script>
@endpush
