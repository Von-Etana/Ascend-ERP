<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmDeal extends Model
{
    protected $fillable = [
        'crm_lead_id',
        'deal_name',
        'stage',
        'value',
        'expected_close',
    ];

    protected function casts(): array
    {
        return [
            'expected_close' => 'date',
            'value' => 'decimal:2',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'crm_lead_id');
    }
}
