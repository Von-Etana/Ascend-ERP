<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AdminUser\Models\User;

class PartnerWarrantyClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_number',
        'user_id',
        'partner_company',
        'contact_person',
        'contact_phone',
        'serial_number',
        'product_sku',
        'product_name',
        'installation_date',
        'fault_description',
        'status',
        'rma_tracking_code',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
