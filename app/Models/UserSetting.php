<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'bio',
        'language',
        'theme',
        'items_per_page',
        'timezone',
        'email_notifications',
        'push_notifications',
        'task_reminders',
        'project_updates',
        'team_notifications',
        'profile_visibility',
        'show_email',
        'show_activity',
        'show_online_status',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'task_reminders' => 'boolean',
        'project_updates' => 'boolean',
        'team_notifications' => 'boolean',
        'show_email' => 'boolean',
        'show_activity' => 'boolean',
        'show_online_status' => 'boolean',
        'items_per_page' => 'integer',
    ];

    /**
     * Get the user that owns the settings
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create settings for a user
     */
    public static function getOrCreate($userId)
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'language' => 'en',
                'theme' => 'light',
                'items_per_page' => 25,
                'timezone' => 'Asia/Jakarta',
                'email_notifications' => true,
                'push_notifications' => true,
                'task_reminders' => true,
                'project_updates' => true,
                'team_notifications' => true,
                'profile_visibility' => 'team',
                'show_email' => false,
                'show_activity' => true,
                'show_online_status' => true,
            ]
        );
    }
}
