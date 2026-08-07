<?php

namespace Modules\AppEmail\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResendEmailService
{
    public function configured(): bool
    {
        return filled(config('modules.appemail.resend.api_key'));
    }

    public function send(array $payload, ?string $idempotencyKey = null): array
    {
        abort_unless($this->configured(), 503, 'Resend is not configured.');
        $from = $payload['from'] ?? [config('modules.appemail.resend.from_name').' <'.config('modules.appemail.resend.from_email').'>'];
        $body = array_merge($payload, ['from' => $from]);
        $headers = ['Accept' => 'application/json'];
        $headers['Idempotency-Key'] = $idempotencyKey ?: (string) Str::uuid();

        $response = Http::withToken((string) config('modules.appemail.resend.api_key'))
            ->withHeaders($headers)
            ->timeout(30)
            ->post('https://api.resend.com/emails', $body);

        return ['ok' => $response->successful(), 'status' => $response->status(), 'data' => $response->json() ?: ['body' => $response->body()]];
    }

    public function sendBatch(array $messages, ?string $idempotencyKey = null): array
    {
        abort_unless($this->configured(), 503, 'Resend is not configured.');
        $response = Http::withToken((string) config('modules.appemail.resend.api_key'))
            ->withHeaders(['Accept' => 'application/json', 'Idempotency-Key' => $idempotencyKey ?: (string) Str::uuid()])
            ->timeout(30)
            ->post('https://api.resend.com/emails/batch', $messages);

        return ['ok' => $response->successful(), 'status' => $response->status(), 'data' => $response->json() ?: ['body' => $response->body()]];
    }
}
