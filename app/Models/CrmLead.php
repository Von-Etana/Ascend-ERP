<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLead extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'deal_value',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'deal_value' => 'decimal:2',
        ];
    }

    public function deals(): HasMany
    {
        return $this->hasMany(CrmDeal::class);
    }
}
