<?php

namespace Modules\AppEmail\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaignRecipient extends Model
{
    protected $table = 'email_campaign_recipients';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'delivered_at' => 'datetime', 'opened_at' => 'datetime', 'clicked_at' => 'datetime', 'bounced_at' => 'datetime', 'unsubscribed_at' => 'datetime'];
    }
}
