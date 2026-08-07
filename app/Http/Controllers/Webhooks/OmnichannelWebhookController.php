<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Support\Webhooks\WebhookIdempotency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OmnichannelWebhookController extends Controller
{
    /**
     * Handle incoming social media webhooks (Instagram, Meta, Telegram, WhatsApp, Stripe).
     */
    public function handle(Request $request, string $provider): JsonResponse
    {
        $eventId = (string) ($request->header('X-Event-ID')
            ?? $request->input('id')
            ?? md5((string) $request->getContent()));

        // Idempotent processing
        $outcome = WebhookIdempotency::once($provider, $eventId, function () use ($request, $provider) {
            Log::info("Received [{$provider}] webhook event", [
                'payload' => $request->all(),
            ]);

            return ['status' => 'processed'];
        });

        if (! $outcome['executed']) {
            return response()->json([
                'status' => 'ignored',
                'reason' => 'duplicate_event',
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'provider' => $provider,
        ], 200);
    }
}
