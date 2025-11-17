@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Activity Log</h1>
    </div>

    <!-- Activities Timeline -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="space-y-6">
            @forelse($activities as $activity)
            <div class="flex items-start">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <i class="fas {{ $activity->icon }} {{ $activity->color }}"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $activity->description }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                by {{ $activity->user->full_name }}
                                @if($activity->project)
                                · in {{ $activity->project->project_name }}
                                @endif
                            </p>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            <span title="{{ userTime($activity->created_at) }}">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Additional details -->
                    @if($activity->task)
                    <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                        Task: <a href="#" class="text-blue-600 hover:underline">{{ $activity->task->title }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="fas fa-history text-4xl mb-2"></i>
                <p>No activities yet</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
