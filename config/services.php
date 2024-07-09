<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'amocrm'=> [
        'token' => env('AMOCRM_LONG_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjAwNDcyYTEyMGVjNTk2NDI3N2RlYjBmYmFjZTU2NGI1ZDM1NGNlZDZmNzUyYzUyZjIwNTA3YTliODk1ZDgxOGEyMGNjZmI3ZmJmZWI1N2FmIn0.eyJhdWQiOiJlZTdiMjZiMy0xODVmLTQ0MTYtOGIxYy1iZDQ5OGViOGU2NTYiLCJqdGkiOiIwMDQ3MmExMjBlYzU5NjQyNzdkZWIwZmJhY2U1NjRiNWQzNTRjZWQ2Zjc1MmM1MmYyMDUwN2E5Yjg5NWQ4MThhMjBjY2ZiN2ZiZmViNTdhZiIsImlhdCI6MTcyMDA5NTIwNSwibmJmIjoxNzIwMDk1MjA1LCJleHAiOjE3MzU4NjI0MDAsInN1YiI6IjExMjI5NjEwIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMxODMwNTgyLCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiMmZkMTI0MmQtZmJkMy00ZTdjLTg0NTMtOGYzZjQ3YWFmNzMxIn0.H4azPXF38LchwPKTPgcEsF1aF2AitCAezpjlTlqj8It40CFznmXitc_ftILGZsUwc8Nckf3gvj07auEiR0lcFtYqfpE40gGXuDYhCTUeOdE9JikLt9rRarvRHuD1KKHZxWz_RratZrNqNDSbmonvGhOHxMBdtAOBPF78GM2wuOFECMkZYXT2jdxetdQaEfgpMqm6dSb3gR_EMT971Pr-BUghYEhZbIMxwq0zO7v10-FaG8atGUyfCxTSCdBFUt_yYsiXHjPyQK1GKfGlrluWxJpUKxM0CU0_udxJdKlZwxRPxjGLr5bpLdZ4vHhCnu3u9OgJ6JzS8l_U2rF50P4zXQ'),
        'domain' => env('AMOCRM_DOMAIN', 'mumar.amocrm.ru'),
        'leadId' => env('AMOCRM_LEAD_ID', 1551521),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
