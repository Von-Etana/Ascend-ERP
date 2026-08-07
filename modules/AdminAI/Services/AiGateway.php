<?php

namespace Modules\AdminAI\Services;

use Illuminate\Support\Facades\Http;
use Modules\AdminSettings\Support\OptionStore;
use RuntimeException;
use Throwable;

class AiGateway
{
    public function __construct(
        protected OptionStore $options,
    ) {}

    public function generateText(string $prompt, array $options = []): array
    {
        return $this->generate($prompt, $options + ['structured' => false]);
    }

    public function generateStructured(string $prompt, array $schema = [], array $options = []): array
    {
        $result = $this->generate($prompt, $options + ['structured' => true, 'schema' => $schema]);
        $decoded = json_decode($result['text'], true);

        if (! is_array($decoded)) {
            throw new RuntimeException(__('AI returned invalid structured output.'));
        }

        return $result + ['data' => $decoded];
    }

    protected function generate(string $prompt, array $options): array
    {
        $capability = (string) ($options['capability'] ?? 'text');
        $feature = (string) ($options['feature'] ?? 'ai.gateway.generate');
        $provider = (string) ($options['provider'] ?? $this->providerFor($capability));
        $model = (string) ($options['model'] ?? $this->modelFor($capability));
        $startedAt = microtime(true);

        try {
            $result = match ($provider) {
                'openai' => $this->generateWithOpenAi($prompt, $model, $options),
                'gemini' => $this->generateWithGemini($prompt, $model, $options),
                default => throw new RuntimeException(__('The selected AI provider is not supported by the shared gateway yet.')),
            };

            $this->logUsage($provider, $model, $capability, $feature, 'success', $startedAt, $result);

            return $result + [
                'provider' => $provider,
                'model' => $model,
            ];
        } catch (Throwable $exception) {
            $this->logUsage($provider, $model, $capability, $feature, 'error', $startedAt, [
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    protected function generateWithOpenAi(string $prompt, string $model, array $options): array
    {
        $apiKey = trim((string) $this->options->get('ai_openai_api_key', ''));
        $baseUrl = rtrim((string) $this->options->get('ai_openai_url', 'https://api.openai.com/v1'), '/');

        if ($apiKey === '') {
            throw new RuntimeException(__('OpenAI API key is missing.'));
        }

        $payload = [
            'model' => $model,
            'temperature' => (float) ($options['temperature'] ?? 0.4),
            'messages' => [
                ['role' => 'system', 'content' => (string) ($options['system'] ?? 'You are a careful business assistant for Ascend Systems. Follow instructions exactly.')],
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        if (! empty($options['structured'])) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::timeout((int) ($options['timeout'] ?? 90))
            ->withToken($apiKey)
            ->acceptJson()
            ->post($baseUrl.'/chat/completions', $payload);

        if (! $response->successful()) {
            throw new RuntimeException((string) data_get($response->json(), 'error.message', __('AI generation request failed.')));
        }

        return [
            'text' => trim((string) data_get($response->json(), 'choices.0.message.content', '')),
            'usage' => (array) data_get($response->json(), 'usage', []),
            'raw' => $response->json(),
        ];
    }

    protected function generateWithGemini(string $prompt, string $model, array $options): array
    {
        $apiKey = trim((string) $this->options->get('ai_gemini_api_key', ''));

        if ($apiKey === '') {
            throw new RuntimeException(__('Gemini API key is missing.'));
        }

        $response = Http::timeout((int) ($options['timeout'] ?? 90))
            ->acceptJson()
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [[
                    'parts' => [['text' => $prompt]],
                ]],
                'generationConfig' => [
                    'temperature' => (float) ($options['temperature'] ?? 0.4),
                    'responseMimeType' => ! empty($options['structured']) ? 'application/json' : 'text/plain',
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(__('AI generation request failed.'));
        }

        return [
            'text' => trim(collect((array) data_get($response->json(), 'candidates.0.content.parts', []))->pluck('text')->filter()->implode("\n")),
            'usage' => (array) data_get($response->json(), 'usageMetadata', []),
            'raw' => $response->json(),
        ];
    }

    protected function providerFor(string $capability): string
    {
        return match ($capability) {
            'content' => (string) $this->options->get('ai_content_provider', 'openai'),
            'chat', 'agent' => (string) $this->options->get('ai_chat_provider', 'openai'),
            default => (string) $this->options->get('ai_text_provider', 'openai'),
        };
    }

    protected function modelFor(string $capability): string
    {
        return match ($capability) {
            'content' => (string) $this->options->get('ai_content_model', 'gpt-5.4'),
            'chat', 'agent' => (string) $this->options->get('ai_chat_model', 'gpt-5.4'),
            default => (string) $this->options->get('ai_text_generation_model', 'gpt-5.4'),
        };
    }

    protected function logUsage(string $provider, string $model, string $capability, string $feature, string $status, float $startedAt, array $result): void
    {
        if (! function_exists('log_ai_usage')) {
            return;
        }

        $usage = (array) ($result['usage'] ?? []);

        log_ai_usage([
            'provider' => $provider,
            'capability' => $capability,
            'model' => $model,
            'feature' => $feature,
            'status' => $status,
            'latency_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            'prompt_tokens' => $usage['prompt_tokens'] ?? $usage['promptTokenCount'] ?? null,
            'completion_tokens' => $usage['completion_tokens'] ?? $usage['candidatesTokenCount'] ?? null,
            'total_tokens' => $usage['total_tokens'] ?? $usage['totalTokenCount'] ?? null,
            'error_message' => $result['error_message'] ?? null,
            'metadata' => ['source' => 'ai_gateway'],
        ]);
    }
}
