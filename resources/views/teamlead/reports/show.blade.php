@extends('layouts.teamlead')

@section('title', 'Project Report - ' . $project->project_name)
@section('page-title', 'PROJECT REPORT')
@section('page-subtitle', $project->project_name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Task Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-clipboard-list text-4xl opacity-80"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $stats['todo'] }}</h3>
                <p class="text-sm opacity-90 font-medium">To Do</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-spinner text-4xl opacity-80"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $stats['in_progress'] }}</h3>
                <p class="text-sm opacity-90 font-medium">In Progress</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-eye text-4xl opacity-80"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $stats['review'] }}</h3>
                <p class="text-sm opacity-90 font-medium">Review</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-check-double text-4xl opacity-80"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $stats['done'] }}</h3>
                <p class="text-sm opacity-90 font-medium">Done</p>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Team Performance
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Total Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">In Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Hours Worked</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($teamPerformance as $performance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            {{ strtoupper(substr($performance['member']->full_name ?? $performance['member']->username, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $performance['member']->full_name ?? $performance['member']->username }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($performance['member']->role) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $performance['total_tasks'] }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full font-semibold">
                                        {{ $performance['completed_tasks'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-full font-semibold">
                                        {{ $performance['in_progress_tasks'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($performance['total_hours'], 1) }}h
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No team members found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
