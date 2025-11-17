<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'cards';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'board_id',
        'card_title',
        'description',
        'position',
        'created_by',
        'due_date',
        'status',
        'priority',
        'estimated_hours',
        'actual_hours',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'due_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'status' => 'todo',
        'priority' => 'medium',
        'position' => 0,
        'actual_hours' => 0.00,
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set position when creating new card
        static::creating(function ($card) {
            if (!$card->position) {
                $maxPosition = static::where('board_id', $card->board_id)
                    ->where('status', $card->status)
                    ->max('position');
                $card->position = ($maxPosition ?? 0) + 1;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the board that owns the card
     */
    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    /**
     * Get the user who created the card
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
	{
		return $this->belongsToMany(User::class, 'project_members')
			->withPivot('role')
			->withTimestamps();
	}

    /**
     * Get the assigned members for the card (through card_assignments)
     */
    public function assignments()
    {
        return $this->hasMany(CardAssignment::class, 'card_id');
    }

    /**
     * Get the assigned members (users) for the card
     */
    public function assignedMembers()
    {
        return $this->belongsToMany(User::class, 'card_assignments', 'card_id', 'user_id')
                    ->withPivot('assignment_status', 'started_at', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Get the comments for the card
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'card_id')
                    ->where('comment_type', 'card')
                    ->latest();
    }

    /**
     * Get the subtasks for the card
     */
    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'card_id')->orderBy('position');
    }

    /**
     * Get the time logs for the card
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'card_id')->latest();
    }

    public function assignedUsers()
	{
		return $this->belongsToMany(User::class, 'card_assignments')
			->withPivot('assignment_status')
			->withTimestamps();
	}
    
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by priority
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Overdue cards
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'done');
    }

    /**
     * Scope: Due soon (within next 3 days)
     */
    public function scopeDueSoon($query)
    {
        return $query->whereNotNull('due_date')
                    ->whereBetween('due_date', [now(), now()->addDays(3)])
                    ->where('status', '!=', 'done');
    }

    /**
     * Scope: Assigned to specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->whereHas('assignedMembers', function($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Scope: Order by position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Get the card title (accessor for consistency)
     */
    public function getTitleAttribute()
    {
        return $this->card_title;
    }

    /**
     * Get the status label with color
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'todo' => ['label' => 'To Do', 'color' => 'blue'],
            'in_progress' => ['label' => 'In Progress', 'color' => 'yellow'],
            'review' => ['label' => 'Review', 'color' => 'purple'],
            'done' => ['label' => 'Done', 'color' => 'green'],
        ];

        return $statusLabels[$this->status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    /**
     * Get the priority label with color
     */
    public function getPriorityLabelAttribute()
    {
        $priorityLabels = [
            'low' => ['label' => 'Low', 'color' => 'green'],
            'medium' => ['label' => 'Medium', 'color' => 'yellow'],
            'high' => ['label' => 'High', 'color' => 'red'],
        ];

        return $priorityLabels[$this->priority] ?? ['label' => 'Medium', 'color' => 'yellow'];
    }

    /**
     * Check if card is overdue
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->due_date || $this->status === 'done') {
            return false;
        }

        return $this->due_date->isPast();
    }

    /**
     * Check if card is due soon
     */
    public function getIsDueSoonAttribute()
    {
        if (!$this->due_date || $this->status === 'done') {
            return false;
        }

        return $this->due_date->isFuture() &&
               $this->due_date->diffInDays(now()) <= 3;
    }

        public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    /**
     * Get days until due date
     */
    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Get subtasks progress percentage
     */
    public function getSubtasksProgressAttribute()
    {
        $total = $this->subtasks->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $this->subtasks->where('status', 'done')->count();

        return round(($completed / $total) * 100);
    }

    /**
     * Get completed subtasks count
     */
    public function getCompletedSubtasksCountAttribute()
    {
        return $this->subtasks->where('status', 'done')->count();
    }

    /**
     * Get total subtasks count
     */
    public function getTotalSubtasksCountAttribute()
    {
        return $this->subtasks->count();
    }

    /**
     * Get comments count
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
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
     * Get hours remaining
     */
    public function getHoursRemainingAttribute()
    {
        if (!$this->estimated_hours) {
            return null;
        }

        return max(0, $this->estimated_hours - $this->actual_hours);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Move card to different status
     */
    public function moveToStatus($newStatus, $newPosition = null)
    {
        // Reset positions in old status
        static::where('board_id', $this->board_id)
            ->where('status', $this->status)
            ->where('position', '>', $this->position)
            ->decrement('position');

        // Set new position
        if ($newPosition === null) {
            $newPosition = static::where('board_id', $this->board_id)
                ->where('status', $newStatus)
                ->max('position') + 1;
        }

        // Update card
        $this->update([
            'status' => $newStatus,
            'position' => $newPosition,
        ]);

        return $this;
    }

    /**
     * Update position within same status
     */
    public function updatePosition($newPosition)
    {
        $oldPosition = $this->position;

        if ($oldPosition === $newPosition) {
            return $this;
        }

        if ($newPosition < $oldPosition) {
            // Moving up
            static::where('board_id', $this->board_id)
                ->where('status', $this->status)
                ->whereBetween('position', [$newPosition, $oldPosition - 1])
                ->increment('position');
        } else {
            // Moving down
            static::where('board_id', $this->board_id)
                ->where('status', $this->status)
                ->whereBetween('position', [$oldPosition + 1, $newPosition])
                ->decrement('position');
        }

        $this->update(['position' => $newPosition]);

        return $this;
    }

    /**
     * Assign user to card
     */
    public function assignUser($userId, $status = 'assigned')
    {
        return $this->assignedMembers()->attach($userId, [
            'assignment_status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Start work on card (for assigned user)
     */
    public function startWork($userId)
    {
        return CardAssignment::where('card_id', $this->id)
            ->where('user_id', $userId)
            ->update([
                'assignment_status' => 'in_progress',
                'started_at' => now(),
            ]);
    }

    /**
     * Complete work on card (for assigned user)
     */
    public function completeWork($userId)
    {
        return CardAssignment::where('card_id', $this->id)
            ->where('user_id', $userId)
            ->update([
                'assignment_status' => 'completed',
                'completed_at' => now(),
            ]);
    }

    /**
     * Add comment to card
     */
    public function addComment($userId, $commentText)
    {
        return $this->comments()->create([
            'user_id' => $userId,
            'comment_text' => $commentText,
            'comment_type' => 'card',
        ]);
    }

    /**
     * Add subtask to card
     */
    public function addSubtask($title, $description = null, $estimatedHours = null)
    {
        $maxPosition = $this->subtasks()->max('position') ?? 0;

        return $this->subtasks()->create([
            'subtask_title' => $title,
            'description' => $description,
            'estimated_hours' => $estimatedHours,
            'position' => $maxPosition + 1,
            'status' => 'todo',
        ]);
    }

    /**
     * Log time for card
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

    /**
     * Duplicate card
     */
    public function duplicate()
    {
        $newCard = $this->replicate();
        $newCard->card_title = $this->card_title . ' (Copy)';
        $newCard->position = static::where('board_id', $this->board_id)
            ->where('status', $this->status)
            ->max('position') + 1;
        $newCard->save();

        // Copy assigned members
        $newCard->assignedMembers()->sync($this->assignedMembers->pluck('id'));

        // Copy subtasks
        foreach ($this->subtasks as $subtask) {
            $newSubtask = $subtask->replicate();
            $newSubtask->card_id = $newCard->id;
            $newSubtask->save();
        }

        return $newCard;
    }
}
