@extends('layouts.admin')

@section('title', 'Member')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Project: {{ $project->project_name }}</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- To Do Column -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-50 border-b border-blue-100 px-4 py-3">
                <h5 class="text-lg font-semibold text-blue-900">To Do</h5>
            </div>
            <div class="p-3 space-y-3 overflow-y-auto" style="max-height: 75vh;">
                @forelse($boards['todo'] as $card)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h6 class="font-semibold text-gray-800 mb-2">{{ $card->card_title }}</h6>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <div class="mb-3">
                            <span class="text-xs font-medium text-gray-700">PIC: </span>
                            @foreach($card->assignments as $assignment)
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    {{ $assignment->user->role }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Start Button -->
                        <button onclick="startWork({{ $card->id }})"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors mb-3">
                            Mulai Kerjakan
                        </button>

                        <!-- Subtasks -->
                        @if($card->subtasks->count() > 0)
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Subtasks</p>
                                <ul class="space-y-1">
                                    @foreach($card->subtasks as $subtask)
                                        <li class="flex items-start text-sm text-gray-600">
                                            <input type="checkbox" {{ $subtask->status == 'done' ? 'checked' : '' }} disabled class="mt-1 mr-2">
                                            <span>{{ $subtask->subtask_title }} - {{ $subtask->description }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Komentar</p>
                            <div class="space-y-2 mb-3 max-h-32 overflow-y-auto">
                                @forelse($card->comments as $comment)
                                    <div class="text-xs bg-gray-50 p-2 rounded">
                                        <p class="font-medium text-gray-800">{{ $comment->user->username }}</p>
                                        <p class="text-gray-600">{{ $comment->comment_text }}</p>
                                        <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">Belum ada komentar.</p>
                                @endforelse
                            </div>

                            <form action="{{ route('panel.member.comment', $card->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="comment_text"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Tulis komentar..." required>
                                <button type="submit"
                                        class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada task.</p>
                @endforelse
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-yellow-50 border-b border-yellow-100 px-4 py-3">
                <h5 class="text-lg font-semibold text-yellow-900">In Progress</h5>
            </div>
            <div class="p-3 space-y-3 overflow-y-auto" style="max-height: 75vh;">
                @forelse($boards['in_progress'] as $card)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h6 class="font-semibold text-gray-800 mb-2">{{ $card->card_title }}</h6>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <div class="mb-3">
                            <span class="text-xs font-medium text-gray-700">PIC: </span>
                            @foreach($card->assignments as $assignment)
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    {{ $assignment->user->role }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Time Tracking -->
                        <div class="mb-3 bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Time Tracking</p>
                            <div class="text-center mb-3">
                                <h4 class="text-2xl font-mono font-bold text-gray-800" id="timer-{{ $card->id }}">
                                    @if($card->activeTimeLog)
                                        <span class="timer-display" data-start="{{ $card->activeTimeLog->start_time }}">00:00:00</span>
                                    @else
                                        00:00:00
                                    @endif
                                </h4>
                            </div>
                            @if($card->activeTimeLog)
                                <button onclick="stopWork({{ $card->id }})"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors mb-2">
                                    Stop
                                </button>
                            @endif
                            <button onclick="requestReview({{ $card->id }})"
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                Minta Review
                            </button>

                            <button onclick="requestBlocker({{ $card->id }})"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                Blocker
                            </button>

                        </div>

                        <!-- Subtasks -->
                        @if($card->subtasks->count() > 0)
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Subtasks</p>
                                <ul class="space-y-1">
                                    @foreach($card->subtasks as $subtask)
                                        <li class="flex items-start text-sm text-gray-600">
                                            <input type="checkbox" {{ $subtask->status == 'done' ? 'checked' : '' }} disabled class="mt-1 mr-2">
                                            <span>{{ $subtask->subtask_title }} - {{ $subtask->description }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Komentar</p>
                            <div class="space-y-2 mb-3 max-h-32 overflow-y-auto">
                                @forelse($card->comments as $comment)
                                    <div class="text-xs bg-gray-50 p-2 rounded">
                                        <p class="font-medium text-gray-800">{{ $comment->user->username }}</p>
                                        <p class="text-gray-600">{{ $comment->comment_text }}</p>
                                        <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">Belum ada komentar.</p>
                                @endforelse
                            </div>

                            <form action="{{ route('panel.member.comment', $card->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="comment_text"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Tulis komentar..." required>
                                <button type="submit"
                                        class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada task.</p>
                @endforelse
            </div>
        </div>

        <!-- Review Column -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-purple-50 border-b border-purple-100 px-4 py-3">
                <h5 class="text-lg font-semibold text-purple-900">Review</h5>
            </div>
            <div class="p-3 space-y-3 overflow-y-auto" style="max-height: 75vh;">
                @forelse($combinedBoards as $card)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow @if($card->status === 'blocker') animate-pulse-red @endif">
                        <h6 class="font-semibold text-gray-800 mb-2">{{ $card->card_title }}</h6>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <div class="mb-3">
                            <span class="text-xs font-medium text-gray-700">PIC: </span>
                            @foreach($card->assignments as $assignment)
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    {{ $assignment->user->role }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Komentar</p>
                            <div class="space-y-2 mb-3 max-h-32 overflow-y-auto">
                                @forelse($card->comments as $comment)
                                    <div class="text-xs bg-gray-50 p-2 rounded">
                                        <p class="font-medium text-gray-800">{{ $comment->user->username }}</p>
                                        <p class="text-gray-600">{{ $comment->comment_text }}</p>
                                        <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">Belum ada komentar.</p>
                                @endforelse
                            </div>

                            <form action="{{ route('panel.member.comment', $card->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="comment_text"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Tulis komentar..." required>
                                <button type="submit"
                                        class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada tugas yang perlu direview.</p>
                @endforelse
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-green-50 border-b border-green-100 px-4 py-3">
                <h5 class="text-lg font-semibold text-green-900">Done</h5>
            </div>
            <div class="p-3 space-y-3 overflow-y-auto" style="max-height: 75vh;">
                @forelse($boards['done'] as $card)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h6 class="font-semibold text-gray-800 mb-2">{{ $card->card_title }}</h6>
                        <p class="text-sm text-gray-600 mb-3">{{ $card->description }}</p>

                        <div class="mb-3">
                            <span class="text-xs font-medium text-gray-700">PIC: </span>
                            @foreach($card->assignments as $assignment)
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    {{ $assignment->user->role }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Subtasks -->
                        @if($card->subtasks->count() > 0)
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Subtasks</p>
                                <ul class="space-y-1">
                                    @foreach($card->subtasks as $subtask)
                                        <li class="flex items-start text-sm text-gray-600">
                                            <input type="checkbox" {{ $subtask->status == 'done' ? 'checked' : '' }} disabled class="mt-1 mr-2">
                                            <span>{{ $subtask->subtask_title }} - {{ $subtask->description }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Komentar</p>
                            <div class="space-y-2 mb-3 max-h-32 overflow-y-auto">
                                @forelse($card->comments as $comment)
                                    <div class="text-xs bg-gray-50 p-2 rounded">
                                        <p class="font-medium text-gray-800">{{ $comment->user->username }}</p>
                                        <p class="text-gray-600">{{ $comment->comment_text }}</p>
                                        <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">Belum ada komentar.</p>
                                @endforelse
                            </div>

                            <form action="{{ route('panel.member.comment', $card->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="comment_text"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Tulis komentar..." required>
                                <button type="submit"
                                        class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada task.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function startWork(cardId) {
    if(confirm('Mulai mengerjakan task ini?')) {
        fetch(`/panel/member/card/${cardId}/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}

function stopWork(cardId) {
    if(confirm('Stop time tracking?')) {
        fetch(`/panel/member/card/${cardId}/stop`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}

function requestReview(cardId) {
    if(confirm('Minta review untuk task ini?')) {
        fetch(`/panel/member/card/${cardId}/review`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}

function requestBlocker(cardId) {
    if(confirm('Minta blocker untuk task ini?')) {
        fetch(`/panel/member/card/${cardId}/blocker`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}

// Timer functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.timer-display').forEach(function(timer) {
        const startTime = new Date(timer.dataset.start).getTime();

        setInterval(function() {
            const now = new Date().getTime();
            const diff = now - startTime;

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            timer.textContent =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }, 1000);
    });
});
</script>
@endsection
