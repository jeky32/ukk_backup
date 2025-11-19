<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * ✅ Primary key default Laravel ('id')
     */
    protected $primaryKey = 'id';

    /**
     * ✅ Aktifkan timestamps (karena tabel punya created_at & updated_at)
     */
    public $timestamps = true;

    /**
     * ✅ Kolom yang bisa diisi lewat mass assignment
     */
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'current_task_status',
        'phone',
        'bio',
        'avatar',
    ];

    /**
     * ✅ Kolom yang disembunyikan saat model dikonversi ke array/json
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * ✅ Casting untuk tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * ✅ Nilai default untuk atribut (sesuai database)
     */
    protected $attributes = [
        'current_task_status' => 'idle',
    ];

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - PROFILE
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ USER PROFILE (One-to-One)
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    /**
     * ✅ Get or create profile for this user
     */
    public function getOrCreateProfile()
    {
        if (!$this->profile) {
            return Profile::create([
                'user_id' => $this->id,
                'bio' => $this->bio ?? '',
                'phone' => $this->phone ?? '',
                'avatar' => $this->avatar ?? null,
            ]);
        }
        return $this->profile;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - PROJECT & MEMBERSHIP
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ PROYEK YANG DIBUAT (sebagai creator)
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by', 'id');
    }

    /**
     * ✅ PROYEK YANG DIBUAT (alias untuk backward compatibility)
     */
    public function ledProjects()
    {
        return $this->createdProjects();
    }

    /**
     * ✅ PROYEK YANG DIKERJAKAN (sebagai member via pivot table)
     * Banyak user → banyak projects (melalui project_members)
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members', 'user_id', 'project_id')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * ✅ PROYEK MEMBERSHIP (intermediate table records)
     */
    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class, 'user_id', 'id');
    }

    /**
     * ✅ Get all members in user's projects (team members)
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - TASKS & ASSIGNMENTS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ CARD ASSIGNMENTS (intermediate table records)
     */
    public function assignments()
    {
        return $this->hasMany(CardAssignment::class, 'user_id', 'id');
    }

    /**
     * ✅ TUGAS YANG DI-ASSIGN KE USER (via pivot table)
     */
    public function assignedCards()
    {
        return $this->belongsToMany(Card::class, 'card_assignments', 'user_id', 'card_id')
            ->withPivot('assignment_status', 'started_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * ✅ TUGAS YANG SEDANG DIKERJAKAN (current task)
     */
    public function currentTask()
    {
        return $this->hasOne(CardAssignment::class, 'user_id')
            ->where('assignment_status', 'in_progress')
            ->latest();
    }

    /**
     * ✅✅ TAMBAHAN BARU: Get active assignments
     */
    public function activeAssignments()
    {
        return $this->assignments()
            ->whereIn('assignment_status', ['assigned', 'in_progress'])
            ->with('card.board.project');
    }

    /**
     * ✅✅ TAMBAHAN BARU: Get completed assignments
     */
    public function completedAssignments()
    {
        return $this->assignments()
            ->where('assignment_status', 'completed')
            ->with('card.board.project');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - TIME TRACKING
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ TIME LOGS untuk user ini
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'user_id')->latest();
    }

    /**
     * ✅ ACTIVE TIME LOG (sedang berjalan)
     */
    public function activeTimeLog()
    {
        return $this->hasOne(TimeLog::class, 'user_id')
            ->whereNull('end_time')
            ->latest();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - SETTINGS & COMMENTS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ USER SETTINGS
     */
    public function settings()
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

    /**
     * ✅ Get or create settings for this user
     */
    public function getSettings()
    {
        if (!$this->settings) {
            return UserSetting::getOrCreate($this->id);
        }
        return $this->settings;
    }

    /**
     * ✅ COMMENTS by this user
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id')->latest();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 💬 RELATIONSHIPS - MESSAGES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ PESAN YANG DIKIRIM user ini
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'id')->latest();
    }

    /**
     * ✅ PESAN YANG DITERIMA user ini
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id')->latest();
    }

    /**
     * ✅ PESAN YANG BELUM DIBACA
     */
    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id')
            ->where('is_read', false)
            ->latest();
    }

    /**
     * ✅ GET JUMLAH PESAN BELUM DIBACA
     */
    public function getUnreadMessagesCountAttribute()
    {
        return $this->unreadMessages()->count();
    }

    /**
     * ✅ GET LAST MESSAGE dengan user tertentu
     */
    public function getLastMessageWith($userId)
    {
        return Message::where(function($query) use ($userId) {
                $query->where('sender_id', $this->id)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $this->id);
            })
            ->latest()
            ->first();
    }

    /**
     * ✅ GET CONVERSATION dengan user tertentu
     */
    public function getConversationWith($userId)
    {
        return Message::where(function($query) use ($userId) {
                $query->where('sender_id', $this->id)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $this->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * ✅ SEND MESSAGE ke user lain
     */
    public function sendMessageTo($receiverId, $messageText)
    {
        return Message::create([
            'sender_id' => $this->id,
            'receiver_id' => $receiverId,
            'message' => $messageText,
            'is_read' => false,
        ]);
    }

    /**
     * ✅ MARK ALL MESSAGES dari user tertentu sebagai sudah dibaca
     */
    public function markMessagesAsReadFrom($senderId)
    {
        return Message::where('sender_id', $senderId)
            ->where('receiver_id', $this->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🎨 ACCESSORS & ATTRIBUTES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Getter untuk display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->username;
    }

    /**
     * ✅ Getter untuk status color
     */
    public function getStatusColorAttribute()
    {
        return match($this->current_task_status) {
            'idle' => 'green',
            'working' => 'yellow',
            'busy' => 'orange',
            'blocked' => 'red',
            'offline' => 'gray',
            default => 'gray'
        };
    }

    /**
     * ✅ Getter untuk role badge color
     */
    public function getRoleBadgeColorAttribute()
    {
        return match($this->role) {
            'admin' => 'purple',
            'teamlead' => 'blue',
            'developer' => 'green',
            'designer' => 'pink',
            'member' => 'gray',
            default => 'gray'
        };
    }

    /**
     * ✅ Getter untuk avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->profile && $this->profile->avatar) {
            return asset('storage/avatars/' . $this->profile->avatar);
        }

        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://i.pravatar.cc/150?u=' . $this->id;
    }

    /**
     * ✅ CEK apakah user sedang online
     */
    public function getIsOnlineAttribute()
    {
        return $this->updated_at && $this->updated_at->gt(now()->subMinutes(5));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔍 HELPER METHODS - ROLE CHECKS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Cek apakah user memiliki role tertentu
     */
    public function hasRole($role)
    {
        return $this->role === strtolower($role);
    }

    /**
     * ✅ Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * ✅ Cek apakah user adalah teamlead
     */
    public function isTeamLead()
    {
        return $this->hasRole('teamlead');
    }

    /**
     * ✅ Cek apakah user adalah developer
     */
    public function isDeveloper()
    {
        return $this->hasRole('developer');
    }

    /**
     * ✅ Cek apakah user adalah designer
     */
    public function isDesigner()
    {
        return $this->hasRole('designer');
    }

    /**
     * ✅ Cek apakah user adalah member biasa
     */
    public function isMember()
    {
        return $this->hasRole('member');
    }

    /**
     * ✅ Cek apakah user bisa assign tugas
     */
    public function canAssignTasks()
    {
        return in_array($this->role, ['admin', 'teamlead']);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔍 HELPER METHODS - STATUS CHECKS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Cek apakah user sedang idle
     */
    public function isAvailable()
    {
        return $this->current_task_status === 'idle';
    }

    /**
     * ✅ Cek apakah user sedang bekerja
     */
    public function isWorking()
    {
        return $this->current_task_status === 'working';
    }

    /**
     * ✅ Update status tugas user
     */
    public function updateTaskStatus($status)
    {
        $allowedStatuses = ['idle', 'working', 'busy', 'blocked', 'offline'];

        if (in_array($status, $allowedStatuses)) {
            $this->update(['current_task_status' => $status]);
            return true;
        }

        return false;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // ✅✅ HELPER METHODS - PROJECT ACCESS (TAMBAHAN BARU)
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅✅ Check if user is member of a project
     */
    public function isMemberOf($projectId)
    {
        return $this->projects()->where('projects.id', $projectId)->exists();
    }

    /**
     * ✅✅ Check if user is admin in a project
     */
    public function isAdminOf($projectId)
    {
        return $this->projectMembers()
            ->where('project_id', $projectId)
            ->whereIn('role', ['admin', 'super_admin'])
            ->exists();
    }

    /**
     * ✅✅ Check if user created a project
     */
    public function isCreatorOf($projectId)
    {
        return $this->createdProjects()->where('id', $projectId)->exists();
    }

    /**
     * ✅✅ Check if user has access to a project (member or creator)
     */
    public function hasAccessTo($projectId)
    {
        return $this->isMemberOf($projectId) || $this->isCreatorOf($projectId);
    }

    /**
     * ✅✅ Get all projects where user is admin
     */
    public function adminProjects()
    {
        return $this->projects()
            ->wherePivotIn('role', ['admin', 'super_admin']);
    }

    /**
     * ✅✅ Assign card to this user
     */
    public function assignCard($cardId, $status = 'assigned')
    {
        return CardAssignment::firstOrCreate(
            ['card_id' => $cardId, 'user_id' => $this->id],
            ['assignment_status' => $status]
        );
    }

    /**
     * ✅✅ Remove card assignment from this user
     */
    public function unassignCard($cardId)
    {
        return CardAssignment::where('card_id', $cardId)
            ->where('user_id', $this->id)
            ->delete();
    }

    /**
     * ✅✅ Check if user is assigned to a card
     */
    public function isAssignedTo($cardId)
    {
        return $this->assignments()->where('card_id', $cardId)->exists();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔍 QUERY SCOPES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Scope untuk filter by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * ✅ Scope untuk admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * ✅ Scope untuk teamlead users
     */
    public function scopeTeamLeads($query)
    {
        return $query->where('role', 'teamlead');
    }

    /**
     * ✅ Scope untuk developer users
     */
    public function scopeDevelopers($query)
    {
        return $query->where('role', 'developer');
    }

    /**
     * ✅ Scope untuk designer users
     */
    public function scopeDesigners($query)
    {
        return $query->where('role', 'designer');
    }

    /**
     * ✅✅ Scope untuk developer + designer users (TAMBAHAN BARU)
     */
    public function scopeDevelopersAndDesigners($query)
    {
        return $query->whereIn('role', ['developer', 'designer']);
    }

    /**
     * ✅ Scope untuk idle users
     */
    public function scopeAvailable($query)
    {
        return $query->where('current_task_status', 'idle');
    }

    /**
     * ✅ Scope untuk working users
     */
    public function scopeWorking($query)
    {
        return $query->where('current_task_status', 'working');
    }

    /**
     * ✅ Scope untuk users in specific project
     */
    public function scopeInProject($query, $projectId)
    {
        return $query->whereHas('projects', function($q) use ($projectId) {
            $q->where('projects.id', $projectId);
        });
    }

    /**
     * ✅ Scope untuk users yang online
     */
    public function scopeOnline($query)
    {
        return $query->where('updated_at', '>', now()->subMinutes(5));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // ⚙️ BOOT METHOD
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Boot method untuk event listener
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->current_task_status)) {
                $user->current_task_status = 'idle';
            }
        });
    }
}
