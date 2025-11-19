<!-- Card Item for Team Lead -->
<div class="card-item bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition cursor-pointer relative"
     data-card-id="{{ $card->id }}"
     onclick="window.location='{{ route('teamlead.card.detail', $card->id) }}'">

    <!-- Priority Indicator (Top Right) -->
    @if($card->priority === 'high')
    <div class="absolute top-2 right-2">
        <i class="fas fa-exclamation-circle text-red-500"></i>
    </div>
    @elseif($card->priority === 'medium')
    <div class="absolute top-2 right-2">
        <i class="fas fa-exclamation-triangle text-orange-500"></i>
    </div>
    @endif

    <!-- Title -->
    <h4 class="text-sm font-semibold text-gray-800 mb-2 pr-6">{{ $card->card_title }}</h4>

    <!-- Description (if exists) -->
    @if($card->description)
    <p class="text-xs text-gray-600 mb-3 line-clamp-2">
        {{ Str::limit($card->description, 80) }}
    </p>
    @endif

    <!-- Due Date -->
    @if($card->due_date)
    <div class="flex items-center space-x-1 text-xs text-gray-500 mb-3">
        <i class="far fa-clock"></i>
        <span>{{ \Carbon\Carbon::parse($card->due_date)->format('M d, Y') }}</span>

        @php
            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($card->due_date), false);
        @endphp

        @if($daysLeft < 0)
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-medium">Overdue</span>
        @elseif($daysLeft <= 2)
            <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Due Soon</span>
        @endif
    </div>
    @endif

    <!-- Estimated Hours -->
    @if($card->estimated_hours)
    <div class="flex items-center space-x-1 text-xs text-gray-500 mb-3">
        <i class="fas fa-hourglass-half"></i>
        <span>{{ $card->estimated_hours }}h estimated</span>
    </div>
    @endif

    <!-- Footer: Stats & Assignees -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <!-- Stats -->
        <div class="flex items-center space-x-3 text-xs text-gray-500">
            <!-- Subtasks Count -->
            @if($card->subtasks && $card->subtasks->count() > 0)
            <div class="flex items-center space-x-1">
                <i class="far fa-check-square"></i>
                <span>{{ $card->subtasks->where('status', 'done')->count() }}/{{ $card->subtasks->count() }}</span>
            </div>
            @endif

            <!-- Comments Count -->
            @if($card->comments && $card->comments->count() > 0)
            <div class="flex items-center space-x-1">
                <i class="far fa-comment"></i>
                <span>{{ $card->comments->count() }}</span>
            </div>
            @endif
        </div>

        <!-- Assigned Members (Developers) -->
        @if($card->assignments && $card->assignments->count() > 0)
        <div class="flex -space-x-2">
            @foreach($card->assignments->take(3) as $assignment)
            <img src="https://ui-avatars.com/api/?name={{ urlencode($assignment->user->full_name) }}&size=24&background=6366f1&color=fff"
                 alt="{{ $assignment->user->full_name }}"
                 title="{{ $assignment->user->full_name }}"
                 class="w-6 h-6 rounded-full border-2 border-white">
            @endforeach

            @if($card->assignments->count() > 3)
            <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center">
                <span class="text-xs font-semibold text-gray-600">+{{ $card->assignments->count() - 3 }}</span>
            </div>
            @endif
        </div>
        @else
        <div class="text-xs text-gray-400 italic">
            <i class="fas fa-user-slash mr-1"></i>
            Unassigned
        </div>
        @endif
    </div>

    <!-- Status Badge (Optional, if you want to show it) -->
    @if(in_array($card->status, ['blocker']))
    <div class="mt-2">
        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">
            <i class="fas fa-ban mr-1"></i>
            Blocked
        </span>
    </div>
    @endif
</div>
