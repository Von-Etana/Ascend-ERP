<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'assignee',
        'due_date',
        'progress_percent',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'progress_percent' => 'integer',
        ];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }
}
