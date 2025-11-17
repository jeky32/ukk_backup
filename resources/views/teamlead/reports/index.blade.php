<!-- resources/views/teamlead/reports/index.blade.php -->
@extends('layouts.teamlead')

@section('title', 'Export Reports')
@section('page-title', 'Export Reports')
@section('page-subtitle', 'Generate dan download laporan project dalam format PDF')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Project Report -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-folder-open text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Project Report</h3>
            <p class="text-sm text-gray-600 mb-4">Laporan lengkap per project termasuk tasks, progress, dan tim</p>
            <button onclick="openModal('projectModal')" 
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-file-pdf mr-2"></i>Generate Report
            </button>
        </div>

        <!-- Team Performance Report -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-users text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Team Performance</h3>
            <p class="text-sm text-gray-600 mb-4">Laporan performa anggota tim dengan statistik lengkap</p>
            <button onclick="openModal('teamModal')" 
                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                <i class="fas fa-file-pdf mr-2"></i>Generate Report
            </button>
        </div>

        <!-- Comprehensive Report -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Comprehensive Report</h3>
            <p class="text-sm text-gray-600 mb-4">Laporan lengkap semua project dan performa tim</p>
            <button onclick="openModal('comprehensiveModal')" 
                    class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                <i class="fas fa-file-pdf mr-2"></i>Generate Report
            </button>
        </div>
    </div>

    <!-- Report Preview Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-start space-x-4">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-white"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 mb-2">Informasi Laporan</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Laporan akan di-generate dalam format PDF</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Anda dapat filter berdasarkan tanggal</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Laporan mencakup statistik lengkap dan visualisasi data</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i>File akan otomatis terdownload setelah generate</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Project Report Modal -->
<div id="projectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Generate Project Report</h3>
            <button onclick="closeModal('projectModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('teamlead.reports.export-project') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Project *</label>
                <select name="project_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('projectModal')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Team Report Modal -->
<div id="teamModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Generate Team Report</h3>
            <button onclick="closeModal('teamModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('teamlead.reports.export-team') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Project (Optional)</label>
                <select name="project_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">-- All Projects --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('teamModal')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Comprehensive Report Modal -->
<div id="comprehensiveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Generate Comprehensive Report</h3>
            <button onclick="closeModal('comprehensiveModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('teamlead.reports.export-comprehensive') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <p class="text-sm text-purple-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    This report includes all projects and team performance data
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('comprehensiveModal')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['projectModal', 'teamModal', 'comprehensiveModal'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });
    }
});
</script>
@endpush