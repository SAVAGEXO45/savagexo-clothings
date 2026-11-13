<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY', 'pk_test_savagexo_sandbox'),
        'secret_key' => env('PAYSTACK_SECRET_KEY', 'sk_test_savagexo_sandbox'),
        'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    ],

    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY', 'FLWPUBK_TEST-savagexo-sandbox'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY', 'FLWSECK_TEST-savagexo-sandbox'),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY', 'FLWSECK_TEST'),
    ],

];
