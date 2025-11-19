<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $table = 'project_members';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'joined_at'
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

    // ✅ Scope untuk mencari member by role (project_members role)
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // ✅ BARU: Scope untuk filter by user role (dari tabel users)
    public function scopeByUserRole($query, $roles)
    {
        return $query->whereHas('user', function($q) use ($roles) {
            $q->whereIn('role', (array) $roles);
        });
    }

    // ✅ BARU: Scope untuk mendapatkan hanya developers
    public function scopeDevelopers($query)
    {
        return $query->whereHas('user', function($q) {
            $q->whereIn('role', ['developer', 'designer']);
        });
    }

    // ✅ BARU: Scope untuk mendapatkan hanya team leads
    public function scopeTeamLeads($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('role', 'teamlead');
        });
    }

    // ✅ Scope untuk member aktif (jika ada kolom status)
    public function scopeActive($query)
    {
        return $query->where('status', 'active'); // Sesuaikan dengan kolom jika ada
    }

    // ✅ Method untuk cek apakah member adalah admin (project role)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // ✅ Method untuk cek apakah member adalah super admin (project role)
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    // ✅ BARU: Method untuk cek apakah user adalah developer (user role)
    public function isDeveloper()
    {
        return $this->user && in_array($this->user->role, ['developer', 'designer']);
    }

    // ✅ BARU: Method untuk cek apakah user adalah team lead (user role)
    public function isTeamLead()
    {
        return $this->user && $this->user->role === 'teamlead';
    }

    // ✅ BARU: Method untuk mendapatkan role user (bukan project role)
    public function getUserRole()
    {
        return $this->user ? $this->user->role : null;
    }

    // ✅ BARU: Accessor untuk mendapatkan role user langsung
    public function getUserRoleAttribute()
    {
        return $this->user ? $this->user->role : null;
    }
}
