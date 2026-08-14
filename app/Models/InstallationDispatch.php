<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallationDispatch extends Model
{
    protected $fillable = [
        'dispatch_number',
        'client_name',
        'client_phone',
        'location_address',
        'system_type',
        'engineer_name',
        'scheduled_date',
        'status',
        'checklist_completed',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'checklist_completed' => 'array',
        ];
    }
}
