<?php

namespace Modules\AppAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentDefinition extends Model
{
    protected $table = 'agent_definitions';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tool_keys' => 'array',
            'output_schema' => 'array',
            'policy' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function runs(): HasMany
    {
        return $this->hasMany(AgentRun::class, 'agent_definition_id');
    }
}
