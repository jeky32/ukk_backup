@extends('layouts.teamlead')

@section('title', 'Edit Card')

@section('content')
<div class="px-4 py-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-6">
            <a href="{{ route('teamlead.card.detail', $card->id) }}" 
               class="text-indigo-600 hover:text-indigo-800 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Card
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-indigo-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Edit Card</h2>
            <p class="text-gray-600 mb-6">
                Board: <span class="font-semibold">{{ $card->board->board_name }}</span> • 
                {{ $card->board->project->project_name }}
            </p>

            <form action="{{ route('teamlead.cards.update', $card) }}" method="POST">
                @csrf
                @method('PUT')

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
                               value="{{ old('card_title', $card->card_title) }}" 
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
                                  placeholder="Describe the task in detail">{{ old('description', $card->description) }}</textarea>
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
                            <option value="low" {{ old('priority', $card->priority) === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $card->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $card->priority) === 'high' ? 'selected' : '' }}>High</option>
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
                               value="{{ old('due_date', $card->due_date) }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        @error('due_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ✅ Status (Display Only - Locked, No Dropdown) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tasks text-purple-500 mr-2"></i>
                            Current Status
                        </label>
                        
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl flex items-center justify-between">
                            <span class="flex items-center font-semibold">
                                @if($card->status === 'todo')
                                    <span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span>
                                    <span class="text-gray-700">To Do</span>
                                @elseif($card->status === 'in_progress')
                                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                    <span class="text-blue-700">In Progress</span>
                                @elseif($card->status === 'review')
                                    <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                    <span class="text-yellow-700">Review</span>
                                @elseif($card->status === 'done')
                                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    <span class="text-green-700">Done</span>
                                @elseif($card->status === 'blocked')
                                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                    <span class="text-red-700">Blocked</span>
                                @endif
                            </span>
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        
                        <input type="hidden" name="status" value="{{ $card->status }}">
                        
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                            Card status can only be changed by assigned developers during task execution.
                        </p>
                    </div>

                    <!-- Estimated Hours -->
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hourglass-half text-teal-500 mr-1"></i>
                            Estimated Hours
                        </label>
                        <input type="number" 
                               id="estimated_hours" 
                               name="estimated_hours" 
                               value="{{ old('estimated_hours', $card->estimated_hours) }}" 
                               step="0.5" 
                               min="0"
                               class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                               placeholder="0.0">
                        @error('estimated_hours')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ✅ Assign To Developers (FIXED) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-users text-green-500 mr-2"></i>
                            Assign To Developers
                        </label>
                        
                        @if($developers->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <p class="text-yellow-800 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No developers available in this project.
                                </p>
                            </div>
                        @else
                            @php
                                $assignedIds = $card->assignments->pluck('user_id')->toArray();
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-80 overflow-y-auto border-2 border-gray-200 rounded-xl p-4 bg-gray-50">
                                @foreach($developers as $developer)
                                    @php
                                        $isAssigned = in_array($developer->id, $assignedIds);
                                    @endphp
                                    <label class="flex items-center space-x-3 p-3 rounded-lg transition-colors border-2 {{ $isAssigned ? 'border-indigo-400 bg-indigo-50' : 'border-transparent' }} cursor-pointer hover:bg-white hover:border-indigo-300">
                                        <input type="checkbox" 
                                               name="assigned_to[]" 
                                               value="{{ $developer->id }}"
                                               {{ $isAssigned ? 'checked' : '' }}
                                               class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                        <div class="flex items-center space-x-2 flex-1">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                                {{ strtoupper(substr($developer->full_name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-800">
                                                    {{ $developer->full_name }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ ucfirst($developer->role) }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                You can assign multiple developers. Uncheck all to remove all assignments.
                            </p>
                        @endif
                        @error('assigned_to')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t-2 border-gray-200">
                    <a href="{{ route('teamlead.card.detail', $card->id) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-lg">
                        <i class="fas fa-save mr-2"></i>Update Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
