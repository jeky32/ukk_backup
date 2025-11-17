<!-- resources/views/admin/projects/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-purple-950">
    <!-- Projects Sidebar -->
    @include('components.admin-sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Header -->
        <header class="bg-gray-900/40 backdrop-blur-xl shadow-xl border-b border-indigo-400/20 sticky top-0 z-40">
            <div class="flex items-center justify-between px-8 py-5">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">
                        <i class="fas fa-layer-group mr-3"></i>All Projects
                    </h1>
                    <p class="text-xs text-gray-400 mt-1">Manage and view all your projects</p>
                </div>
                <a href="{{ route('admin.projects.create') }}"
                   class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>Create New Project</span>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-auto p-8 space-y-12">
            <!-- ===== MY PROJECTS SECTION ===== -->
            <section class="animate-slide-up">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-3 animate-bounce-subtle"></i>
                            My Projects
                        </h2>
                        <p class="text-sm text-gray-400 mt-1">Project yang Anda buat</p>
                    </div>
                </div>

                <!-- My Projects Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($myProjects as $project)
                        <a href="{{ route('admin.projects.show', $project->id) }}"
                           class="group bg-gradient-to-br from-blue-500/10 to-purple-600/10 backdrop-blur-xl rounded-xl p-6 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl">

                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3 flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-folder text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-white truncate group-hover:text-cyan-300 transition-colors">{{ $project->project_name }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $project->creator->full_name }}</p>
                                    </div>
                                </div>
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-500/30 text-green-700',
                                        'planning' => 'bg-blue-500/30 text-blue-700',
                                        'completed' => 'bg-purple-500/30 text-purple-700',
                                        'on_hold' => 'bg-yellow-500/30 text-yellow-700',
                                    ];
                                    $status = $project->status ?? 'active';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$status] ?? 'bg-gray-500/30 text-gray-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </div>

                            <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $project->description ?? 'No description' }}</p>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4 pb-4 border-b border-gray-700/50">
                                <span><i class="fas fa-th mr-1"></i>{{ $project->boards->count() }} Boards</span>
                                @if($project->deadline)
                                    <span><i class="far fa-clock mr-1"></i>{{ date('M d, Y', strtotime($project->deadline)) }}</span>
                                @else
                                    <span><i class="far fa-clock mr-1 text-gray-600"></i>No deadline</span>
                                @endif
                            </div>

                            <div class="flex space-x-2 gap-2">
                                <button class="flex-1 px-3 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg text-sm transition-all duration-300 font-medium group-hover:scale-105">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                                <button class="flex-1 px-3 py-2 bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-400 rounded-lg text-sm transition-all duration-300 font-medium group-hover:scale-105">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full bg-gradient-to-br from-white/10 to-white/5 rounded-xl p-12 text-center border border-gray-700/50 border-dashed">
                            <i class="fas fa-inbox text-4xl text-gray-500 mb-4"></i>
                            <p class="text-gray-500 mb-4">Belum ada project yang dibuat</p>
                            <a href="{{ route('admin.projects.create') }}"
                               class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                <i class="fas fa-plus"></i>
                                <span>Buat Project Pertama</span>
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Divider -->
            <div class="border-t border-gray-700/50"></div>

            <!-- ===== ALL PROJECTS SECTION ===== -->
            <section class="animate-slide-up" style="animation-delay: 0.2s;">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-layer-group text-indigo-400 mr-3"></i>
                        All Projects
                    </h2>

                    <!-- All Projects Table -->
                    <div class="bg-white/5 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-gray-700/50">
                        <table class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-900/50 border-b border-gray-700/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Project Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Created By</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Boards</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Deadline</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/30">
                                @forelse($allProjects as $project)
                                    <tr class="hover:bg-gray-800/30 transition-all duration-300 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-folder text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-200 group-hover:text-cyan-300 transition-colors">
                                                        {{ $project->project_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ Str::limit($project->description, 40) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($project->creator->full_name ?? 'U', 0, 1)) }}</span>
                                                </div>
                                                <span class="text-sm text-gray-300">
                                                    {{ $project->creator->full_name ?? $project->creator->username }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'active' => 'bg-green-500/30 text-green-300 border-green-500/50',
                                                    'planning' => 'bg-blue-500/30 text-blue-300 border-blue-500/50',
                                                    'completed' => 'bg-purple-500/30 text-purple-300 border-purple-500/50',
                                                    'on_hold' => 'bg-yellow-500/30 text-yellow-300 border-yellow-500/50',
                                                ];
                                                $status = $project->status ?? 'active';
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $statusColors[$status] ?? 'bg-gray-500/30 text-gray-300 border-gray-500/50' }}">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-th text-indigo-400"></i>
                                                <span class="text-sm text-gray-300 font-medium">{{ $project->boards->count() }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                            @if($project->deadline)
                                                <div class="flex items-center space-x-1">
                                                    <i class="far fa-clock"></i>
                                                    <span>{{ date('M d, Y', strtotime($project->deadline)) }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-600">No deadline</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3 gap-2">
                                                <a href="{{ route('admin.projects.show', $project->id) }}"
                                                   class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/20 rounded-lg transition-all group/btn"
                                                   title="View Project">
                                                    <i class="fas fa-eye group-hover/btn:scale-110 transition-transform"></i>
                                                </a>
                                                <a href="{{ route('admin.projects.edit', $project->id) }}"
                                                   class="p-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-500/20 rounded-lg transition-all group/btn"
                                                   title="Edit Project">
                                                    <i class="fas fa-edit group-hover/btn:scale-110 transition-transform"></i>
                                                </a>
                                                <form action="{{ route('admin.projects.destroy', $project->id) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/20 rounded-lg transition-all group/btn"
                                                            title="Delete Project">
                                                        <i class="fas fa-trash group-hover/btn:scale-110 transition-transform"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-folder-open text-gray-600 text-5xl mb-4"></i>
                                                <h3 class="text-lg font-semibold text-gray-400 mb-2">No projects found</h3>
                                                <p class="text-sm text-gray-500 mb-4">Get started by creating a new project</p>
                                                <a href="{{ route('admin.projects.create') }}"
                                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                                    Create Your First Project
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($allProjects->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $allProjects->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </div>
</div>

<style>
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-bounce-subtle {
        animation: bounce-subtle 2s ease-in-out infinite;
    }

    @keyframes bounce-subtle {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }
</style>
@endsection
