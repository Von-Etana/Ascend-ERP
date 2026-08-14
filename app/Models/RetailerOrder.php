<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailerOrder extends Model
{
    protected $fillable = [
        'order_number',
        'retailer_user_id',
        'retailer_company_name',
        'retailer_email',
        'retailer_phone',
        'items',
        'subtotal',
        'tax',
        'total_amount',
        'order_type',
        'status',
        'shipping_address',
        'notes',
        'invoice_id',
        'approved_by_user_id',
        'paystack_reference',
        'paystack_status',
        'scanned_items',
        'warehouse_location',
        'dispatched_at',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'scanned_items' => 'array',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'dispatched_at' => 'datetime',
        ];
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(\Modules\AdminUser\Models\User::class, 'retailer_user_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\Modules\AdminUser\Models\User::class, 'approved_by_user_id');
    }
}
