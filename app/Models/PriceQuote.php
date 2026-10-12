<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceQuote extends Model
{
    protected $fillable = [
        'quote_number',
        'client_name',
        'email',
        'phone',
        'address',
        'valid_until',
        'subtotal',
        'discount_amount',
        'tax',
        'total',
        'status',
        'notes',
        'items',
        'crm_deal_id',
        'crm_lead_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'items' => 'array',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(CrmDeal::class, 'crm_deal_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'crm_lead_id');
    }
}
