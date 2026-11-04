<?php

/*
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */

return [
    'name' => env('WATERMARK_AUTHOR_NAME', 'DJ Punzalan'),
    'url' => env('WATERMARK_AUTHOR_URL', 'https://github.com/EE2506'),
    'email' => env('WATERMARK_AUTHOR_EMAIL', '{{AUTHOR_EMAIL}}'),
    'license' => env('WATERMARK_LICENSE', ''),
    'year' => (int) env('WATERMARK_YEAR', date('Y')),
];
