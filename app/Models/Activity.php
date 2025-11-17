<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'type',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Scopes
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    // Helper Methods
    public function getIconAttribute()
    {
        return match($this->type) {
            'task_created' => 'fa-plus-circle',
            'task_updated' => 'fa-edit',
            'task_completed' => 'fa-check-circle',
            'task_deleted' => 'fa-trash',
            'comment_added' => 'fa-comment',
            'file_uploaded' => 'fa-file-upload',
            'user_assigned' => 'fa-user-plus',
            default => 'fa-info-circle',
        };
    }

    public function getColorAttribute()
    {
        return match($this->type) {
            'task_created' => 'text-green-500',
            'task_updated' => 'text-blue-500',
            'task_completed' => 'text-green-600',
            'task_deleted' => 'text-red-500',
            'comment_added' => 'text-purple-500',
            'file_uploaded' => 'text-indigo-500',
            'user_assigned' => 'text-teal-500',
            default => 'text-gray-500',
        };
    }
}
