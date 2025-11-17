<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        return view('admin.settings.index', compact('user'));
    }

    /**
     * Update general settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'timezone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update settings logic here
        // You can save to database or config files

        return redirect()->route('admin.settings')
            ->with('success', 'General settings updated successfully!');
    }

    /**
     * Update user preferences
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'language' => 'nullable|string|in:en,id',
            'theme' => 'nullable|string|in:light,dark,auto',
            'items_per_page' => 'nullable|integer|min:10|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user preferences
        // You can save to user_settings table or user metadata

        return redirect()->route('admin.settings')
            ->with('success', 'Preferences updated successfully!');
    }

    /**
     * Update notification settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
            'task_reminders' => 'nullable|boolean',
            'project_updates' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update notification settings
        // Save to user_notification_settings table

        return redirect()->route('admin.settings')
            ->with('success', 'Notification settings updated successfully!');
    }

    /**
     * Update privacy settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'profile_visibility' => 'nullable|string|in:public,private,team',
            'show_email' => 'nullable|boolean',
            'show_activity' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update privacy settings
        // Save to user_privacy_settings table

        return redirect()->route('admin.settings')
            ->with('success', 'Privacy settings updated successfully!');
    }

    /**
     * Update password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect!');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Update profile information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update profile
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? $user->phone;
        $user->bio = $request->bio ?? $user->bio;
        $user->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Profile updated successfully!');
    }
}
