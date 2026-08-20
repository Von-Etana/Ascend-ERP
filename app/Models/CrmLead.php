<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLead extends Model
{
    protected $fillable = [
        'lead_type',
        'company_name',
        'contact_person',
        'job_title',
        'email',
        'phone',
        'website',
        'city_location',
        'country',
        'system_interest',
        'product_interest',
        'deal_value',
        'monthly_sales_volume',
        'customer_type',
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
