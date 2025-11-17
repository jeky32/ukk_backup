@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4">

        <!-- Header dengan animasi -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('admin.allprojects') }}"
                   class="group inline-flex items-center space-x-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm text-gray-700 rounded-xl hover:shadow-xl transition-all duration-300 border border-gray-200">
                    <i class="fas fa-arrow-left text-indigo-600 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="font-semibold text-sm">Back to Projects</span>
                </a>

                <!-- Badge Status -->
                <span class="px-4 py-2 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                    <i class="fas fa-edit mr-1"></i>EDIT MODE
                </span>
            </div>

            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-pen-to-square text-3xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-4xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Edit Project
                    </h2>
                    <p class="text-gray-600 text-sm mt-1 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Update your project information and settings
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card dengan gradient border -->
        <div class="relative animate-slide-up">
            <!-- Gradient border effect -->
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur opacity-20"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Project Information
                    </h3>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Error Messages -->
                        @if ($errors->any())
                        <div class="relative mb-6 animate-shake">
                            <div class="absolute inset-0 bg-red-500 rounded-xl blur opacity-20"></div>
                            <div class="relative bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-xl">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-bold text-lg mb-2">Oops! Please fix these errors:</p>
                                        <ul class="space-y-1 text-sm">
                                            @foreach ($errors->all() as $error)
                                                <li class="flex items-center">
                                                    <i class="fas fa-circle text-xs mr-2"></i>
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Project Name -->
                        <div class="mb-6 group">
                            <label for="project_name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-project-diagram text-indigo-600 mr-2"></i>
                                Project Name <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       name="project_name"
                                       id="project_name"
                                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 group-hover:border-indigo-300"
                                       placeholder="e.g., E-Commerce Website Redesign"
                                       value="{{ old('project_name', $project->project_name) }}"
                                       required>
                                <i class="fas fa-heading absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6 group">
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2 flex items-center justify-between">
                                <span class="flex items-center">
                                    <i class="fas fa-align-left text-purple-600 mr-2"></i>
                                    Description
                                </span>
                                <span id="char-count" class="text-xs text-gray-400"></span>
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      maxlength="500"
                                      class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 resize-none group-hover:border-purple-300"
                                      placeholder="Describe your project goals, features, and requirements...">{{ old('description', $project->description) }}</textarea>
                        </div>

                        <!-- ✅ GITHUB LINK (BARU) -->
                        <div class="mb-6 group">
                            <label for="github_link" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                <i class="fab fa-github text-gray-900 mr-2 text-lg"></i>
                                GitHub Repository Link
                                <span class="ml-2 text-xs font-normal text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative">
                                <input type="url"
                                       name="github_link"
                                       id="github_link"
                                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-gray-500/20 focus:border-gray-500 transition-all duration-300 group-hover:border-gray-300"
                                       placeholder="https://github.com/username/repository"
                                       value="{{ old('github_link', $project->github_link ?? '') }}">
                                <i class="fab fa-github absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                                <i class="fas fa-info-circle text-gray-600 mr-2"></i>
                                Add GitHub repository link for backup and version control
                            </p>
                            @error('github_link')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Thumbnail Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-image text-pink-600 mr-2"></i>
                                Project Thumbnail
                            </label>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Current Thumbnail -->
                                @if($project->thumbnail)
                                <div id="current-thumbnail" class="space-y-3">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Image</p>
                                    <div class="relative group/thumb">
                                        <img src="{{ asset('storage/' . $project->thumbnail) }}"
                                             alt="Current Thumbnail"
                                             class="w-full h-56 rounded-xl object-cover shadow-lg border-4 border-white ring-2 ring-gray-200">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover/thumb:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                            <button type="button"
                                                    id="delete-current-thumbnail"
                                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-semibold">
                                                <i class="fas fa-trash mr-2"></i>Delete Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Upload New -->
                                <div class="space-y-3">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        {{ $project->thumbnail ? 'Upload New' : 'Upload Image' }}
                                    </p>
                                    <label class="group/upload relative flex flex-col items-center justify-center h-56 px-6 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 border-2 border-dashed border-indigo-300 rounded-xl cursor-pointer hover:border-indigo-500 hover:from-indigo-100 hover:via-purple-100 hover:to-pink-100 transition-all duration-300">
                                        <i class="fas fa-cloud-upload-alt text-5xl text-indigo-500 group-hover/upload:text-indigo-600 group-hover/upload:scale-110 transition-all mb-3"></i>
                                        <span class="text-sm font-bold text-gray-700 group-hover/upload:text-indigo-600">
                                            Click to upload
                                        </span>
                                        <span class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB)</span>
                                        <input type="file"
                                               name="thumbnail"
                                               accept="image/*"
                                               class="hidden"
                                               id="thumbnail-input">
                                    </label>

                                    <!-- Preview New -->
                                    <div id="thumbnail-preview" class="hidden">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Preview</p>
                                        <div class="relative">
                                            <img id="preview-image"
                                                 class="w-full h-56 rounded-xl object-cover shadow-lg border-4 border-white ring-2 ring-indigo-300"
                                                 alt="Preview">
                                            <button type="button"
                                                    id="remove-thumbnail"
                                                    class="absolute top-2 right-2 w-10 h-10 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all shadow-lg flex items-center justify-center">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('thumbnail')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="mb-8 group">
                            <label for="deadline" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-alt text-red-600 mr-2"></i>
                                Project Deadline
                            </label>
                            <div class="relative">
                                <input type="date"
                                       name="deadline"
                                       id="deadline"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 transition-all duration-300 group-hover:border-red-300"
                                       value="{{ old('deadline', $project->deadline ? date('Y-m-d', strtotime($project->deadline)) : '') }}">
                                <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-red-400"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center bg-amber-50 px-3 py-2 rounded-lg border border-amber-200">
                                <i class="fas fa-info-circle text-amber-600 mr-2"></i>
                                Deadline minimal hari ini <strong class="ml-1">({{ date('d M Y') }})</strong>
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-8 border-t-2 border-gray-100">
                            <a href="{{ route('admin.allprojects') }}"
                               class="group px-8 py-4 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 hover:shadow-lg transition-all duration-300 font-bold flex items-center space-x-2">
                                <i class="fas fa-times group-hover:rotate-90 transition-transform"></i>
                                <span>Cancel</span>
                            </a>
                            <button type="submit"
                                    class="group px-10 py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white rounded-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 font-bold flex items-center space-x-3">
                                <i class="fas fa-save group-hover:rotate-12 transition-transform"></i>
                                <span>Update Project</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Character counter
    const descriptionField = document.getElementById('description');
    const charCount = document.getElementById('char-count');

    function updateCharCount() {
        const length = descriptionField.value.length;
        const maxLength = 500;
        charCount.textContent = `${length}/${maxLength} characters`;

        if (length > maxLength * 0.9) {
            charCount.classList.add('text-red-500', 'font-bold');
        } else {
            charCount.classList.remove('text-red-500', 'font-bold');
        }
    }

    descriptionField.addEventListener('input', updateCharCount);
    updateCharCount();

    // Thumbnail preview
    const thumbnailInput = document.getElementById('thumbnail-input');
    const thumbnailPreview = document.getElementById('thumbnail-preview');
    const previewImage = document.getElementById('preview-image');
    const removeButton = document.getElementById('remove-thumbnail');
    const currentThumbnail = document.getElementById('current-thumbnail');
    const deleteCurrentButton = document.getElementById('delete-current-thumbnail');

    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files;
        if (file) {
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

    if (removeButton) {
        removeButton.addEventListener('click', function() {
            thumbnailInput.value = '';
            thumbnailPreview.classList.add('hidden');
            previewImage.src = '';
        });
    }

    if (deleteCurrentButton) {
        deleteCurrentButton.addEventListener('click', function() {
            if (confirm('🗑️ Are you sure you want to delete the current thumbnail?')) {
                currentThumbnail.remove();
                alert('✅ Thumbnail will be removed after saving!');
            }
        });
    }
</script>

<!-- Animations -->
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
        20%, 40%, 60%, 80% { transform: translateX(8px); }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 0.8s ease-out;
    }

    .animate-shake {
        animation: shake 0.6s ease-out;
    }

    * {
        scroll-behavior: smooth;
    }
</style>
@endsection
