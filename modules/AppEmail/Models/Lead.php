<?php

namespace Modules\AppEmail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AdminUser\Models\User;

class Lead extends Model
{
    protected $table = 'ascend_leads';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'last_contacted_at' => 'datetime'];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
