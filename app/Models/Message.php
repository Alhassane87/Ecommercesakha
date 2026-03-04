<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'chatbot_messages';
    
    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'metadata',
        'is_ai',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_ai' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
