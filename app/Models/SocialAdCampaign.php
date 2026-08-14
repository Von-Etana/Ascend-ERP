<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAdCampaign extends Model
{
    protected $fillable = [
        'campaign_name',
        'platform',
        'objective',
        'target_product',
        'budget_ngn',
        'spend_ngn',
        'impressions',
        'clicks',
        'leads_generated',
        'revenue_generated',
        'status',
        'ad_creative_url',
    ];

    protected function casts(): array
    {
        return [
            'budget_ngn' => 'decimal:2',
            'spend_ngn' => 'decimal:2',
            'revenue_generated' => 'decimal:2',
            'impressions' => 'integer',
            'clicks' => 'integer',
            'leads_generated' => 'integer',
        ];
    }

    public function getRoasAttribute(): float
    {
        if ($this->spend_ngn <= 0) {
            return 0.0;
        }
        return round((float)$this->revenue_generated / (float)$this->spend_ngn, 2);
    }
}
