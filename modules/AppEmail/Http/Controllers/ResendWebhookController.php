<?php

namespace Modules\AppEmail\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResendWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->all();
        $type = (string) data_get($payload, 'type', 'unknown');
        $emailId = (string) data_get($payload, 'data.email_id', data_get($payload, 'data.id', ''));
        $recipient = (string) data_get($payload, 'data.to.0', data_get($payload, 'data.to', ''));
        $eventMap = ['email.delivered' => 'delivered', 'email.opened' => 'opened', 'email.clicked' => 'clicked', 'email.bounced' => 'bounced', 'email.complained' => 'complained', 'email.suppressed' => 'suppressed'];

        DB::table('email_events')->insert(['resend_email_id' => $emailId ?: null, 'event' => $eventMap[$type] ?? $type, 'payload' => json_encode($payload), 'occurred_at' => now(), 'created_at' => now(), 'updated_at' => now()]);

        if ($emailId !== '') {
            $status = $eventMap[$type] ?? $type;
            $update = ['status' => $status, 'updated_at' => now()];
            if (in_array($status, ['delivered', 'opened', 'clicked', 'bounced', 'unsubscribed'], true)) {
                $update[$status.'_at'] = now();
            }
            DB::table('email_campaign_recipients')->where('resend_email_id', $emailId)->update($update);
        } elseif ($recipient !== '') {
            DB::table('email_campaign_recipients')->where('email', $recipient)->update(['status' => $eventMap[$type] ?? $type, 'updated_at' => now()]);
        }

        return response()->json(['received' => true]);
    }
}
