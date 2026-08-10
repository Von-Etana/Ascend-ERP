<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'cashier_name',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'items',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'items' => 'array',
        ];
    }
}
