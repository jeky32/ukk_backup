@extends('layouts.teamlead')

@section('title', 'Project Detail - ' . $project->project_name)
@section('page-title', 'PROJECT DETAIL')
@section('page-subtitle', $project->project_name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Project Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">{{ $project->project_name }}</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $project->description }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-calendar text-blue-600"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Deadline</p>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'No deadline' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <i class="fas fa-chart-line text-green-600"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Progress</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $project->progress }}%</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <i class="fas fa-users text-purple-600"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Team Size</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $project->members->count() }} members</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Team Performance</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teamPerformance as $performance)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($performance['member']->full_name ?? $performance['member']->username, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $performance['member']->full_name ?? $performance['member']->username }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($performance['member']->role) }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Tasks</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $performance['total_tasks'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Completed</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ $performance['completed_tasks'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">In Progress</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $performance['in_progress_tasks'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Hours Worked</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($performance['total_hours'], 1) }}h</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
