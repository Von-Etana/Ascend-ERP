<?php

namespace Modules\AppInbox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\AdminUser\Models\User;
use Modules\AppChannels\Models\SocialAccount;

class InboxConversation extends Model
{
    protected $table = 'inbox_conversations';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'last_message_at' => 'datetime'];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(InboxMessage::class, 'conversation_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(InboxParticipant::class, 'conversation_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(InboxAssignment::class, 'conversation_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(InboxTag::class, 'inbox_conversation_tag', 'conversation_id', 'tag_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
