@extends('layouts.teamlead')

@section('title', 'Create Card')

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('teamlead.projects.show', $project->id) }}"
               class="text-indigo-600 hover:text-indigo-800 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Project
            </a>
        </div>

        <!-- ✅✅ DEBUG INFO (HAPUS SETELAH FIX) -->
        <div class="bg-yellow-100 border-2 border-yellow-400 rounded-xl p-4 mb-6">
            <h3 class="font-bold text-lg text-yellow-800 mb-2">🔍 Debug Info</h3>
            <div class="text-sm space-y-1">
                <p><strong>Project ID:</strong> {{ $project->id }}</p>
                <p><strong>Project Name:</strong> {{ $project->project_name }}</p>
                @if(isset($board))
                    <p><strong>Board ID:</strong> {{ $board->id }}</p>
                    <p><strong>Board Name:</strong> {{ $board->board_name }}</p>
                @else
                    <p class="text-orange-600"><strong>⚠️ No Board (Auto-create mode)</strong></p>
                @endif
                <hr class="my-2">
                @if(isset($developers))
                    <p class="text-green-700"><strong>✅ $developers EXISTS</strong></p>
                    <p><strong>Count:</strong> {{ $developers->count() }}</p>
                    @if($developers->count() > 0)
                        <p><strong>Developers:</strong></p>
                        <ul class="list-disc ml-6 text-xs">
                            @foreach($developers as $dev)
                                <li>ID: {{ $dev->id }} - {{ $dev->username }} ({{ $dev->role }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-red-600"><strong>❌ NO DEVELOPERS IN PROJECT!</strong></p>
                    @endif
                @else
                    <p class="text-red-600 font-bold"><strong>❌ $developers NOT PASSED FROM CONTROLLER!</strong></p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-indigo-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Create New Card</h2>
            <p class="text-gray-600 mb-6">
                Project: <span class="font-semibold">{{ $project->project_name }}</span>
                @if(isset($board))
                    • Board: <span class="font-semibold">{{ $board->board_name }}</span>
                @endif
            </p>

            <form action="{{ isset($board) ? route('teamlead.cards.store', $board->id) : route('teamlead.cards.store', ['board' => 'auto']) }}" method="POST">
                @csrf

                <!-- ✅ Hidden project_id -->
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <strong>Whoops! There were some problems with your input:</strong>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Card Title -->
                    <div class="md:col-span-2">
                        <label for="card_title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading text-blue-500 mr-1"></i>
                            Card Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="card_title"
                               name="card_title"
                               value="{{ old('card_title') }}"
                               required
                               class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Enter card title">
                        @error('card_title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left text-purple-500 mr-1"></i>
                            Description
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                  placeholder="Describe the task in detail">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag text-orange-500 mr-1"></i>
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority"
                                name="priority"
                                required
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-red-500 mr-1"></i>
                            Due Date
                        </label>
                        <input type="date"
                               id="due_date"
                               name="due_date"
                               value="{{ old('due_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        @error('due_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ✅ Status (Fixed - untuk CREATE card) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tasks text-purple-500 mr-2"></i>
                            Status
                        </label>

                        <!-- Display only - not editable -->
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 font-semibold flex items-center justify-between">
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span>
                                To Do (Default)
                            </span>
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>

                        <!-- Hidden input untuk submit -->
                        <input type="hidden" name="status" value="todo">

                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                            New cards will automatically be set to "To Do" status. Developers can update the status when they start working.
                        </p>
                    </div>

                    <!-- Estimated Hours -->
                    <div class="md:col-span-2">
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hourglass-half text-teal-500 mr-1"></i>
                            Estimated Hours
                        </label>
                        <input type="number"
                               id="estimated_hours"
                               name="estimated_hours"
                               value="{{ old('estimated_hours') }}"
                               step="0.5"
                               min="0"
                               class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                               placeholder="0.0">
                        @error('estimated_hours')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assign To Developers -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-users text-green-500 mr-2"></i>
                            Assign To Developers <span class="text-red-500">*</span>
                        </label>

                        @if(!isset($developers) || $developers->isEmpty())
                            <!-- No Developers Available -->
                            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-5">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-yellow-800 font-bold mb-1">No Developers Available</h4>
                                        <p class="text-yellow-700 text-sm mb-3">
                                            There are no developers assigned to this project yet. Please add developers to the project first before creating cards.
                                        </p>
                                        <div class="flex space-x-3">
                                            <a href="{{ route('teamlead.projects.show', $project->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                                                <i class="fas fa-user-plus mr-2"></i>
                                                Add Developers to Project
                                            </a>
                                            <a href="{{ route('teamlead.projects.show', $project->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-yellow-700 text-sm font-semibold rounded-lg transition border-2 border-yellow-300">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Back to Project
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input untuk validasi -->
                            <input type="hidden" name="assigned_to[]" value="">
                        @else
                            <!-- Developers List -->
                            <div class="space-y-4">
                                <!-- Select All Option -->
                                <div class="flex items-center justify-between bg-indigo-50 border-2 border-indigo-200 rounded-xl p-3">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                               id="selectAllDevelopers"
                                               class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500"
                                               onclick="toggleAllDevelopers(this)">
                                        <span class="text-sm font-semibold text-indigo-800">
                                            <i class="fas fa-users mr-1"></i>
                                            Select All Developers
                                        </span>
                                    </label>
                                    <span class="text-xs text-indigo-600 font-medium">
                                        {{ $developers->count() }} available
                                    </span>
                                </div>

                                <!-- Developers Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto border-2 border-gray-200 rounded-xl p-4 bg-gradient-to-br from-gray-50 to-blue-50">
                                    @foreach($developers as $developer)
                                        <label class="developer-checkbox flex items-center space-x-3 p-4 rounded-xl transition-all border-2 cursor-pointer
                                                      {{ in_array($developer->id, old('assigned_to', [])) ? 'bg-indigo-100 border-indigo-400 shadow-md' : 'bg-white border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 hover:shadow-sm' }}">
                                            <input type="checkbox"
                                                   name="assigned_to[]"
                                                   value="{{ $developer->id }}"
                                                   {{ in_array($developer->id, old('assigned_to', [])) ? 'checked' : '' }}
                                                   class="developer-checkbox-input w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 focus:ring-2 transition">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <!-- Avatar -->
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg flex-shrink-0 ring-2 ring-white">
                                                    {{ strtoupper(substr($developer->full_name ?? $developer->username, 0, 1)) }}
                                                </div>
                                                <!-- Info -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-gray-800 truncate">
                                                        {{ $developer->full_name ?? $developer->username }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 flex items-center">
                                                        <i class="fas fa-code mr-1.5 text-indigo-600"></i>
                                                        {{ ucfirst($developer->role) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Info & Counter -->
                                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl p-3">
                                    <p class="text-xs text-gray-600 flex items-center">
                                        <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                                        You can assign multiple developers. Hold <kbd class="px-2 py-0.5 bg-gray-200 rounded text-xs font-mono">Ctrl</kbd> to select multiple.
                                    </p>
                                    <span id="selectedCount" class="text-xs font-bold text-indigo-600">
                                        0 selected
                                    </span>
                                </div>
                            </div>
                        @endif

                        @error('assigned_to')
                            <p class="text-red-500 text-xs mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t-2 border-gray-200">
                    <a href="{{ route('teamlead.projects.show', $project->id) }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-lg">
                        <i class="fas fa-save mr-2"></i>Create Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle All Developers
function toggleAllDevelopers(checkbox) {
    const checkboxes = document.querySelectorAll('.developer-checkbox-input');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSelectedCount();
}

// Update Selected Count
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.developer-checkbox-input:checked');
    const count = checkboxes.length;
    const counter = document.getElementById('selectedCount');

    if (counter) {
        counter.textContent = count + ' selected';
        counter.className = count > 0
            ? 'text-xs font-bold text-indigo-600'
            : 'text-xs font-bold text-gray-400';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all checkboxes
    const checkboxes = document.querySelectorAll('.developer-checkbox-input');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Initial count
    updateSelectedCount();

    // Update select all state
    const selectAllCheckbox = document.getElementById('selectAllDevelopers');
    if (selectAllCheckbox) {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
    }
});
</script>
@endpush
@endsection
