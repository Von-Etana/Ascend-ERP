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
            'items' => 'array',
        ];
    }
}
