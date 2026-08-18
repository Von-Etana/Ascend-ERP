<?php

return [
    'providers' => ['whatsapp', 'instagram', 'messenger', 'telegram', 'email'],
    'webhook_secrets' => [
        'whatsapp' => env('INBOX_WHATSAPP_WEBHOOK_SECRET'),
        'instagram' => env('INBOX_INSTAGRAM_WEBHOOK_SECRET'),
        'messenger' => env('INBOX_MESSENGER_WEBHOOK_SECRET'),
        'telegram' => env('INBOX_TELEGRAM_WEBHOOK_SECRET'),
    ],
    'ai' => [
        'enabled' => (bool) env('INBOX_AI_ENABLED', true),
        'confidence_threshold' => (float) env('INBOX_AI_CONFIDENCE_THRESHOLD', 0.80),
        'handoff_keywords' => ['human', 'agent', 'refund', 'complaint', 'cancel', 'manager'],
    ],
    'whatsapp_bot' => [
        'enabled' => (bool) env('WHATSAPP_BOT_ENABLED', true),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN', env('INBOX_WHATSAPP_VERIFY_TOKEN', 'ascend_webhook_secret')),
        'sales_phone' => env('SALES_TEAM_WHATSAPP_NUMBER', ''),
    ],
];
