<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'user_id', 'school_name', 'question_ids', 'status',
        'current_question', 'time_remaining',
        'started_at', 'paused_at',
        'last_heartbeat', 'paused_by', 'pause_message',
    ];

    protected $casts = [
        'started_at'     => 'datetime',
        'paused_at'      => 'datetime',
        'last_heartbeat' => 'datetime',
        'question_ids'   => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOnline(): bool
    {
        return $this->last_heartbeat
            && $this->last_heartbeat->diffInSeconds(now()) <= 15;
    }
}
