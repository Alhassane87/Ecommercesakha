<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappSession extends Model
{
    protected $fillable = [
        'phone_number',
        'session_id',
        'status',
        'metadata',
        'last_activity_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_activity_at' => 'datetime',
    ];
}
