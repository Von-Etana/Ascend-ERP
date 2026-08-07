<?php

namespace Modules\AppAgents\Services;

use Illuminate\Support\Facades\Schema;
use Modules\AdminAI\Services\AiGateway;
use Modules\AppAgents\Models\AgentRun;
use RuntimeException;
use Throwable;

class AgentRunner
{
    public function __construct(
        protected AgentRegistry $registry,
        protected AiGateway $ai,
    ) {}

    public function run(string $agentKey, array $input, array $context = []): array
    {
        $definition = $this->registry->definition($agentKey);

        if (! $definition) {
            throw new RuntimeException(__('The requested AI agent is not available.'));
        }

        $run = $this->createRun($agentKey, [
            'agent_definition_id' => $definition->id,
            'user_id' => $context['user_id'] ?? auth()->id(),
            'team_id' => $context['team_id'] ?? null,
            'source_type' => $context['source_type'] ?? null,
            'source_id' => $context['source_id'] ?? null,
            'input' => $input,
            'context' => $context,
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $result = $this->ai->generateStructured(
                prompt: $this->prompt($definition->system_prompt, $definition->output_schema ?? [], $input, $context),
                schema: $definition->output_schema ?? [],
                options: [
                    'capability' => 'agent',
                    'feature' => 'agent.'.$agentKey,
                    'temperature' => (float) data_get($definition->policy, 'temperature', 0.25),
                    'system' => $definition->system_prompt,
                ],
            );

            $output = $this->normalizeOutput((array) $result['data']);

            $this->finishRun($run, [
                'status' => 'success',
                'output' => $output,
                'confidence' => $output['confidence'] ?? null,
                'handoff_reason' => $output['handoff_reason'] ?? null,
                'finished_at' => now(),
            ]);

            return [
                'run_id' => $run?->id,
                'agent_key' => $agentKey,
                'definition' => $definition,
                'output' => $output,
                'raw' => $result,
            ];
        } catch (Throwable $exception) {
            $this->finishRun($run, [
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'finished_at' => now(),
            ]);

            throw $exception;
        }
    }

    protected function prompt(string $systemPrompt, array $schema, array $input, array $context): string
    {
        return trim(implode("\n\n", [
            'Agent instructions:',
            $systemPrompt,
            'Required JSON shape:',
            json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'Input:',
            json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'Context:',
            json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]));
    }

    protected function normalizeOutput(array $output): array
    {
        $action = in_array(($output['action'] ?? ''), ['auto_reply', 'draft', 'handoff'], true)
            ? (string) $output['action']
            : 'draft';

        $confidence = max(0, min(1, (float) ($output['confidence'] ?? 0)));

        return [
            'action' => $action,
            'reply' => trim((string) ($output['reply'] ?? '')),
            'confidence' => $confidence,
            'reasoning' => trim((string) ($output['reasoning'] ?? '')),
            'handoff_reason' => trim((string) ($output['handoff_reason'] ?? '')) ?: null,
            'tags' => collect((array) ($output['tags'] ?? []))->map(fn ($tag) => trim((string) $tag))->filter()->values()->all(),
        ];
    }

    protected function createRun(string $agentKey, array $attributes): ?AgentRun
    {
        try {
            if (! Schema::hasTable('agent_runs')) {
                return null;
            }
        } catch (Throwable) {
            return null;
        }

        return AgentRun::query()->create($attributes + ['agent_key' => $agentKey]);
    }

    protected function finishRun(?AgentRun $run, array $attributes): void
    {
        if (! $run) {
            return;
        }

        $run->forceFill($attributes)->save();
    }
}
