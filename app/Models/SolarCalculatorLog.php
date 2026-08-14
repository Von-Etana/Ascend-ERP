<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarCalculatorLog extends Model
{
    protected $fillable = [
        'client_name',
        'total_wattage',
        'daily_kwh',
        'recommended_inverter',
        'recommended_battery',
        'recommended_panels',
        'estimated_total_ngn',
    ];

    protected function casts(): array
    {
        return [
            'total_wattage' => 'integer',
            'daily_kwh' => 'float',
            'estimated_total_ngn' => 'float',
        ];
    }
}
