<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $primaryKey = 'id';

    /**
     * ✅ FIXED: Enable timestamps (karena tabel ada created_at & updated_at)
     */
    public $timestamps = true;

    protected $fillable = [
        'project_name',
        'description',
        'thumbnail',
        'created_by',
        'deadline',
        'github_link',
        'created_at',
        'updated_at',
        'leader_id',
        'status'
    ];

    /**
     * ✅ Cast otomatis untuk tipe data
     */
    protected $casts = [
        'deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ✅ Appends accessor ke JSON
     */
    protected $appends = ['progress'];

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - CORE
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ FIXED: Project members (via pivot table project_members)
     * Ini yang dipake untuk many-to-many relationship
     */
   public function members()
{
    return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
        ->withPivot('role', 'joined_at') // Pivot columns
        ->withTimestamps() // created_at & updated_at dari pivot
        ->orderBy('project_members.joined_at', 'desc'); // ✅ TAMBAHAN: Order by join date
}
    /**
     * ✅ Project boards (one-to-many)
     */
    public function boards()
    {
        return $this->hasMany(Board::class, 'project_id', 'id');
    }

    /**
     * ✅ Project leader (belongs-to)
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id');
    }

    /**
     * ✅ Project creator (belongs-to)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * ✅ All cards/tasks in this project (through boards)
     */
    public function cards()
    {
        return $this->hasManyThrough(
            Card::class,
            Board::class,
            'project_id',
            'board_id',
            'id',
            'id'
        );
    }

    /**
     * ✅ Alias untuk cards (backward compatibility)
     */
    public function tasks()
    {
        return $this->cards();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔗 RELATIONSHIPS - FILTERED MEMBERS BY ROLE
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Super admin members (role = super_admin)
     */
    public function superAdmins()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->wherePivot('role', 'super_admin')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * ✅ Team leads (role = team_lead or admin)
     */
    public function teamLeads()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->wherePivotIn('role', ['team_lead', 'admin'])
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * ✅ Developers (role = developer)
     */
    public function developers()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->wherePivot('role', 'developer')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * ✅ Designers (role = designer)
     */
    public function designers()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->wherePivot('role', 'designer')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * ✅ Team members (role = developer, designer, or member)
     */
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->wherePivotIn('role', ['developer', 'designer', 'member'])
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🎨 ACCESSORS & COMPUTED ATTRIBUTES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Auto-calculate project progress (0-100)
     */
    public function getProgressAttribute()
    {
        if (!$this->relationLoaded('boards')) {
            $this->load('boards.cards');
        }

        $totalCards = $this->boards->flatMap->cards->count();
        $doneCards = $this->boards->flatMap->cards->where('status', 'done')->count();

        return $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
    }

    /**
     * ✅ Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        return null;
    }

    /**
     * ✅ Get thumbnail with fallback
     */
    public function getThumbnailImageAttribute()
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }

        return null;
    }

    /**
     * ✅ Get active members
     */
    public function getActiveMembersAttribute()
    {
        return $this->members()->whereHas('user', function($query) {
            $query->where('is_active', true);
        })->get();
    }

    /**
     * ✅ Get total tasks count
     */
    public function getTotalTasksAttribute()
    {
        return $this->boards()->withCount('cards')->get()->sum('cards_count');
    }

    /**
     * ✅ Get completed tasks count
     */
    public function getCompletedTasksAttribute()
    {
        if (!$this->relationLoaded('boards')) {
            $this->load('boards.cards');
        }

        return $this->boards->flatMap->cards->where('status', 'done')->count();
    }

    /**
     * ✅ Get todo tasks count
     */
    public function getTodoTasksAttribute()
    {
        if (!$this->relationLoaded('boards')) {
            $this->load('boards.cards');
        }

        return $this->boards->flatMap->cards->where('status', 'todo')->count();
    }

    /**
     * ✅ Get in-progress tasks count
     */
    public function getInProgressTasksAttribute()
    {
        if (!$this->relationLoaded('boards')) {
            $this->load('boards.cards');
        }

        return $this->boards->flatMap->cards->where('status', 'in_progress')->count();
    }

    /**
     * ✅ Get review tasks count
     */
    public function getReviewTasksAttribute()
    {
        if (!$this->relationLoaded('boards')) {
            $this->load('boards.cards');
        }

        return $this->boards->flatMap->cards->where('status', 'review')->count();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔍 QUERY SCOPES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Scope for ongoing projects
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * ✅ Scope for completed projects
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * ✅ Scope for active projects
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['ongoing', 'active']);
    }

    /**
     * ✅ Scope for projects with thumbnail
     */
    public function scopeWithThumbnail($query)
    {
        return $query->whereNotNull('thumbnail');
    }

    /**
     * ✅ Scope for projects created by user
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * ✅ Scope for projects led by user
     */
    public function scopeLedBy($query, $userId)
    {
        return $query->where('leader_id', $userId);
    }

    /**
     * ✅ Scope for projects where user is member
     */
    public function scopeWhereUserIsMember($query, $userId)
    {
        return $query->whereHas('members', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🔧 HELPER METHODS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Check if project is active
     */
    public function isActive()
    {
        return in_array($this->status, ['ongoing', 'active']);
    }

    /**
     * ✅ Check if project is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * ✅ Check if project has thumbnail
     */
    public function hasThumbnail()
    {
        return !empty($this->thumbnail) && Storage::disk('public')->exists($this->thumbnail);
    }

    /**
     * ✅ Delete thumbnail file
     */
    public function deleteThumbnail()
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            Storage::disk('public')->delete($this->thumbnail);
            $this->update(['thumbnail' => null]);
            return true;
        }

        return false;
    }

    /**
     * ✅ Check if user is member of this project
     */
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * ✅ Check if user is leader of this project
     */
    public function isLeader($userId)
    {
        return $this->leader_id == $userId;
    }

    /**
     * ✅ Check if user is creator of this project
     */
    public function isCreator($userId)
    {
        return $this->created_by == $userId;
    }

    /**
     * ✅ Check if user can manage this project
     */
    public function canManage($userId)
    {
        return $this->isLeader($userId) || $this->isCreator($userId);
    }

    /**
     * ✅ Add member to project
     */
    public function addMember($userId, $role = 'developer')
    {
        if ($this->isMember($userId)) {
            return false;
        }

        $this->members()->attach($userId, [
            'role' => $role,
            'joined_at' => now()
        ]);

        return true;
    }

    /**
     * ✅ Remove member from project
     */
    public function removeMember($userId)
    {
        return $this->members()->detach($userId);
    }

    /**
     * ✅ Update member role
     */
    public function updateMemberRole($userId, $role)
    {
        return $this->members()->updateExistingPivot($userId, [
            'role' => $role
        ]);
    }

    /**
     * ✅ Get days until deadline
     */
    public function getDaysUntilDeadline()
    {
        if (!$this->deadline) {
            return null;
        }

        return now()->diffInDays($this->deadline, false);
    }

    /**
     * ✅ Check if deadline is overdue
     */
    public function isOverdue()
    {
        if (!$this->deadline) {
            return false;
        }

        return now()->gt($this->deadline);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // ⚙️ MODEL EVENTS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * ✅ Boot method for model events
     */
    protected static function booted()
    {
        // Auto-delete thumbnail when project is deleted
        static::deleting(function ($project) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
        });

        // Set created_at if not set (when timestamps = false before)
        static::creating(function ($project) {
            if (!$project->created_at) {
                $project->created_at = now();
            }
        });
    }
}
