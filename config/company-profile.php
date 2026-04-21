<?php

return [
    'public_url' => env('COMPANY_PROFILE_PUBLIC_URL'),
    'browserless' => [
        'endpoint' => env('BROWSERLESS_PDF_ENDPOINT', 'https://production-sfo.browserless.io/pdf'),
        'token' => env('BROWSERLESS_TOKEN'),
        'request_timeout_seconds' => (int) env('BROWSERLESS_TIMEOUT', 180),
    ],
    'pdf' => [
        'goto_timeout_milliseconds' => (int) env('COMPANY_PROFILE_PDF_GOTO_TIMEOUT', 120000),
        'delay_milliseconds' => (int) env('COMPANY_PROFILE_PDF_DELAY', 1200),
        'wait_for_ready_timeout' => (int) env('COMPANY_PROFILE_PDF_WAIT_FOR_READY_TIMEOUT', 20000),
        'paper_format' => env('COMPANY_PROFILE_PDF_PAPER_FORMAT', 'A4'),
        'margin_top' => (float) env('COMPANY_PROFILE_PDF_MARGIN_TOP', 8),
        'margin_right' => (float) env('COMPANY_PROFILE_PDF_MARGIN_RIGHT', 8),
        'margin_bottom' => (float) env('COMPANY_PROFILE_PDF_MARGIN_BOTTOM', 12),
        'margin_left' => (float) env('COMPANY_PROFILE_PDF_MARGIN_LEFT', 8),
    ],
];
