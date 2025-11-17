@extends('layouts.teamlead')

@section('title', 'Team Report')
@section('page-title', 'TEAM REPORT')
@section('page-subtitle', 'Overall team performance and statistics')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Team Members Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    Team Members Performance
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Individual performance metrics for all team members
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Member
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Total Tasks
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Completed
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Total Hours
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                This Week
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Completion Rate
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($teamMembers as $performance)
                            @php
                                $completionRate = $performance['total_tasks'] > 0 
                                    ? round(($performance['completed_tasks'] / $performance['total_tasks']) * 100) 
                                    : 0;
                                
                                if ($completionRate >= 80) {
                                    $rateColor = 'text-green-600 dark:text-green-400';
                                    $rateBg = 'bg-green-100 dark:bg-green-900/30';
                                } elseif ($completionRate >= 50) {
                                    $rateColor = 'text-blue-600 dark:text-blue-400';
                                    $rateBg = 'bg-blue-100 dark:bg-blue-900/30';
                                } else {
                                    $rateColor = 'text-orange-600 dark:text-orange-400';
                                    $rateBg = 'bg-orange-100 dark:bg-orange-900/30';
                                }
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-12 h-12">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                                {{ strtoupper(substr($performance['member']->full_name ?? $performance['member']->username, 0, 2)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $performance['member']->full_name ?? $performance['member']->username }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $performance['member']->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $performance['total_tasks'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">tasks</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        {{ $performance['completed_tasks'] }} completed
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ number_format($performance['total_hours'], 1) }}h
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">all time</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($performance['this_week_hours'], 1) }}h
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">this week</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-semibold {{ $rateColor }}">{{ $completionRate }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="{{ $rateBg }} h-2 rounded-full transition-all duration-500" 
                                                     style="width: {{ $completionRate }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-users text-gray-400 dark:text-gray-500 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold">No team members found</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Team members will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Statistics -->
        @if($teamMembers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-users text-4xl opacity-80"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-2">{{ $teamMembers->count() }}</h3>
                    <p class="text-sm opacity-90 font-medium">Team Members</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-tasks text-4xl opacity-80"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-2">{{ $teamMembers->sum('total_tasks') }}</h3>
                    <p class="text-sm opacity-90 font-medium">Total Tasks</p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-check-circle text-4xl opacity-80"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-2">{{ $teamMembers->sum('completed_tasks') }}</h3>
                    <p class="text-sm opacity-90 font-medium">Completed Tasks</p>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-clock text-4xl opacity-80"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($teamMembers->sum('total_hours'), 0) }}h</h3>
                    <p class="text-sm opacity-90 font-medium">Total Hours</p>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
