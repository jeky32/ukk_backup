<!-- resources/views/designer/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Designer Dashboard')
@section('page-title', 'DASHBOARD DESIGNER - Halo, ' . Auth::user()->full_name . '!')
@section('page-subtitle', 'Kelola desain dan waktu kerja Anda')

@push('styles')
<style>
    .working-indicator {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
    .timer-display {
        font-variant-numeric: tabular-nums;
    }
    .design-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .design-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="designTimer()">
    <!-- Current Status Banner -->
    @if(Auth::user()->current_task_status === 'working')
    <div class="bg-gradient-to-r from-pink-400 to-purple-500 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="working-indicator">
                    <i class="fas fa-circle text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">You're Currently Designing</h3>
                    <p class="text-sm text-white/90">{{ $activeTimeLog ? $activeTimeLog->card->card_title : 'Design in progress' }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold timer-display" x-text="elapsedTime"></p>
                <p class="text-sm text-white/90">Time Elapsed</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-purple-400 to-indigo-500 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center space-x-4">
            <i class="fas fa-palette text-white text-3xl"></i>
            <div>
                <h3 class="text-xl font-bold">Ready to Create</h3>
                <p class="text-sm text-white/90">Select a design task below to start working</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex items-center space-x-1 border-b border-gray-200 bg-white rounded-t-xl px-6 pt-4">
        <button @click="activeTab = 'current'"
                :class="activeTab === 'current' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-3 border-b-2 font-medium text-sm transition">
            <i class="fas fa-paintbrush mr-2"></i>Tugas Desain
        </button>
        <button @click="activeTab = 'projects'"
                :class="activeTab === 'projects' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-3 border-b-2 font-medium text-sm transition">
            <i class="fas fa-folder mr-2"></i>Proyek
        </button>
        <button @click="activeTab = 'portfolio'"
                :class="activeTab === 'portfolio' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-3 border-b-2 font-medium text-sm transition">
            <i class="fas fa-images mr-2"></i>Portfolio
        </button>
        <button @click="activeTab = 'timetrack'"
                :class="activeTab === 'timetrack' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-3 border-b-2 font-medium text-sm transition">
            <i class="fas fa-clock mr-2"></i>Time Track
        </button>
        <button @click="activeTab = 'achievements'"
                :class="activeTab === 'achievements' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-3 border-b-2 font-medium text-sm transition">
            <i class="fas fa-trophy mr-2"></i>Achievements
        </button>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-b-xl shadow-sm">
        <!-- TUGAS DESAIN SAAT INI -->
        <div x-show="activeTab === 'current'" class="p-6">
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-paintbrush text-purple-600 text-xl mr-3"></i>
                    <h2 class="text-lg font-bold text-gray-800">TUGAS DESAIN SAAT INI</h2>
                </div>

                @if($currentTask)
                <div class="border border-gray-200 rounded-xl p-6 bg-gradient-to-br from-purple-50 to-pink-50">
                    <!-- Task Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-palette text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">{{ $currentTask->card->card_title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $currentTask->card->board->project->project_name }}
                                </p>
                            </div>
                        </div>

                        @php
                            $priorityColors = [
                                'high' => 'bg-red-100 text-red-700',
                                'medium' => 'bg-yellow-100 text-yellow-700',
                                'low' => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $priorityColors[$currentTask->card->priority] }}">
                            Priority: {{ ucfirst($currentTask->card->priority) }}
                        </span>
                    </div>

                    <!-- Task Meta -->
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Estimasi</p>
                            <p class="text-lg font-bold text-gray-800">{{ $currentTask->card->estimated_hours ?? 0 }} jam</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Telah bekerja</p>
                            <p class="text-lg font-bold text-purple-600">{{ number_format($currentTask->card->actual_hours, 1) }} jam</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Progress</p>
                            <p class="text-lg font-bold text-pink-600">{{ $currentTask->card->subtasks_progress }}%</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Deadline</p>
                            <p class="text-lg font-bold {{ $currentTask->card->is_overdue ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $currentTask->card->due_date ? $currentTask->card->due_date->format('d M') : 'No deadline' }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">Progress</span>
                            <span class="text-xs font-bold text-gray-800">{{ $currentTask->card->subtasks_progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-3 rounded-full transition-all"
                                 style="width: {{ $currentTask->card->subtasks_progress }}%"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        @if(Auth::user()->current_task_status === 'idle')
                            <form action="{{ route('designer.task.start', $currentTask->card) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:opacity-90 transition shadow-lg">
                                    <i class="fas fa-play mr-2"></i>Start Design
                                </button>
                            </form>
                        @else
                            <form action="{{ route('designer.task.pause') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full px-6 py-3 bg-yellow-600 text-white font-bold rounded-lg hover:bg-yellow-700 transition shadow-lg">
                                    <i class="fas fa-pause mr-2"></i>Pause
                                </button>
                            </form>

                            <form action="{{ route('designer.task.complete', $currentTask->card) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Mark this design as complete?')"
                                        class="w-full px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-lg">
                                    <i class="fas fa-check mr-2"></i>Complete
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.cards.show', $currentTask->card) }}"
                           class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-comment mr-2"></i>Comment
                        </a>
                    </div>
                </div>
                @else
                <div class="text-center py-12 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                    <i class="fas fa-palette text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Active Design Task</h3>
                    <p class="text-sm text-gray-500">Select a design task from the list below to start creating</p>
                </div>
                @endif
            </div>

            <!-- DAFTAR TUGAS DESAIN -->
            <div class="mt-8">
                <div class="flex items-center mb-4">
                    <i class="fas fa-list text-pink-600 text-xl mr-3"></i>
                    <h2 class="text-lg font-bold text-gray-800">DAFTAR TUGAS DESAIN</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($myTasks as $assignment)
                    <div class="design-card border border-gray-200 rounded-xl p-4 hover:border-purple-300 transition cursor-pointer"
                         onclick="window.location='{{ route('admin.cards.show', $assignment->card) }}'">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                {{ $assignment->assignment_status === 'assigned' ? 'bg-gray-100 text-gray-700' :
                                   ($assignment->assignment_status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                <i class="fas fa-{{ $assignment->assignment_status === 'assigned' ? 'circle' : ($assignment->assignment_status === 'in_progress' ? 'spinner' : 'check-circle') }} mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $assignment->assignment_status)) }}
                            </span>
                            @if($assignment->card->priority === 'high')
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded">High</span>
                            @endif
                        </div>

                        <h3 class="font-semibold text-gray-900 mb-2">{{ $assignment->card->card_title }}</h3>
                        <p class="text-xs text-gray-600 mb-3">{{ $assignment->card->board->project->project_name }}</p>

                        @if($assignment->card->due_date)
                        <div class="flex items-center text-xs {{ $assignment->card->is_overdue ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                            <i class="far fa-clock mr-1"></i>
                            {{ $assignment->card->due_date->format('d M Y') }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12 bg-gray-50 rounded-xl">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-sm text-gray-500">No design tasks assigned yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- PROYEK TAB -->
        <div x-show="activeTab === 'projects'" class="p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">My Design Projects</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myProjects as $project)
                <a href="{{ route('admin.projects.showproject', $project) }}"
                   class="design-card block bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $project->project_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $project->boards->count() }} Boards</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($project->description, 80) }}</p>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-bold text-purple-600">{{ $project->progress }}%</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- PORTFOLIO TAB -->
        <div x-show="activeTab === 'portfolio'" class="p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">My Design Portfolio</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($completedDesigns as $design)
                <div class="design-card bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="aspect-video bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $design->card_title }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $design->board->project->project_name }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span><i class="far fa-calendar mr-1"></i>{{ $design->updated_at->format('M d, Y') }}</span>
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded font-medium">Completed</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- TIME TRACK TAB -->
        <div x-show="activeTab === 'timetrack'" class="p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Time Tracking History</h2>

            <div class="space-y-4">
                @forelse($myTimeLogs->take(10) as $log)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $log->card->card_title }}</h4>
                            <p class="text-sm text-gray-600">{{ $log->card->board->project->project_name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $log->start_time->format('d M Y, H:i') }} -
                                {{ $log->end_time ? $log->end_time->format('H:i') : 'In Progress' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-purple-600">{{ $log->formatted_duration }}</p>
                            <p class="text-xs text-gray-500">Duration</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No time logs yet</p>
                @endforelse
            </div>
        </div>

        <!-- ACHIEVEMENTS TAB -->
        <div x-show="activeTab === 'achievements'" class="p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">My Achievements</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-purple-400 to-pink-500 text-white rounded-xl p-6 text-center">
                    <i class="fas fa-check-circle text-5xl mb-4"></i>
                    <h3 class="text-3xl font-bold">{{ $completedTasks }}</h3>
                    <p class="text-sm">Designs Completed</p>
                </div>

                <div class="bg-gradient-to-br from-indigo-400 to-purple-500 text-white rounded-xl p-6 text-center">
                    <i class="fas fa-clock text-5xl mb-4"></i>
                    <h3 class="text-3xl font-bold">{{ number_format($totalHoursWorked, 1) }}h</h3>
                    <p class="text-sm">Design Hours</p>
                </div>

                <div class="bg-gradient-to-br from-pink-400 to-rose-500 text-white rounded-xl p-6 text-center">
                    <i class="fas fa-project-diagram text-5xl mb-4"></i>
                    <h3 class="text-3xl font-bold">{{ $myProjects->count() }}</h3>
                    <p class="text-sm">Active Projects</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function designTimer() {
    return {
        activeTab: 'current',
        startTime: {{ $activeTimeLog ? "new Date('" . $activeTimeLog->start_time . "').getTime()" : 'null' }},
        elapsedTime: '00:00:00',

        init() {
            if (this.startTime) {
                this.updateTimer();
                setInterval(() => this.updateTimer(), 1000);
            }
        },

        updateTimer() {
            if (!this.startTime) return;

            const now = new Date().getTime();
            const diff = now - this.startTime;

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            this.elapsedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
    }
}
</script>
@endpush
