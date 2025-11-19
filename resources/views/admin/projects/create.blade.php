@extends('layouts.admin')

@section('title', 'Create Project')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-2">Create New Project</h1>
        <p class="text-gray-500 mb-6">Assign a Team Lead to manage this project</p>

        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-2 border-red-200 rounded-xl p-4">
            <p class="font-bold text-red-800 mb-2">❌ Please fix errors:</p>
            <ul class="list-disc list-inside text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Project Name -->
            <div>
                <label class="block font-bold text-gray-700 mb-2">
                    <i class="fas fa-project-diagram text-blue-500"></i> Project Name *
                </label>
                <input type="text" name="project_name" required value="{{ old('project_name') }}"
                       placeholder="e.g., Website Redesign"
                       class="w-full px-4 py-3 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 @error('project_name') border-red-500 @enderror">
            </div>

            <!-- Description -->
            <div>
                <label class="block font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-purple-500"></i> Description
                </label>
                <textarea name="description" rows="4" placeholder="Project description..."
                          class="w-full px-4 py-3 border-2 rounded-xl focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
            </div>

            <!-- ✅ Assign Team Lead (PENTING) -->
            <div>
                <label class="block font-bold text-gray-700 mb-2">
                    <i class="fas fa-user-tie text-green-500"></i> Assign Team Lead *
                </label>
                <select name="team_lead_id" required
                        class="w-full px-4 py-3 border-2 rounded-xl focus:ring-2 focus:ring-green-500 @error('team_lead_id') border-red-500 @enderror">
                    <option value="">-- Select Team Lead --</option>
                    @foreach($teamLeads as $lead)
                        <option value="{{ $lead->id }}" {{ old('team_lead_id') == $lead->id ? 'selected' : '' }}>
                            {{ $lead->full_name }} ({{ $lead->username }})
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Team Lead will manage cards and tasks</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Deadline -->
                <div>
                    <label class="block font-bold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-red-500"></i> Deadline
                    </label>
                    <input type="date" name="deadline" min="{{ date('Y-m-d') }}" value="{{ old('deadline') }}"
                           class="w-full px-4 py-3 border-2 rounded-xl focus:ring-2 focus:ring-red-500">
                </div>

                <!-- Thumbnail -->
                <div>
                    <label class="block font-bold text-gray-700 mb-2">
                        <i class="fas fa-image text-indigo-500"></i> Thumbnail
                    </label>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full px-4 py-3 border-2 rounded-xl">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB (JPEG, PNG, JPG, WEBP)</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('admin.allprojects') }}"
                   class="px-6 py-3 border-2 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg font-bold">
                    <i class="fas fa-plus mr-2"></i>Create Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
