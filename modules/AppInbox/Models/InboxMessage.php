<?php

namespace Modules\AppInbox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AdminUser\Models\User;

class InboxMessage extends Model
{
    protected $table = 'inbox_messages';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['attachments' => 'array', 'metadata' => 'array', 'sent_at' => 'datetime', 'received_at' => 'datetime'];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(InboxConversation::class, 'conversation_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }
}
