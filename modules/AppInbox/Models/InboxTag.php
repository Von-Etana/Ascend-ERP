<?php

namespace Modules\AppInbox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InboxTag extends Model
{
    protected $table = 'inbox_tags';

    protected $guarded = [];

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(InboxConversation::class, 'inbox_conversation_tag', 'tag_id', 'conversation_id');
    }
}
