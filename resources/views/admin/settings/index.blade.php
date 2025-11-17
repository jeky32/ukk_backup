@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-cog text-blue-600"></i> Settings
        </h1>
        <p class="text-gray-600">Manage your account and application preferences</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-xl mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-xl mr-3 mt-1"></i>
                <div class="flex-1">
                    <strong class="font-bold">Whoops! There were some problems:</strong>
                    <ul class="mt-2 ml-4 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Settings Tabs & Content -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 bg-gray-50" x-data="{ activeTab: 'profile' }">
            <nav class="flex overflow-x-auto">
                <button @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-user mr-2"></i> Profile
                </button>
                <button @click="activeTab = 'preferences'"
                        :class="activeTab === 'preferences' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-sliders-h mr-2"></i> Preferences
                </button>
                <button @click="activeTab = 'notifications'"
                        :class="activeTab === 'notifications' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-bell mr-2"></i> Notifications
                </button>
                <button @click="activeTab = 'privacy'"
                        :class="activeTab === 'privacy' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-lock mr-2"></i> Privacy
                </button>
                <button @click="activeTab = 'security'"
                        :class="activeTab === 'security' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-shield-alt mr-2"></i> Security
                </button>
            </nav>

            <!-- Tab Content -->
            <div class="p-6 md:p-8">
                <!-- Profile Tab -->
                <div x-show="activeTab === 'profile'" x-transition>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Profile Information</h2>
                    <form action="{{ route('admin.settings.profile') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="full_name" value="{{ old('full_name', $settings->full_name ?? $user->username) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" value="{{ $user->username }}" disabled
                                       class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $settings->phone ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea name="bio" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                      placeholder="Tell us about yourself...">{{ old('bio', $settings->bio ?? '') }}</textarea>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i> Save Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preferences Tab -->
                <div x-show="activeTab === 'preferences'" x-transition style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Application Preferences</h2>

                    <form action="{{ route('admin.settings.preferences') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Language Dropdown -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Language <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="language" required
                                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                        <option value="en" {{ ($settings->language ?? 'en') == 'en' ? 'selected' : '' }}>
                                            English
                                        </option>
                                        <option value="id" {{ ($settings->language ?? 'en') == 'id' ? 'selected' : '' }}>
                                            Bahasa Indonesia
                                        </option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-600">
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Choose your preferred language</p>
                            </div>

                            <!-- Theme Dropdown -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Theme <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="theme" required
                                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                        <option value="light" {{ ($settings->theme ?? 'light') == 'light' ? 'selected' : '' }}>
                                            Light
                                        </option>
                                        <option value="dark" {{ ($settings->theme ?? 'light') == 'dark' ? 'selected' : '' }}>
                                            Dark
                                        </option>
                                        <option value="auto" {{ ($settings->theme ?? 'light') == 'auto' ? 'selected' : '' }}>
                                            Auto (System)
                                        </option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-600">
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Select your preferred color theme</p>
                            </div>
                        </div>

                        <!-- Timezone (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Timezone
                            </label>
                            <div class="relative">
                                <select name="timezone"
                                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="Asia/Jakarta" {{ ($settings->timezone ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>
                                        Asia/Jakarta (WIB - GMT+7)
                                    </option>
                                    <option value="Asia/Makassar" {{ ($settings->timezone ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>
                                        Asia/Makassar (WITA - GMT+8)
                                    </option>
                                    <option value="Asia/Jayapura" {{ ($settings->timezone ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>
                                        Asia/Jayapura (WIT - GMT+9)
                                    </option>
                                    <option value="UTC" {{ ($settings->timezone ?? 'Asia/Jakarta') == 'UTC' ? 'selected' : '' }}>
                                        UTC (GMT+0)
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-600">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Your timezone for date and time display</p>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Save Preferences
                            </button>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800 mb-1">About Preferences</h4>
                                <p class="text-sm text-blue-700">
                                    These settings control how the application displays information to you.
                                    Changes will take effect immediately after saving.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div x-show="activeTab === 'notifications'" x-transition style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Notification Settings</h2>
                    <form action="{{ route('admin.settings.notifications') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Email Notifications</h3>
                                    <p class="text-sm text-gray-600">Receive updates via email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_notifications" class="sr-only peer" {{ ($settings->email_notifications ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Push Notifications</h3>
                                    <p class="text-sm text-gray-600">Receive browser notifications</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="push_notifications" class="sr-only peer" {{ ($settings->push_notifications ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Task Reminders</h3>
                                    <p class="text-sm text-gray-600">Get reminders for upcoming tasks</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="task_reminders" class="sr-only peer" {{ ($settings->task_reminders ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Project Updates</h3>
                                    <p class="text-sm text-gray-600">Notify when projects are updated</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="project_updates" class="sr-only peer" {{ ($settings->project_updates ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i> Save Notifications
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Privacy Tab -->
                <div x-show="activeTab === 'privacy'" x-transition style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Privacy Settings</h2>
                    <form action="{{ route('admin.settings.privacy') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Visibility *</label>
                            <select name="profile_visibility" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="public" {{ ($settings->profile_visibility ?? 'team') == 'public' ? 'selected' : '' }}>Public - Everyone can see</option>
                                <option value="team" {{ ($settings->profile_visibility ?? 'team') == 'team' ? 'selected' : '' }}>Team Only</option>
                                <option value="private" {{ ($settings->profile_visibility ?? 'team') == 'private' ? 'selected' : '' }}>Private - Only me</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Show Email</h3>
                                    <p class="text-sm text-gray-600">Display email on profile</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="show_email" class="sr-only peer" {{ ($settings->show_email ?? false) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Show Activity</h3>
                                    <p class="text-sm text-gray-600">Display recent activities</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="show_activity" class="sr-only peer" {{ ($settings->show_activity ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i> Save Privacy
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" x-transition style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Security Settings</h2>
                    <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-1"></i>
                                <p class="text-sm text-yellow-700">
                                    Make sure to use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and symbols.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                            <input type="password" name="new_password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                            <input type="password" name="new_password_confirmation" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all shadow-md hover:shadow-lg">
                                <i class="fas fa-key mr-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Settings Button -->
    <div class="mt-6">
        <form action="{{ route('admin.settings.reset') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset all settings to default?');">
            @csrf
            <button type="submit" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-undo mr-2"></i> Reset All Settings to Default
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endsection
