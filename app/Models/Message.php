<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sender of the message
     */

        // Relationship dengan User (pengirim)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship dengan User (penerima)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scope untuk mendapatkan pesan antara 2 user
    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)
              ->where('receiver_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId2)
              ->where('receiver_id', $userId1);
        })->orderBy('created_at', 'asc');
    }

    // Scope untuk pesan yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get the receiver of the message
     */

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Get formatted time
     */
    public function getFormattedTimeAttribute()
    {
        $diff = $this->created_at->diffInMinutes(now());

        if ($diff < 1) {
            return 'Baru saja';
        } elseif ($diff < 60) {
            return $diff . ' menit lalu';
        } elseif ($diff < 1440) {
            return $this->created_at->diffInHours(now()) . ' jam lalu';
        } elseif ($diff < 2880) {
            return 'Kemarin';
        } else {
            return $this->created_at->format('d M Y');
        }
    }
}
