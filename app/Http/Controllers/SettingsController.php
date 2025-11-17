<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $settings = $user->getSettings();

            return view('admin.settings.index', compact('user', 'settings'));
        } catch (\Exception $e) {
            Log::error('Settings page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load settings page.');
        }
    }

    /**
     * Update profile information
     * Save phone & bio to users table directly
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'bio.max' => 'Bio cannot exceed 500 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            DB::beginTransaction();

            // Update user table
            $user->full_name = $request->filled('full_name') ? $request->full_name : $user->full_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->bio = $request->bio;
            $user->save();

            DB::commit();

            Log::info('User profile updated', ['user_id' => $user->id]);

            return redirect()->route('admin.settings')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update failed: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $settings = $user->getSettings();

        $validator = Validator::make($request->all(), [
            'language' => 'required|string|in:en,id',
            'theme' => 'required|string|in:light,dark,auto',
            'items_per_page' => 'required|integer|min:10|max:100',
            'timezone' => 'nullable|string|max:100',
        ], [
            'language.required' => 'Language is required.',
            'language.in' => 'Invalid language selection.',
            'theme.required' => 'Theme is required.',
            'theme.in' => 'Invalid theme selection.',
            'items_per_page.required' => 'Items per page is required.',
            'items_per_page.integer' => 'Items per page must be a number.',
            'items_per_page.min' => 'Items per page must be at least 10.',
            'items_per_page.max' => 'Items per page cannot exceed 100.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            $settings->language = $request->language;
            $settings->theme = $request->theme;
            $settings->items_per_page = $request->items_per_page;
            $settings->timezone = $request->filled('timezone') ? $request->timezone : 'Asia/Jakarta';
            $settings->save();

            Log::info('User preferences updated', ['user_id' => $user->id]);

            return redirect()->route('admin.settings')
                ->with('success', 'Preferences updated successfully!');
        } catch (\Exception $e) {
            Log::error('Preferences update failed: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update preferences. Please try again.');
        }
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $settings = $user->getSettings();

        try {
            $settings->email_notifications = $request->has('email_notifications');
            $settings->push_notifications = $request->has('push_notifications');
            $settings->task_reminders = $request->has('task_reminders');
            $settings->project_updates = $request->has('project_updates');
            $settings->team_notifications = $request->has('team_notifications');
            $settings->save();

            Log::info('Notification settings updated', ['user_id' => $user->id]);

            return redirect()->route('admin.settings')
                ->with('success', 'Notification settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Notification settings update failed: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->back()
                ->with('error', 'Failed to update notification settings. Please try again.');
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        $settings = $user->getSettings();

        $validator = Validator::make($request->all(), [
            'profile_visibility' => 'required|string|in:public,team,private',
        ], [
            'profile_visibility.required' => 'Profile visibility is required.',
            'profile_visibility.in' => 'Invalid profile visibility option.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            $settings->profile_visibility = $request->profile_visibility;
            $settings->show_email = $request->has('show_email');
            $settings->show_activity = $request->has('show_activity');
            $settings->show_online_status = $request->has('show_online_status');
            $settings->save();

            Log::info('Privacy settings updated', ['user_id' => $user->id]);

            return redirect()->route('admin.settings')
                ->with('success', 'Privacy settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Privacy settings update failed: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update privacy settings. Please try again.');
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Current password is incorrect!');
        }

        // Check if new password is same as current
        if (Hash::check($request->new_password, $user->password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'New password cannot be the same as current password!');
        }

        try {
            DB::beginTransaction();

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            DB::commit();

            Log::info('Password updated', ['user_id' => $user->id]);

            return redirect()->route('admin.settings')
                ->with('success', 'Password updated successfully! Please use your new password for next login.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Password update failed: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->back()
                ->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Reset settings to default
     */
    public function resetSettings(Request $request)
    {
        $user = Auth::user();
        $settings = $user->getSettings();

        try {
            DB::beginTransaction();

            // Reset to defaults
            $settings->language = 'en';
            $settings->theme = 'light';
            $settings->items_per_page = 25;
            $settings->timezone = 'Asia/Jakarta';
            $settings->email_notifications = true;
            $settings->push_notifications = true;
            $settings->task_reminders = true;
            $settings->project_updates = true;
            $settings->team_notifications = true;
            $settings->profile_visibility = 'team';
            $settings->show_email = false;
            $settings->show_activity = true;
            $settings->show_online_status = true;
            $settings->save();

            DB::commit();

            Log::info('Settings reset to default', ['user_id' => $user->id]);

            return redirect()->route('admin.settings')
                ->with('success', 'All settings have been reset to default values successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Settings reset failed: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->back()
                ->with('error', 'Failed to reset settings. Please try again.');
        }
    }
}
