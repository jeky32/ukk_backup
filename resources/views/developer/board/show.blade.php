@extends('layouts.admin')

@section('title', $board->board_name . ' - ' . $project->project_name)
@section('page-title', $board->board_name)
@section('page-subtitle', $project->project_name)

@section('content')
<main class="ml-2 mt-16 p-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('developer.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Project Info -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $project->project_name }}</h2>
                <p class="text-gray-600 mt-1">{{ $project->description }}</p>
            </div>
            <div class="text-right">
                @if($project->deadline)
                <div class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                </div>
                @endif
                <div class="mt-2">
                    <span class="px-3 py-1 bg-{{ $project->status === 'approved' ? 'green' : 'yellow' }}-100 text-{{ $project->status === 'approved' ? 'green' : 'yellow' }}-800 rounded-full text-xs font-semibold">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Board Cards -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-columns mr-2 text-blue-600"></i>
            {{ $board->board_name }} - Tasks
        </h3>

        @if($board->description)
        <p class="text-gray-600 mb-6">{{ $board->description }}</p>
        @endif

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($board->cards as $card)
            <div class="bg-gray-50 rounded-lg border-2 border-gray-200 p-4 hover:border-blue-400 transition">
                <!-- Card Header -->
                <div class="flex items-start justify-between mb-3">
                    <h4 class="font-bold text-gray-800 flex-1">{{ $card->card_title }}</h4>
                    
                    <!-- Priority Badge -->
                    @if($card->priority === 'high')
                        <span class="px-2 py-1 bg-red-500 text-white rounded text-xs font-bold ml-2">
                            <i class="fas fa-fire"></i>
                        </span>
                    @elseif($card->priority === 'medium')
                        <span class="px-2 py-1 bg-orange-500 text-white rounded text-xs font-bold ml-2">
                            <i class="fas fa-exclamation"></i>
                        </span>
                    @else
                        <span class="px-2 py-1 bg-green-500 text-white rounded text-xs font-bold ml-2">
                            <i class="fas fa-check"></i>
                        </span>
                    @endif
                </div>

                <!-- Card Description -->
                @if($card->description)
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($card->description, 80) }}</p>
                @endif

                <!-- Card Meta -->
                <div class="space-y-2 mb-3 text-xs text-gray-500">
                    @if($card->due_date)
                    <div class="flex items-center">
                        <i class="fas fa-calendar-day mr-2 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($card->due_date)->format('d M Y') }}</span>
                    </div>
                    @endif

                    @if($card->estimated_hours)
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2 w-4"></i>
                        <span>{{ $card->estimated_hours }}h estimated</span>
                    </div>
                    @endif

                    <div class="flex items-center">
                        <i class="fas fa-comment mr-2 w-4"></i>
                        <span>{{ $card->comments->count() }} comments</span>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-3">
                    @php
                        $statusColors = [
                            'todo' => 'gray',
                            'in_progress' => 'blue',
                            'review' => 'yellow',
                            'done' => 'green',
                            'blocker' => 'red'
                        ];
                        $color = $statusColors[$card->status] ?? 'gray';
                    @endphp
                    <span class="px-3 py-1 bg-{{ $color }}-100 text-{{ $color }}-800 rounded-full text-xs font-bold">
                        {{ ucfirst(str_replace('_', ' ', $card->status)) }}
                    </span>
                </div>

                <!-- Assigned Users -->
                @if($card->assignments->isNotEmpty())
                <div class="flex items-center space-x-2 mb-3">
                    <span class="text-xs text-gray-500">Assigned to:</span>
                    <div class="flex -space-x-2">
                        @foreach($card->assignments->take(3) as $assignment)
                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white" 
                             title="{{ $assignment->user->full_name }}">
                            {{ strtoupper(substr($assignment->user->full_name, 0, 1)) }}
                        </div>
                        @endforeach
                        @if($card->assignments->count() > 3)
                        <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">
                            +{{ $card->assignments->count() - 3 }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Button (if assigned to current user) -->
                @php
                    $myAssignment = $card->assignments->where('user_id', Auth::id())->first();
                @endphp

                @if($myAssignment)
                <a href="{{ route('developer.dashboard') }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg font-semibold text-xs text-center transition">
                    <i class="fas fa-play mr-1"></i>Work on this Task
                </a>
                @else
                <button disabled class="block w-full bg-gray-300 text-gray-500 px-3 py-2 rounded-lg font-semibold text-xs text-center cursor-not-allowed">
                    <i class="fas fa-lock mr-1"></i>Not Assigned to You
                </button>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-gray-400">
                <i class="fas fa-inbox text-5xl mb-3"></i>
                <p>No cards in this board yet</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- My Tasks in this Board -->
    @if($myAssignments->isNotEmpty())
    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-user-check mr-2 text-blue-600"></i>
            My Tasks in This Board
        </h3>
        <div class="space-y-3">
            @foreach($myAssignments as $assignment)
            <div class="bg-white rounded-lg p-4 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-gray-800">{{ $assignment->card->card_title }}</h4>
                    <p class="text-sm text-gray-500">Status: {{ ucfirst($assignment->assignment_status) }}</p>
                </div>
                <a href="{{ route('developer.dashboard') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                    Go to Dashboard
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</main>
@endsection
