<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeLog extends Model
{
    use HasFactory;

    protected $table = 'time_logs';

    protected $fillable = [
        'card_id',
        'subtask_id',
        'user_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'description',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate duration when end_time is set
        static::saving(function ($timeLog) {
            if ($timeLog->start_time && $timeLog->end_time && !$timeLog->duration_minutes) {
                $start = Carbon::parse($timeLog->start_time);
                $end = Carbon::parse($timeLog->end_time);
                $timeLog->duration_minutes = $end->diffInMinutes($start);
            }
        });

        // Update card/subtask actual hours after save
        static::saved(function ($timeLog) {
            if ($timeLog->card) {
                $timeLog->card->updateActualHours();
            }
            if ($timeLog->subtask) {
                $timeLog->subtask->updateActualHours();
            }
        });
    }

    /**
     * Get the card that owns the time log
     */
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    /**
     * Get the subtask that owns the time log (if applicable)
     */
    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id');
    }

    /**
     * Get the user who logged the time
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Active time logs (not ended yet)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('end_time');
    }

    /**
     * Scope: Completed time logs
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    /**
     * Scope: For specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Today's time logs
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    /**
     * Scope: This week's time logs
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute()
    {
        if (!$this->duration_minutes) {
            return 0;
        }

        return round($this->duration_minutes / 60, 2);
    }

    /**
     * Get formatted duration (e.g., "2h 30m")
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return '0m';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /**
     * Stop the timer
     */
    public function stop()
    {
        if (!$this->end_time) {
            $this->update(['end_time' => now()]);
        }

        return $this;
    }

    /**
     * Check if timer is running
     */
    public function getIsRunningAttribute()
    {
        return !$this->end_time;
    }

    /**
     * Get elapsed time for running timer
     */
    public function getElapsedTimeAttribute()
    {
        if ($this->end_time) {
            return $this->formatted_duration;
        }

        $minutes = Carbon::parse($this->start_time)->diffInMinutes(now());
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }

        return "{$mins}m";
    }
}