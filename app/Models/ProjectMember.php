<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $table = 'project_members';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'project_id', 'user_id', 'role'
        , 'joined_at'
    ];

    // ✅ PERBAIKI: Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
        // 'user_id' = foreign key di tabel project_members
        // 'id' = primary key di tabel users (bukan 'user_id')
    }

    // ✅ Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
        // 'project_id' = foreign key di tabel project_members
        // 'project_id' = primary key di tabel projects
    }

    // ✅ Accessor untuk mendapatkan nama user
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->username : 'Unknown User';
    }

    // ✅ Accessor untuk mendapatkan nama lengkap user
    public function getUserFullNameAttribute()
    {
        return $this->user ? ($this->user->full_name ?? $this->user->username) : 'Unknown User';
    }

    // ✅ Scope untuk mencari member by role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // ✅ Scope untuk member aktif (jika ada kolom status)
    public function scopeActive($query)
    {
        return $query->where('status', 'active'); // Sesuaikan dengan kolom jika ada
    }

    // ✅ Method untuk cek apakah member adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // ✅ Method untuk cek apakah member adalah developer
    public function isDeveloper()
    {
        return $this->role === 'developer';
    }

    // ✅ Method untuk cek apakah member adalah designer
    public function isDesigner()
    {
        return $this->role === 'designer';
    }
}
