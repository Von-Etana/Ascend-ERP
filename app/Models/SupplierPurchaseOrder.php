<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_name',
        'supplier_email',
        'supplier_country',
        'destination_warehouse',
        'items',
        'subtotal_usd',
        'subtotal_ngn',
        'exchange_rate',
        'status',
        'payment_terms',
        'shipping_method',
        'expected_delivery_date',
        'sent_to_supplier_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal_usd' => 'decimal:2',
        'subtotal_ngn' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'expected_delivery_date' => 'date',
        'sent_to_supplier_at' => 'datetime',
        'received_at' => 'datetime',
    ];
}
