<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLead extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'city_location',
        'system_interest',
        'deal_value',
        'ai_lead_score',
        'purchasing_timeline',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'deal_value' => 'decimal:2',
            'ai_lead_score' => 'integer',
        ];
    }

    public function deals(): HasMany
    {
        return $this->hasMany(CrmDeal::class);
    }
}
