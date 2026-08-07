<?php

namespace Modules\AppAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AdminUser\Models\User;

class AgentRun extends Model
{
    protected $table = 'agent_runs';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'input' => 'array',
            'context' => 'array',
            'output' => 'array',
            'confidence' => 'decimal:4',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(AgentDefinition::class, 'agent_definition_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
