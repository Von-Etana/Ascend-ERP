<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'vendor',
        'amount',
        'payment_method',
        'expense_date',
        'description',
        'attachments',
        'receipt_path',
        'receipt_original_name',
        'approval_status',
        'approved_by',
        'approved_at',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
            'attachments' => 'array',
        ];
    }
}
