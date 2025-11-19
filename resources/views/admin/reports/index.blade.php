@extends('layouts.admin')
@section('title', 'Reports')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Project Reports</h1>
                <p class="text-gray-600 text-sm">Overview of all project progress and statistics</p>
            </div>
            <a href="{{ route('admin.reports.exportPdf') }}"
                class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-red-500/30 flex items-center gap-3 border border-red-700/20">
                <i class="fas fa-file-pdf text-lg"></i> 
                <span>Download PDF</span>
            </a>
        </div>

        <!-- Stats Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalProjects = $reports->count();
                $totalTasks = $reports->sum('task_total');
                $completedTasks = $reports->sum('task_completed');
                $blockedTasks = $reports->sum('task_blocked');
            @endphp
            
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-1">Total Projects</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $totalProjects }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i class="fas fa-folder-open text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-1">Total Tasks</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $totalTasks }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-1">Completed</p>
                        <h3 class="text-3xl font-bold text-green-600">{{ $completedTasks }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/30">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-1">Blocked</p>
                        <h3 class="text-3xl font-bold text-red-600">{{ $blockedTasks }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <i class="fas fa-exclamation-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-200">
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-left uppercase">
                            <i class="fas fa-project-diagram mr-2 text-slate-500"></i>Project
                        </th>
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-left uppercase">
                            <i class="fas fa-user mr-2 text-slate-500"></i>Owner
                        </th>
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-center uppercase">
                            <i class="fas fa-list mr-2 text-slate-500"></i>Total Tasks
                        </th>
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-center uppercase">
                            <i class="fas fa-check mr-2 text-slate-500"></i>Completed
                        </th>
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-center uppercase">
                            <i class="fas fa-ban mr-2 text-slate-500"></i>Blocked
                        </th>
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-center uppercase">
                            <i class="fas fa-chart-line mr-2 text-slate-500"></i>Progress
                        </th>
                        <th class="px-7 py-5 text-slate-700 text-xs font-bold tracking-widest text-center uppercase">
                            <i class="fas fa-info-circle mr-2 text-slate-500"></i>Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reports as $proj)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/30">
                        <td class="px-7 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($proj->project_name, 0, 2)) }}</span>
                                </div>
                                <span class="text-gray-900 font-bold text-sm">{{ $proj->project_name }}</span>
                            </div>
                        </td>
                        <td class="px-7 py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-xs"></i>
                                </div>
                                <span class="text-gray-800 font-medium text-sm">
                                    {{ $proj->creator->full_name ?? $proj->creator->username ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-7 py-5 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-slate-100 rounded-xl font-bold text-slate-700">
                                {{ $proj->task_total }}
                            </span>
                        </td>
                        <td class="px-7 py-5 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl font-bold text-green-700">
                                {{ $proj->task_completed }}
                            </span>
                        </td>
                        <td class="px-7 py-5 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-xl font-bold text-red-700">
                                {{ $proj->task_blocked }}
                            </span>
                        </td>
                        <td class="px-7 py-5">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-full max-w-[120px] bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full shadow-sm" 
                                         style="width: {{ $proj->progress_percent }}%"></div>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 font-bold text-xs shadow-sm">
                                    {{ $proj->progress_percent }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-7 py-5 text-center">
                            @php
                                $statusLabel = 'Ongoing'; 
                                $badge = 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/40';
                                $icon = 'fa-spinner';
                                
                                if ($proj->is_overdue) { 
                                    $statusLabel = 'Overdue'; 
                                    $badge = 'bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/40';
                                    $icon = 'fa-exclamation-triangle';
                                }
                                elseif ($proj->progress_percent == 100) { 
                                    $statusLabel = 'Done'; 
                                    $badge = 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/40';
                                    $icon = 'fa-check-double';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $badge }} font-bold text-xs uppercase tracking-wide">
                                <i class="fas {{ $icon }}"></i>
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mb-4 shadow-inner">
                                    <i class="fas fa-inbox text-gray-400 text-4xl"></i>
                                </div>
                                <p class="text-gray-500 font-semibold text-lg mb-1">No Report Data Available</p>
                                <p class="text-gray-400 text-sm">Start creating projects to see reports here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="mt-6 text-center">
        <p class="text-gray-500 text-sm">
            <i class="fas fa-info-circle mr-1"></i>
            Generated on {{ now()->format('d M Y, H:i') }}
        </p>
    </div>
</div>
@endsection