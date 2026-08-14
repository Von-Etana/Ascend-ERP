<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantySerial extends Model
{
    protected $fillable = [
        'serial_number',
        'product_name',
        'sku',
        'client_name',
        'client_phone',
        'purchase_date',
        'warranty_expiry_date',
        'status',
        'maintenance_alerts_sent',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'datetime',
            'warranty_expiry_date' => 'datetime',
            'maintenance_alerts_sent' => 'integer',
        ];
    }
}
