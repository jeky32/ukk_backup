<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'card_id',
        'subtask_id',
        'user_id',
        'comment_text',
        'comment_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the card that owns the comment (if card comment)
     */
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    /**
     * Get the subtask that owns the comment (if subtask comment)
     */
    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id');
    }

    /**
     * Get the user who wrote the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Card comments only
     */
    public function scopeCardComments($query)
    {
        return $query->where('comment_type', 'card');
    }

    /**
     * Scope: Subtask comments only
     */
    public function scopeSubtaskComments($query)
    {
        return $query->where('comment_type', 'subtask');
    }
}