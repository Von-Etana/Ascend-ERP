<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_name',
        'issue_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'paid_amount',
        'status',
        'notes',
        'items',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'items' => 'array',
        ];
    }

    public function getBalanceDueAttribute(): float
    {
        return max(0.00, (float) $this->total - (float) $this->paid_amount);
    }
}
