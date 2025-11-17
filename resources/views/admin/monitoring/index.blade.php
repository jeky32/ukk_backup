@extends('layouts.admin')

@section('title', 'Project Monitoring')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-violet-50/50 to-indigo-50/50 p-6">
    <!-- Header dengan animasi -->
    <div class="mb-6 animate-fade-in-down">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent animate-gradient">
                    Project Monitoring Dashboard
                </h1>
                <p class="text-gray-600 text-sm mt-1 font-medium">Real-time project tracking and team activity insights</p>
            </div>
            <button onclick="location.reload()" class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-violet-600 text-white rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center space-x-2 font-semibold text-sm">
                <i class="fas fa-sync-alt text-sm"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards dengan animasi dan backdrop -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Total Projects -->
        <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg hover:shadow-2xl p-5 border border-indigo-100 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 animate-scale-in relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-400/10 to-indigo-600/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center shadow-md animate-float">
                        <i class="fas fa-folder-open text-white text-lg"></i>
                    </div>
                    <span class="text-3xl font-bold text-indigo-600">
                        {{ $projectStats['total'] ?? 0 }}
                    </span>
                </div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Total Projects</h3>
                <div class="flex items-center text-indigo-500 text-xs font-medium">
                    <i class="fas fa-chart-line mr-1 text-xs"></i>
                    <span>Active projects</span>
                </div>
            </div>
        </div>

        <!-- With Deadline -->
        <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg hover:shadow-2xl p-5 border border-purple-100 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 animate-scale-in relative overflow-hidden group" style="animation-delay: 0.1s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-400/10 to-fuchsia-500/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-fuchsia-500 rounded-lg flex items-center justify-center shadow-md animate-float" style="animation-delay: 0.5s;">
                        <i class="fas fa-calendar-check text-white text-lg"></i>
                    </div>
                    <span class="text-3xl font-bold text-purple-600">
                        {{ $projectStats['with_deadline'] ?? 0 }}
                    </span>
                </div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">With Deadline</h3>
                <div class="flex items-center text-purple-500 text-xs font-medium">
                    <i class="fas fa-clock mr-1 text-xs"></i>
                    <span>Scheduled projects</span>
                </div>
            </div>
        </div>

        <!-- Deadline Soon -->
        <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg hover:shadow-2xl p-5 border border-amber-100 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 animate-scale-in relative overflow-hidden group" style="animation-delay: 0.2s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-400/10 to-orange-500/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-md animate-float" style="animation-delay: 1s;">
                        <i class="fas fa-hourglass-half text-white text-lg"></i>
                    </div>
                    <span class="text-3xl font-bold text-amber-600">
                        {{ $projectStats['deadline_approaching'] ?? 0 }}
                    </span>
                </div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Deadline Soon</h3>
                <div class="flex items-center text-amber-600 text-xs font-medium">
                    <i class="fas fa-fire mr-1 text-xs animate-pulse"></i>
                    <span>Within 7 days</span>
                </div>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg hover:shadow-2xl p-5 border border-rose-100 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 animate-scale-in relative overflow-hidden group" style="animation-delay: 0.3s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-rose-400/10 to-pink-500/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-pink-500 rounded-lg flex items-center justify-center shadow-md animate-float" style="animation-delay: 1.5s;">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                    <span class="text-3xl font-bold text-rose-600">
                        {{ $projectStats['overdue'] ?? 0 }}
                    </span>
                </div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Overdue</h3>
                <div class="flex items-center text-rose-500 text-xs font-medium">
                    <i class="fas fa-bell mr-1 text-xs animate-bounce"></i>
                    <span>Need attention</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg p-5 border border-indigo-100 mb-6 animate-fade-in-up" style="animation-delay: 0.35s;">
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
            <!-- Search Bar -->
            <div class="flex-1 w-full lg:w-auto">
                <div class="relative">
                    <input type="text"
                           id="searchProject"
                           placeholder="Search projects... (Press Ctrl+K)"
                           class="w-full pl-11 pr-4 py-2.5 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-300 text-sm font-medium"
                           onkeyup="filterProjects()">
                    <i class="fas fa-search absolute left-4 top-3.5 text-indigo-400 text-sm"></i>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 items-center">
                <!-- Status Filter -->
                <select id="statusFilter"
                        onchange="filterProjects()"
                        class="px-4 py-2.5 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-300 text-sm font-semibold bg-white">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="due-soon">Due Soon</option>
                    <option value="overdue">Overdue</option>
                </select>

                <!-- Sort -->
                <select id="sortBy"
                        onchange="sortProjects()"
                        class="px-4 py-2.5 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-300 text-sm font-semibold bg-white">
                    <option value="name">Sort by Name</option>
                    <option value="deadline">Sort by Deadline</option>
                    <option value="members">Sort by Members</option>
                </select>

                <!-- Reset Button - WARNA MERAH -->
                <button onclick="resetFilters()"
                        class="px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:shadow-lg hover:from-red-600 hover:to-rose-700 transform hover:scale-105 transition-all duration-300 text-sm font-semibold flex items-center space-x-2">
                    <i class="fas fa-redo text-xs"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>

        <!-- Result Counter -->
        <div class="mt-3 text-sm text-gray-600 font-medium">
            Showing <span id="resultCount" class="font-bold text-indigo-600">{{ count($projects) }}</span> of <span class="font-bold">{{ count($projects) }}</span> projects
        </div>
    </div>

    <!-- All Projects Table -->
    <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg border border-indigo-100 overflow-hidden animate-fade-in-up hover:shadow-xl transition-all duration-300 mb-6" style="animation-delay: 0.4s;">
        <div class="bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500 px-5 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-th-list mr-2.5 text-lg"></i>
                All Projects Overview
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-indigo-50 to-violet-50 border-b-2 border-indigo-200">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Project</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Members</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Deadline</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $project)
                        <tr class="project-row hover:bg-gradient-to-r hover:from-indigo-50 hover:to-violet-50 transition-all duration-300 group">
                            <td class="px-5 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-11 h-11 bg-gradient-to-br from-indigo-400 to-violet-600 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                        <i class="fas fa-folder text-white text-base"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm group-hover:text-indigo-600 transition-colors project-name">
                                            {{ $project->project_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 font-medium mt-0.5">
                                            <i class="fas fa-user-circle mr-1"></i>{{ $project->creator->full_name ?? $project->creator->username ?? 'Unknown' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex -space-x-2 members-container">
                                    @foreach($project->members->take(3) as $member)
                                        <div class="w-9 h-9 bg-gradient-to-br from-violet-400 to-purple-600 rounded-full border-2 border-white flex items-center justify-center shadow-sm hover:scale-125 hover:z-10 transition-all duration-300">
                                            <span class="text-white text-xs font-bold">
                                                {{ strtoupper(substr($member->full_name ?? $member->username ?? 'U', 0, 2)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($project->members->count() > 3)
                                        <div class="w-9 h-9 bg-gradient-to-br from-slate-500 to-gray-600 rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                                            <span class="text-white text-xs font-bold">+{{ $project->members->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 deadline-cell">
                                @if($project->deadline)
                                    @php
                                        $daysUntil = now()->diffInDays($project->deadline, false);
                                    @endphp
                                    <div class="text-sm text-gray-900 font-bold">
                                        {{ date('M d, Y', strtotime($project->deadline)) }}
                                    </div>
                                    @if($daysUntil < 0)
                                        <div class="text-xs text-rose-600 font-semibold flex items-center mt-0.5">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ abs($daysUntil) }} days overdue
                                        </div>
                                    @elseif($daysUntil <= 7)
                                        <div class="text-xs text-amber-600 font-semibold flex items-center mt-0.5">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $daysUntil }} days left
                                        </div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400 italic font-medium">No deadline</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $status = 'Active';
                                    $statusColor = 'emerald';
                                    if ($project->deadline) {
                                        if (now()->isAfter($project->deadline)) {
                                            $status = 'Overdue';
                                            $statusColor = 'rose';
                                        } elseif (now()->diffInDays($project->deadline) <= 7) {
                                            $status = 'Due Soon';
                                            $statusColor = 'amber';
                                        }
                                    }
                                @endphp
                                <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold
                                    {{ $statusColor === 'emerald' ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : '' }}
                                    {{ $statusColor === 'amber' ? 'bg-amber-100 text-amber-700 border border-amber-300' : '' }}
                                    {{ $statusColor === 'rose' ? 'bg-rose-100 text-rose-700 border border-rose-300' : '' }}
                                    shadow-sm" data-status="{{ strtolower($status) }}">
                                    <span class="w-1.5 h-1.5 {{ $statusColor === 'emerald' ? 'bg-emerald-500' : '' }} {{ $statusColor === 'amber' ? 'bg-amber-500 animate-pulse' : '' }} {{ $statusColor === 'rose' ? 'bg-rose-500 animate-pulse' : '' }} rounded-full mr-1.5"></span>
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.monitoring.show', $project->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-violet-600 text-white rounded-lg hover:shadow-lg transform hover:scale-105 hover:-translate-y-0.5 transition-all duration-300 text-xs font-bold">
                                    <i class="fas fa-eye mr-1.5 text-xs"></i>
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-results-row">
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center mb-3 shadow-lg animate-pulse">
                                        <i class="fas fa-folder-open text-gray-500 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 font-bold text-base">No projects found</p>
                                    <p class="text-gray-500 text-sm mt-1">Start by creating your first project</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- User Activity & Member Distribution Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- User Activity -->
        <div class="lg:col-span-1 bg-white/90 backdrop-blur-lg rounded-xl shadow-lg hover:shadow-xl p-5 border border-violet-100 animate-fade-in-left transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">User Activity</h2>
                <div class="w-10 h-10 bg-gradient-to-br from-violet-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
            </div>

            <div class="space-y-3">
                <!-- Working -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg p-4 border border-emerald-200 hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-laptop-code text-white text-base"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Working</p>
                                <p class="text-2xl font-bold text-emerald-600">{{ $workingUsers ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="relative">
                            <span class="absolute w-3 h-3 bg-emerald-400 rounded-full animate-ping"></span>
                            <span class="w-3 h-3 bg-emerald-400 rounded-full block"></span>
                        </div>
                    </div>
                </div>

                <!-- Idle -->
                <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-lg p-4 border border-slate-200 hover:shadow-md transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-slate-400 to-gray-500 rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-mug-hot text-white text-base"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Idle</p>
                                <p class="text-2xl font-bold text-slate-600">{{ $idleUsers ?? 0 }}</p>
                            </div>
                        </div>
                        <span class="w-3 h-3 bg-slate-400 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>

        {{--  <!-- Member Distribution Chart -->
        <div class="lg:col-span-2 bg-white/90 backdrop-blur-lg rounded-xl shadow-lg hover:shadow-xl p-5 border border-indigo-100 animate-fade-in-right transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Member Distribution</h2>
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-violet-500 rounded-lg flex items-center justify-center shadow-sm">
                    <i class="fas fa-chart-pie text-white text-sm"></i>
                </div>
            </div>

            @if(isset($memberDistribution) && count($memberDistribution) > 0)
                <div class="space-y-3">
                    @php
                        $maxMembers = 1;
                        foreach($memberDistribution as $item) {
                            $memberCount = is_array($item) ? ($item['member_count'] ?? 0) : ($item->member_count ?? 0);
                            if ($memberCount > $maxMembers) {
                                $maxMembers = $memberCount;
                            }
                        }
                        $colors = [
                            ['from' => 'from-indigo-400', 'to' => 'to-indigo-600', 'text' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
                            ['from' => 'from-violet-400', 'to' => 'to-violet-600', 'text' => 'text-violet-600', 'bg' => 'bg-violet-50'],
                            ['from' => 'from-purple-400', 'to' => 'to-purple-600', 'text' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                            ['from' => 'from-fuchsia-400', 'to' => 'to-fuchsia-600', 'text' => 'text-fuchsia-600', 'bg' => 'bg-fuchsia-50'],
                            ['from' => 'from-pink-400', 'to' => 'to-pink-600', 'text' => 'text-pink-600', 'bg' => 'bg-pink-50'],
                        ];
                        $colorIndex = 0;
                    @endphp

                    @foreach($memberDistribution as $data)
                        @php
                            $projectName = is_array($data) ? ($data['project_name'] ?? 'Unknown Project') : ($data->project_name ?? 'Unknown Project');
                            $memberCount = is_array($data) ? ($data['member_count'] ?? 0) : ($data->member_count ?? 0);
                            $color = $colors[$colorIndex % count($colors)];
                            $colorIndex++;
                        @endphp

                        <div class="group {{ $color['bg'] }} rounded-lg p-3.5 border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02]">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $color['from'] }} {{ $color['to'] }} rounded-lg flex items-center justify-center shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                        <span class="text-white text-xs font-bold">
                                            {{ strtoupper(substr($projectName, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span class="font-bold {{ $color['text'] }} text-sm">{{ $projectName }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-bold {{ $color['text'] }}">{{ $memberCount }}</span>
                                    <p class="text-xs text-gray-600 font-medium">members</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden shadow-inner">
                                @php
                                    $percentage = $maxMembers > 0 ? ($memberCount / $maxMembers) * 100 : 0;
                                @endphp
                                <div class="h-full bg-gradient-to-r {{ $color['from'] }} {{ $color['to'] }} rounded-full transition-all duration-1000 ease-out progress-bar group-hover:animate-pulse"
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center mb-3 shadow-lg">
                        <i class="fas fa-chart-bar text-gray-500 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 font-semibold text-sm">No member distribution data</p>
                </div>
            @endif
        </div>  --}}
    </div>
</div>

<script>
// Filter Projects Function
function filterProjects() {
    const searchValue = document.getElementById('searchProject').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.project-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const projectName = row.querySelector('.project-name')?.textContent.toLowerCase() || '';
        const statusBadge = row.querySelector('.status-badge')?.getAttribute('data-status') || '';

        let statusMatch = true;
        if (statusFilter !== 'all') {
            if (statusFilter === 'active') {
                statusMatch = statusBadge === 'active';
            } else if (statusFilter === 'due-soon') {
                statusMatch = statusBadge === 'due soon';
            } else if (statusFilter === 'overdue') {
                statusMatch = statusBadge === 'overdue';
            }
        }

        const searchMatch = projectName.includes(searchValue);

        if (searchMatch && statusMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('resultCount').textContent = visibleCount;
}

// Sort Projects Function
function sortProjects() {
    const sortBy = document.getElementById('sortBy').value;
    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('.project-row'));

    rows.sort((a, b) => {
        if (sortBy === 'name') {
            const nameA = a.querySelector('.project-name')?.textContent || '';
            const nameB = b.querySelector('.project-name')?.textContent || '';
            return nameA.localeCompare(nameB);
        } else if (sortBy === 'deadline') {
            const dateA = a.querySelector('.deadline-cell .text-sm')?.textContent || 'ZZZ';
            const dateB = b.querySelector('.deadline-cell .text-sm')?.textContent || 'ZZZ';
            return dateA.localeCompare(dateB);
        } else if (sortBy === 'members') {
            const membersA = a.querySelectorAll('.members-container > div').length;
            const membersB = b.querySelectorAll('.members-container > div').length;
            return membersB - membersA;
        }
        return 0;
    });

    rows.forEach(row => tbody.appendChild(row));
    filterProjects();
}

// Reset Filters Function
function resetFilters() {
    document.getElementById('searchProject').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('sortBy').value = 'name';
    filterProjects();
}

// Keyboard Shortcut
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('searchProject').focus();
        }
    });
});
</script>

<style>
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.5s ease-out;
    }

    @keyframes fade-in-left {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-left {
        animation: fade-in-left 0.5s ease-out;
    }

    @keyframes fade-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-right {
        animation: fade-in-right 0.5s ease-out;
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out backwards;
    }

    @keyframes scale-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-scale-in {
        animation: scale-in 0.4s ease-out backwards;
    }

    @keyframes progress-load {
        0% { width: 0%; }
    }

    .progress-bar {
        animation: progress-load 1.2s ease-out;
    }
</style>
@endsection
