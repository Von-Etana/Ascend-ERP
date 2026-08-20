<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmLeadDripLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'crm_lead_id',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'channel',
        'step',
        'subject_or_action',
        'message_body',
        'status',
        'sent_at',
        'read_at',
        'replied_at',
        'reply_content',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function crmLead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'crm_lead_id');
    }
}
