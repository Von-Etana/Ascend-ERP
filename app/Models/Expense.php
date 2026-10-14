<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'vendor',
        'amount',
        'requested_by',
        'requester_name',
        'department',
        'payment_method',
        'expense_date',
        'description',
        'attachments',
        'receipt_path',
        'receipt_original_name',
        'approval_status',
        'manager_approval_status',
        'manager_approved_by',
        'manager_approved_at',
        'admin_approval_status',
        'admin_approved_by',
        'admin_approved_at',
        'rejection_reason',
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
            'manager_approved_at' => 'datetime',
            'admin_approved_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }
}
