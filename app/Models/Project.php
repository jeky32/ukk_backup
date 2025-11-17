<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'project_name',
        'description',
        'thumbnail',      // ✅ TAMBAH INI untuk gambar project
        'created_by',
        'deadline',
        'github_link', 
        'created_at',
        'leader_id',      // ✅ Uncomment untuk project leader
        'status'          // ✅ Uncomment untuk status project
    ];

    // ✅ Cast otomatis untuk tipe data
    protected $casts = [
        'deadline' => 'date',
    ];

    // ✅ Appends accessor ke JSON
    protected $appends = ['progress'];

    // ✅ Relationship dengan members
    public function members()
    {
        return $this->hasMany(ProjectMember::class, 'project_id', 'id');
    }

    // ✅ Relationship dengan boards
    public function boards()
    {
        return $this->hasMany(Board::class, 'project_id', 'id');
    }

    // ✅ Relationship dengan leader (user yang memimpin project)
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id');
    }

    // ✅ Relationship dengan creator (user yang membuat project)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // 🔹 Relasi ke tabel pivot project_members
    public function members2()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    // 🔹 Hanya ambil member dengan role 'super_admin' → creator (melalui pivot)
    public function superAdmin()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->wherePivot('role', 'super_admin');
    }

    // 🔹 Hanya ambil member dengan role 'admin' → teamlead
    public function teamLeads()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->wherePivot('role', 'admin');
    }

    // 🔹 Hanya ambil member dengan role 'member' → developer/designer
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->wherePivot('role', 'member');
    }

    // ✅ Relationship dengan tasks melalui boards
    public function tasks()
    {
        return $this->hasManyThrough(
            Card::class,        // Model akhir (tasks/cards)
            Board::class,       // Model perantara
            'project_id',       // Foreign key di boards table
            'board_id',         // Foreign key di cards table
            'id',               // Local key di projects table
            'id'                // Local key di boards table
        );
    }

    // ✅ Accessor untuk progress otomatis
    public function getProgressAttribute()
    {
        if (!$this->relationLoaded('boards')) {
            $this->load('boards.cards');
        }

        $totalCards = $this->boards->flatMap->cards->count();
        $doneCards = $this->boards->flatMap->cards->where('status', 'done')->count();

        return $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
    }

    // ✅ Accessor untuk thumbnail URL
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        return null; // atau return default image
    }

    // ✅ Accessor untuk thumbnail lengkap dengan fallback
    public function getThumbnailImageAttribute()
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }

        // Fallback: return gradient atau default image
        return null;
    }

    // ✅ Scope untuk project ongoing
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    // ✅ Scope untuk project completed
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ✅ Scope untuk project dengan thumbnail
    public function scopeWithThumbnail($query)
    {
        return $query->whereNotNull('thumbnail');
    }

    // ✅ Method untuk cek apakah project aktif
    public function isActive()
    {
        return $this->status === 'ongoing';
    }

    // ✅ Method untuk cek apakah project completed
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // ✅ Method untuk cek apakah ada thumbnail
    public function hasThumbnail()
    {
        return !empty($this->thumbnail) && Storage::disk('public')->exists($this->thumbnail);
    }

    // ✅ Method untuk hapus thumbnail
    public function deleteThumbnail()
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            Storage::disk('public')->delete($this->thumbnail);
            $this->update(['thumbnail' => null]);
            return true;
        }

        return false;
    }

    // ✅ Event ketika model dihapus (hapus thumbnail juga)
    protected static function booted()
    {
        static::deleting(function ($project) {
            // Hapus thumbnail saat project dihapus
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
        });
    }

    // ✅ Method untuk get all active members
    public function getActiveMembersAttribute()
    {
        return $this->members()->whereHas('user', function($query) {
            $query->where('is_active', true); // jika ada field is_active di users
        })->get();
    }

    // ✅ Method untuk count total tasks
    public function getTotalTasksAttribute()
    {
        return $this->boards()->withCount('cards')->get()->sum('cards_count');
    }

    // ✅ Method untuk count completed tasks
    public function getCompletedTasksAttribute()
    {
        return $this->boards->flatMap->cards->where('status', 'done')->count();
    }

    // ✅ Method untuk cek apakah user adalah member
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    // ✅ Method untuk cek apakah user adalah leader
    public function isLeader($userId)
    {
        return $this->leader_id == $userId;
    }

    // ✅ Method untuk cek apakah user adalah creator
    public function isCreator($userId)
    {
        return $this->created_by == $userId;
    }
}
