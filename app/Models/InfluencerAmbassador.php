<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfluencerAmbassador extends Model
{
    protected $fillable = [
        'name',
        'handle',
        'platform',
        'referral_code',
        'leads_count',
        'sales_attributed_ngn',
        'commission_earned_ngn',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'leads_count' => 'integer',
            'sales_attributed_ngn' => 'float',
            'commission_earned_ngn' => 'float',
        ];
    }
}
