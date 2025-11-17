<!-- resources/views/components/card-item.blade.php -->
<div class="card-item bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition cursor-pointer"
     data-card-id="{{ $card->id }}"
     onclick="window.location='<?php //{{ route('admin.cards.show', $card) }} ?>'">

    <!-- Labels -->
    @if($card->labels && count($card->labels) > 0)
    <div class="flex flex-wrap gap-2 mb-3">
        @foreach($card->labels as $label)
            @php
                $labelColors = [
                    'Email Campaign' => 'bg-purple-100 text-purple-700',
                    'Blog' => 'bg-green-100 text-green-700',
                    'Website' => 'bg-orange-100 text-orange-700',
                    'Social Media' => 'bg-yellow-100 text-yellow-700',
                    'SEO' => 'bg-blue-100 text-blue-700',
                ];
                $colorClass = $labelColors[$label] ?? 'bg-gray-100 text-gray-700';
            @endphp
            <span class="text-xs font-medium px-2 py-1 rounded {{ $colorClass }}">
                {{ $label }}
            </span>
        @endforeach
    </div>
    @endif

    <!-- Title -->
    <h4 class="text-sm font-semibold text-gray-800 mb-2">{{ $card->title }}</h4>
    <a href="{{ route('admin.cards.show', [$card->id]) }}"
    class="block bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition group">view cards</a>
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
        <span>{{ \Carbon\Carbon::parse($card->due_date)->format('M d') }}</span>

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

    <!-- Footer: Stats & Assignees -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <!-- Stats -->
        <div class="flex items-center space-x-3 text-xs text-gray-500">
            @if($card->checklist_total > 0)
            <div class="flex items-center space-x-1">
                <i class="far fa-check-square"></i>
                <span>{{ $card->checklist_completed }}/{{ $card->checklist_total }}</span>
            </div>
            @endif

            @if($card->comments_count > 0)
            <div class="flex items-center space-x-1">
                <i class="far fa-comment"></i>
                <span>{{ $card->comments_count }}</span>
            </div>
            @endif

            @if($card->attachments_count > 0)
            <div class="flex items-center space-x-1">
                <i class="fas fa-paperclip"></i>
                <span>{{ $card->attachments_count }}</span>
            </div>
            @endif
        </div>

        <!-- Assigned Members -->
        @if($card->assignedMembers && $card->assignedMembers->count() > 0)
        <div class="flex -space-x-2">
            @foreach($card->assignedMembers->take(3) as $member)
            <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                 alt="{{ $member->full_name }}"
                 title="{{ $member->full_name }}"
                 class="w-6 h-6 rounded-full border-2 border-white">
            @endforeach

            @if($card->assignedMembers->count() > 3)
            <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center">
                <span class="text-xs font-semibold text-gray-600">+{{ $card->assignedMembers->count() - 3 }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Priority Indicator -->
    @if($card->priority === 'high')
    <div class="absolute top-2 right-2">
        <i class="fas fa-exclamation-circle text-red-500"></i>
    </div>
    @endif
</div>
