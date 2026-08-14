<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledSocialPost extends Model
{
    protected $fillable = [
        'platform',
        'caption',
        'hashtags',
        'image_url',
        'scheduled_at',
        'status',
        'engagement_likes',
        'engagement_shares',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'engagement_likes' => 'integer',
            'engagement_shares' => 'integer',
        ];
    }
}
