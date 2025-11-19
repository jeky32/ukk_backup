@extends('layouts.teamlead')

@section('title', 'Review Tasks')
@section('page-title', 'Review & Approval')
@section('page-subtitle', 'Review task yang sudah diselesaikan oleh tim')

@push('styles')
<style>
    .review-card {
        transition: all 0.3s ease;
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <!-- Pending Review -->
            <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-semibold mb-1">Pending Review</p>
                        <h3 class="text-4xl font-bold">{{ $pendingCards->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Approved Today -->
            <div class="bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-semibold mb-1">Approved Today</p>
                        <h3 class="text-4xl font-bold">{{ $approvedToday }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Rejected Today -->
            <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-semibold mb-1">Rejected Today</p>
                        <h3 class="text-4xl font-bold">{{ $rejectedToday }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-times-circle text-3xl"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pending Review Cards -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-clipboard-check text-yellow-500 mr-2"></i>
                    Tasks Waiting for Review
                </h2>
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-semibold text-sm">
                    {{ $pendingCards->count() }} Tasks
                </span>
            </div>

            @forelse($pendingCards as $card)
            <div class="review-card bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-xl p-6 mb-4">
                
                <!-- Header: Card Title & Project -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $card->card_title }}</h3>
                        <div class="flex items-center space-x-3 text-sm text-gray-600">
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
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                 {{ $card->priority === 'high' ? 'bg-red-100 text-red-700' : 
                                    ($card->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                        <i class="fas fa-flag mr-1"></i>
                        {{ ucfirst($card->priority) }} Priority
                    </span>
                </div>

                <!-- Description -->
                @if($card->description)
                <p class="text-gray-700 mb-4 line-clamp-2">{{ $card->description }}</p>
                @endif

                <!-- Assigned To & Submitted Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    
                    <!-- Assigned Developers -->
                    <div class="bg-white rounded-lg p-3">
                        <p class="text-xs text-gray-500 font-semibold mb-2">Assigned To:</p>
                        <div class="flex items-center space-x-2">
                            @foreach($card->assignments as $assignment)
                            <div class="flex items-center space-x-2 bg-blue-50 rounded-lg px-3 py-1">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($assignment->user->full_name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-800">{{ $assignment->user->full_name }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Time Info -->
                    <div class="bg-white rounded-lg p-3">
                        <p class="text-xs text-gray-500 font-semibold mb-2">Submitted:</p>
                        <p class="text-sm font-bold text-gray-800">
                            <i class="fas fa-clock text-yellow-500 mr-1"></i>
                            {{ $card->updated_at->diffForHumans() }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $card->updated_at->format('d M Y, H:i') }}</p>
                    </div>

                </div>

                <!-- Subtasks Progress (if any) -->
                @if($card->subtasks->count() > 0)
                <div class="bg-white rounded-lg p-3 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500 font-semibold">Subtasks Progress:</span>
                        <span class="text-xs font-bold text-gray-800">
                            {{ $card->subtasks->where('status', 'done')->count() }}/{{ $card->subtasks->count() }} Completed
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full" 
                             style="width: {{ ($card->subtasks->where('status', 'done')->count() / $card->subtasks->count()) * 100 }}%">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    
                    <!-- View Details -->
                    <a href="{{ route('teamlead.review.detail', $card->id) }}" 
                       class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2.5 rounded-xl font-semibold transition">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </a>

                    <!-- Approve Button -->
                    <form action="{{ route('teamlead.approve.task', $card->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Approve this task?')"
                                class="w-full bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-xl font-semibold transition">
                            <i class="fas fa-check mr-2"></i>Approve
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button onclick="openRejectModal({{ $card->id }}, '{{ addslashes($card->card_title) }}')"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-semibold transition">
                        <i class="fas fa-times mr-2"></i>Reject
                    </button>

                </div>

            </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-check-double text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">All Caught Up!</h3>
                <p class="text-gray-500">No tasks waiting for review</p>
            </div>
            @endforelse

        </div>

        <!-- Recently Reviewed (Last 5) -->
        @if($recentlyReviewed->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-gray-500 mr-2"></i>
                Recently Reviewed
            </h2>
            
            <div class="space-y-3">
                @foreach($recentlyReviewed as $card)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $card->card_title }}</h4>
                        <p class="text-xs text-gray-500">{{ $card->board->project->project_name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                     {{ $card->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $card->status === 'done' ? 'Approved' : 'In Progress' }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $card->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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

        <p class="text-gray-600 mb-4">Card: <strong id="rejectCardTitle"></strong></p>

        <form id="rejectForm" method="POST">
            @csrf
            
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Reason for Rejection <span class="text-red-500">*</span>
            </label>
            <textarea name="reason" 
                      required
                      rows="4"
                      placeholder="Explain what needs to be fixed..."
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"></textarea>

            <div class="flex space-x-3 mt-4">
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2.5 rounded-xl font-semibold transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Reject Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(cardId, cardTitle) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectCardTitle').textContent = cardTitle;
    document.getElementById('rejectForm').action = `/teamlead/reject-task/${cardId}`;
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRejectModal();
});
</script>
@endpush
