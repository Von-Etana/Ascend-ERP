<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialInboxMessage extends Model
{
    protected $fillable = [
        'sender_name',
        'sender_handle',
        'channel',
        'message_body',
        'ai_suggested_reply',
        'replied_text',
        'is_read',
        'is_replied',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_replied' => 'boolean',
            'received_at' => 'datetime',
        ];
    }
}
