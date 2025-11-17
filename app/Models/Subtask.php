<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $table = 'subtasks';

    protected $fillable = [
        'card_id',
        'subtask_title',
        'description',
        'status',
        'estimated_hours',
        'actual_hours',
        'position',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'todo',
        'actual_hours' => 0.00,
        'position' => 0,
    ];

    /**
     * Get the card that owns the subtask
     */
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    /**
     * Get the comments for the subtask
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'subtask_id')
                    ->where('comment_type', 'subtask')
                    ->latest();
    }

    /**
     * Get the time logs for the subtask
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'subtask_id')->latest();
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Completed subtasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'done');
    }

    /**
     * Scope: Order by position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }

    /**
     * Get the status label with color
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'todo' => ['label' => 'To Do', 'color' => 'blue'],
            'in_progress' => ['label' => 'In Progress', 'color' => 'yellow'],
            'done' => ['label' => 'Done', 'color' => 'green'],
        ];

        return $statusLabels[$this->status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    /**
     * Get total hours worked (from time logs)
     */
    public function getTotalHoursWorkedAttribute()
    {
        $totalMinutes = $this->timeLogs()
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes');
            
        return round($totalMinutes / 60, 2);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'done']);
        return $this;
    }

    /**
     * Mark as in progress
     */
    public function markAsInProgress()
    {
        $this->update(['status' => 'in_progress']);
        return $this;
    }

    /**
     * Add comment to subtask
     */
    public function addComment($userId, $commentText)
    {
        return $this->comments()->create([
            'user_id' => $userId,
            'comment_text' => $commentText,
            'comment_type' => 'subtask',
        ]);
    }

    /**
     * Log time for subtask
     */
    public function logTime($userId, $startTime, $endTime = null, $description = null)
    {
        $durationMinutes = null;
        
        if ($endTime) {
            $start = \Carbon\Carbon::parse($startTime);
            $end = \Carbon\Carbon::parse($endTime);
            $durationMinutes = $end->diffInMinutes($start);
        }

        return $this->timeLogs()->create([
            'card_id' => $this->card_id,
            'user_id' => $userId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
            'description' => $description,
        ]);
    }

    /**
     * Update actual hours from time logs
     */
    public function updateActualHours()
    {
        $totalMinutes = $this->timeLogs()
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes');
            
        $hours = round($totalMinutes / 60, 2);
        
        $this->update(['actual_hours' => $hours]);
        
        return $this;
    }
}