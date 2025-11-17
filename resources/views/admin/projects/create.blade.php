@extends('layouts.admin')

@section('title', 'Create Project')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-6">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-6 animate-fade-in">
            <a href="{{ route('admin.projects.index') }}"
               class="inline-flex items-center space-x-2 px-4 py-2 bg-white/90 backdrop-blur-sm text-gray-700 rounded-lg hover:shadow-lg transition-all duration-300 border border-gray-200 mb-4">
                <i class="fas fa-arrow-left text-indigo-600"></i>
                <span class="font-semibold text-sm">Back to Projects</span>
            </a>

            <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                Create New Project
            </h2>
            <p class="text-gray-600 text-sm mt-1">Fill in the details to create a new project</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden animate-slide-up">
            <div class="p-8">
                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" id="createProjectForm">
                    @csrf

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6 animate-shake">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-xl mr-3 mt-0.5"></i>
                            <div>
                                <p class="font-semibold mb-2">Please fix the following errors:</p>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Project Name -->
                    <div class="mb-6">
                        <label for="project_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="project_name"
                               id="project_name"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300"
                               placeholder="Enter project name..."
                               value="{{ old('project_name') }}"
                               required>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300"
                                  placeholder="Describe your project...">{{ old('description') }}</textarea>
                    </div>

                    {{--  <!-- Upload Thumbnail -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Project Thumbnail
                        </label>
                        <div class="flex items-start space-x-4">
                            <!-- Upload Button -->
                            <label class="flex flex-col items-center justify-center px-6 py-8 bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-dashed border-indigo-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-100 transition-all duration-300 group">
                                <i class="fas fa-cloud-upload-alt text-4xl text-indigo-500 group-hover:text-indigo-600 mb-2"></i>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">Upload Image</span>
                                <span class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB)</span>
                                <input type="file"
                                       name="thumbnail"
                                       accept="image/*"
                                       class="hidden"
                                       id="thumbnail-input">
                            </label>

                            <!-- Preview -->
                            <div id="thumbnail-preview" class="hidden">
                                <div class="relative">
                                    <img id="preview-image"
                                         class="w-40 h-40 rounded-lg object-cover shadow-lg border-4 border-white"
                                         alt="Preview">
                                    <button type="button"
                                            id="remove-thumbnail"
                                            class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all duration-300 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 text-center">Click X to remove</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>  --}}

                    <!-- Deadline dengan validasi min hari ini -->
                    <div class="mb-8">
                        <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="far fa-calendar text-red-500 mr-1"></i>
                            Deadline
                        </label>
                        <input type="date"
                               name="deadline"
                               id="deadline"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300"
                               value="{{ old('deadline') }}">
                        @error('deadline')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.projects.index') }}"
                           class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-300 font-semibold">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Create Project</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ✅ VALIDASI DEADLINE - Tidak boleh memilih tanggal masa lalu
document.addEventListener('DOMContentLoaded', function() {
    const deadlineInput = document.getElementById('deadline');
    const form = document.getElementById('createProjectForm');

    if (deadlineInput) {
        // Set minimum date ke hari ini
        const today = new Date().toISOString().split('T')[0];
        deadlineInput.setAttribute('min', today);

        // Validasi saat tanggal berubah
        deadlineInput.addEventListener('change', function() {
            const selectedDate = this.value;

            if (selectedDate && selectedDate < today) {
                alert('⚠️ Deadline tidak boleh di masa lalu!');
                this.value = '';
                this.focus();
            }
        });
    }

    // Validasi sebelum form submit
    if (form) {
        form.addEventListener('submit', function(e) {
            const deadlineValue = deadlineInput.value;
            const today = new Date().toISOString().split('T')[0];

            if (deadlineValue && deadlineValue < today) {
                e.preventDefault();
                alert('❌ Deadline tidak boleh di masa lalu! Silakan pilih tanggal hari ini atau ke depan.');
                deadlineInput.focus();
                return false;
            }
        });
    }
});

// ✅ Preview Thumbnail (jika diaktifkan)
const thumbnailInput = document.getElementById('thumbnail-input');
const thumbnailPreview = document.getElementById('thumbnail-preview');
const previewImage = document.getElementById('preview-image');
const removeButton = document.getElementById('remove-thumbnail');

if (thumbnailInput) {
    // Preview image saat file dipilih
    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('⚠️ File size must be less than 2MB!');
                thumbnailInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                thumbnailPreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove thumbnail
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            thumbnailInput.value = '';
            thumbnailPreview.classList.add('hidden');
            previewImage.src = '';
        });
    }
}
</script>

<!-- Animations -->
<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

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

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out;
    }

    .animate-shake {
        animation: shake 0.5s ease-out;
    }
</style>
@endsection
