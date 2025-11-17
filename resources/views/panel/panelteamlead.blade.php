@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Project: {{ $project->project_name }} - Team Lead Panel</h3>
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
                        <div class="flex justify-between items-start mb-2">
                            <h6 class="font-semibold text-gray-800">{{ $card->card_title }}</h6>
                            <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs font-medium rounded">#{{ $card->id }}</span>
                        </div>

                        <div class="mb-3 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-blue-600">{{ $card->total_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Dev:</span>
                                <span class="font-semibold text-cyan-600">{{ $card->dev_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Designer:</span>
                                <span class="font-semibold text-amber-600">{{ $card->designer_time }}</span>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3 mb-3">
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

                            <form action="{{ route('panel.teamlead.comment', $card->id) }}" method="POST" class="flex gap-2">
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

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Start
                                </button>
                            </form>
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="review">
                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Review
                                </button>
                            </form>
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="done">
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Done
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
                        <div class="flex justify-between items-start mb-2">
                            <h6 class="font-semibold text-gray-800">{{ $card->card_title }}</h6>
                            <span class="px-2 py-1 bg-yellow-200 text-yellow-800 text-xs font-medium rounded">#{{ $card->id }}</span>
                        </div>

                        <div class="mb-3 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-blue-600">{{ $card->total_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Dev:</span>
                                <span class="font-semibold text-cyan-600">{{ $card->dev_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Designer:</span>
                                <span class="font-semibold text-amber-600">{{ $card->designer_time }}</span>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3 mb-3">
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

                            <form action="{{ route('panel.teamlead.comment', $card->id) }}" method="POST" class="flex gap-2">
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
                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="todo">
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Back
                                </button>
                            </form>
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="review">
                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Review
                                </button>
                            </form>
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="done">
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Done
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
                        <div class="flex justify-between items-start mb-2">
                            <h6 class="font-semibold text-gray-800">{{ $card->card_title }}</h6>
                            <span class="px-2 py-1 bg-purple-200 text-purple-800 text-xs font-medium rounded">#{{ $card->id }}</span>
                        </div>

                        <div class="mb-3 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-blue-600">{{ $card->total_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Dev:</span>
                                <span class="font-semibold text-cyan-600">{{ $card->dev_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Designer:</span>
                                <span class="font-semibold text-amber-600">{{ $card->designer_time }}</span>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3 mb-3">
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

                            <form action="{{ route('panel.teamlead.comment', $card->id) }}" method="POST" class="flex gap-2">
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

                        <!-- Action Buttons -->
                        <div class="flex gap-2">

                            @if($card->status === 'blocker')
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="todo">
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Selesai Blocker
                                </button>
                            </form>

                            @else
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="todo">
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Back
                                </button>
                            </form>
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="review">
                                <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Review
                                </button>
                            </form>
                            <form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="done">
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                    Done
                                </button>
                            </form>
                            @endif
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
                        <div class="flex justify-between items-start mb-2">
                            <h6 class="font-semibold text-gray-800">{{ $card->card_title }}</h6>
                            <span class="px-2 py-1 bg-green-200 text-green-800 text-xs font-medium rounded">#{{ $card->id }}</span>
                        </div>

                        <div class="mb-3 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-blue-600">{{ $card->total_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Dev:</span>
                                <span class="font-semibold text-cyan-600">{{ $card->dev_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Designer:</span>
                                <span class="font-semibold text-amber-600">{{ $card->designer_time }}</span>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="border-t border-gray-200 pt-3 mb-3">
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

                            <form action="{{ route('panel.teamlead.comment', $card->id) }}" method="POST" class="flex gap-2">
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

                        <!-- Action Button -->
                        <!--button onclick="changeStatus({{ $card->id }}, 'todo')"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                            Kembalikan ke To Do
                        </button-->
						<form action="{{ route('panel.teamlead.status', $card->id) }}" method="POST" class="flex-1">
							@csrf
							<input type="hidden" name="status" value="todo">
							<button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
								Kembalikan ke To Do
							</button>
						</form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada task.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function changeStatus(cardId, newStatus) {
    let statusMap = {
        'todo': 'To Do',
        'review': 'Review',
        'done': 'Done'
    };

    let confirmMessage = `Pindahkan card ke ${statusMap[newStatus]}?`;

    if(confirm(confirmMessage)) {
        fetch(`/panel/teamlead/card/${cardId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal mengubah status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endsection
