<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card2 extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'board_id','card_title','description','position',
        'created_by','due_date','status','priority',
        'estimated_hours','actual_hours'
    ];

    public function assignments()
    {
        return $this->hasMany(CardAssignment::class, 'card_id');
    }

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function subtasks()
    {
       return $this->hasMany(Subtask::class, 'card_id', 'id');
    }

}

