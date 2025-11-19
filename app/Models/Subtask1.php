<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask1 extends Model
{
    protected $primaryKey = 'subtask_id';
    public $timestamps = false;

    protected $fillable = [
        'card_id','subtask_title','description','status',
        'estimated_hours','actual_hours','position','created_at'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'card_id');
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'subtask_id', 'subtask_id');
    }
}
