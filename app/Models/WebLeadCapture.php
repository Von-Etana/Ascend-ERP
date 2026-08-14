<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebLeadCapture extends Model
{
    protected $fillable = [
        'client_name',
        'phone',
        'email',
        'city_location',
        'system_interest',
        'estimated_budget_ngn',
        'source_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'estimated_budget_ngn' => 'float',
        ];
    }
}
