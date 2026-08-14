<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryProduct extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'category',
        'unit_price',
        'wholesale_price',
        'cost_price',
        'stock_quantity',
        'reorder_level',
        'location',
        'image_path',
        'is_b2b_visible',
        'specifications',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'reorder_level' => 'integer',
            'is_b2b_visible' => 'boolean',
        ];
    }
}
