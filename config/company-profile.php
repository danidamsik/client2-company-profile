<?php

return [
    'public_url' => env('COMPANY_PROFILE_PUBLIC_URL'),
    'pdf' => [
        'chrome_path' => env('BROWSERSHOT_CHROME_PATH'),
        'node_binary' => env('BROWSERSHOT_NODE_BINARY', 'node'),
        'npm_binary' => env('BROWSERSHOT_NPM_BINARY'),
        'window_width' => (int) env('COMPANY_PROFILE_PDF_WINDOW_WIDTH', 1440),
        'window_height' => (int) env('COMPANY_PROFILE_PDF_WINDOW_HEIGHT', 2200),
        'device_scale_factor' => (int) env('COMPANY_PROFILE_PDF_DEVICE_SCALE', 1),
        'timeout_seconds' => (int) env('COMPANY_PROFILE_PDF_TIMEOUT', 120),
        'delay_milliseconds' => (int) env('COMPANY_PROFILE_PDF_DELAY', 1200),
        'wait_for_ready_timeout' => (int) env('COMPANY_PROFILE_PDF_WAIT_FOR_READY_TIMEOUT', 20000),
        'paper_format' => env('COMPANY_PROFILE_PDF_PAPER_FORMAT', 'A4'),
        'margin_top' => (float) env('COMPANY_PROFILE_PDF_MARGIN_TOP', 8),
        'margin_right' => (float) env('COMPANY_PROFILE_PDF_MARGIN_RIGHT', 8),
        'margin_bottom' => (float) env('COMPANY_PROFILE_PDF_MARGIN_BOTTOM', 12),
        'margin_left' => (float) env('COMPANY_PROFILE_PDF_MARGIN_LEFT', 8),
    ],
];
