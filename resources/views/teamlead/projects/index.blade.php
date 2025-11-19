@extends('layouts.teamlead')

@section('title', 'My Projects')
@section('page-title', 'My Projects')
@section('page-subtitle', 'Manage and monitor all your projects')

@push('styles')
<style>
    .project-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .project-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .project-card.blue-card:hover { border-color: #3b82f6; }
    .project-card.green-card:hover { border-color: #10b981; }
    .project-card.orange-card:hover { border-color: #f97316; }
    .project-card.teal-card:hover { border-color: #14b8a6; }
    .project-card.pink-card:hover { border-color: #ec4899; }
    .project-card.indigo-card:hover { border-color: #6366f1; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Header Section dengan Warna -->
    <div class="bg-blue-500 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">My Projects</h1>
                    <p class="text-blue-100 mt-1">{{ $projects->total() }} total projects</p>
                </div>
            </div>
            <a href="{{ route('teamlead.dashboard') }}"
               class="px-5 py-2.5 bg-white hover:bg-blue-50 text-blue-600 rounded-lg font-semibold transition-colors flex items-center text-sm shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search & Filter dengan Warna -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text"
                       placeholder="Search projects..."
                       class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm"
                       id="searchProjects">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <select class="px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm font-semibold text-gray-700">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Projects Grid dengan Warna Berbeda -->
    @if($projects->count() > 0)
        <div class="project-grid">
            @foreach($projects as $project)
                @php
                    $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                    $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                    $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                    
                    // Dynamic color theming
                    $colorThemes = [
                        'blue' => ['header' => 'bg-blue-500', 'badge' => 'bg-blue-100 text-blue-700', 'progress' => 'bg-blue-500', 'btn' => 'bg-blue-500 hover:bg-blue-600', 'class' => 'blue-card'],
                        'green' => ['header' => 'bg-green-500', 'badge' => 'bg-green-100 text-green-700', 'progress' => 'bg-green-500', 'btn' => 'bg-green-500 hover:bg-green-600', 'class' => 'green-card'],
                        'orange' => ['header' => 'bg-orange-500', 'badge' => 'bg-orange-100 text-orange-700', 'progress' => 'bg-orange-500', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'class' => 'orange-card'],
                        'teal' => ['header' => 'bg-teal-500', 'badge' => 'bg-teal-100 text-teal-700', 'progress' => 'bg-teal-500', 'btn' => 'bg-teal-500 hover:bg-teal-600', 'class' => 'teal-card'],
                        'pink' => ['header' => 'bg-pink-500', 'badge' => 'bg-pink-100 text-pink-700', 'progress' => 'bg-pink-500', 'btn' => 'bg-pink-500 hover:bg-pink-600', 'class' => 'pink-card'],
                        'indigo' => ['header' => 'bg-indigo-500', 'badge' => 'bg-indigo-100 text-indigo-700', 'progress' => 'bg-indigo-500', 'btn' => 'bg-indigo-500 hover:bg-indigo-600', 'class' => 'indigo-card'],
                    ];
                    
                    // Assign color based on index (cycling through colors)
                    $colorKeys = array_keys($colorThemes);
                    $colorKey = $colorKeys[$loop->index % count($colorKeys)];
                    $theme = $colorThemes[$colorKey];
                    
                    $deadline = $project->deadline ? \Carbon\Carbon::parse($project->deadline) : null;
                    $isOverdue = $deadline && $deadline->isPast();
                @endphp

                <div class="project-card {{ $theme['class'] }}">
                    <!-- Card Header Berwarna -->
                    <div class="{{ $theme['header'] }} p-5 text-white">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-11 h-11 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-lg">
                                <i class="fas fa-folder-open text-white text-lg"></i>
                            </div>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">
                                {{ strtoupper($project->status ?? 'active') }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-2 line-clamp-1">
                            {{ $project->project_name }}
                        </h3>
                        <p class="text-sm text-white/90 line-clamp-2 leading-relaxed">
                            {{ $project->description ?? 'No description available' }}
                        </p>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 bg-white space-y-4">
                        <!-- Progress -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-700">Progress</span>
                                <span class="text-lg font-bold" style="color: {{ $theme['header'] === 'bg-blue-500' ? '#3b82f6' : ($theme['header'] === 'bg-green-500' ? '#10b981' : ($theme['header'] === 'bg-orange-500' ? '#f97316' : ($theme['header'] === 'bg-teal-500' ? '#14b8a6' : ($theme['header'] === 'bg-pink-500' ? '#ec4899' : '#6366f1')))) }}">{{ $progress }}%</span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="{{ $theme['progress'] }} h-full rounded-full transition-all duration-300" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Stats dengan Background Berwarna -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <i class="fas fa-tasks text-blue-500 text-base mb-1"></i>
                                <p class="text-xl font-bold text-gray-900">{{ $totalCards }}</p>
                                <p class="text-xs text-gray-600 font-medium">Tasks</p>
                            </div>
                            <div class="text-center p-3 bg-teal-50 border border-teal-200 rounded-lg">
                                <i class="fas fa-columns text-teal-500 text-base mb-1"></i>
                                <p class="text-xl font-bold text-gray-900">{{ $project->boards->count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Boards</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                <i class="fas fa-users text-green-500 text-base mb-1"></i>
                                <p class="text-xl font-bold text-gray-900">{{ $project->members->count() }}</p>
                                <p class="text-xs text-gray-600 font-medium">Team</p>
                            </div>
                        </div>

                        <!-- Deadline -->
                        @if($deadline)
                        <div class="flex items-center justify-between p-3 {{ $isOverdue ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                            <span class="text-xs font-semibold {{ $isOverdue ? 'text-red-700' : 'text-gray-700' }} flex items-center">
                                <i class="far fa-calendar mr-2"></i>
                                Deadline
                            </span>
                            <span class="text-sm font-bold {{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $deadline->format('d M Y') }}
                            </span>
                        </div>
                        @endif

                        <!-- Action Button Berwarna -->
                        <a href="{{ route('teamlead.projects.show', $project->id) }}"
                        class="block w-full text-center px-4 py-2.5 {{ $theme['btn'] }} text-white rounded-lg font-semibold transition-colors text-sm shadow-md">
                            <i class="fas fa-th mr-2"></i>  <!-- ✅ Ganti icon juga -->
                            View Cards  <!-- ✅ BARU -->
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
        @endif
    @else
        <!-- Empty State Berwarna -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-12 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-blue-400 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Projects Yet</h3>
            <p class="text-gray-600 mb-6">You haven't been assigned to lead any projects yet.</p>
            <a href="{{ route('teamlead.dashboard') }}"
               class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition-colors shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProjects');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.project-card');

            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const desc = card.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || desc.includes(searchTerm)) {
                    card.parentElement.style.display = '';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
