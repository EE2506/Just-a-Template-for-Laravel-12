<?php

/*
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */

return [
    'login' => [
        'max_attempts' => (int) env('SECURE_LOGIN_MAX_ATTEMPTS', 5),
        'decay_minutes' => (int) env('SECURE_LOGIN_DECAY_MINUTES', 15),
    ],

    'api' => [
        'per_minute' => (int) env('SECURE_API_RATE_LIMIT', 60),
        'admin_per_minute' => (int) env('SECURE_ADMIN_API_RATE_LIMIT', 120),
    ],
];
