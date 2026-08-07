<?php

return [
    'resend' => [
        'api_key' => env('RESEND_API_KEY'),
        'from_email' => env('RESEND_FROM_EMAIL', 'onboarding@resend.dev'),
        'from_name' => env('RESEND_FROM_NAME', 'Ascend Systems'),
    ],
];
