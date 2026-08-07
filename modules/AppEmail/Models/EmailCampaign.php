<?php

namespace Modules\AppEmail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailCampaign extends Model
{
    protected $table = 'email_campaigns';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['audience' => 'array', 'scheduled_at' => 'datetime', 'sent_at' => 'datetime'];
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(EmailCampaignRecipient::class, 'campaign_id');
    }
}
