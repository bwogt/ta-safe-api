<?php

return [
    'password_reset' => [
        'ttl' => env('PASSWORD_RESET_TTL', 600),
        'cooldown' => env('PASSWORD_RESET_COOLDOWN_TTL', 60),
        'max_attempts' => env('PASSWORD_RESET_MAX_ATTEMPTS', 3),
        'block' => env('PASSWORD_RESET_BLOCK_TTL', 1800),
    ],
];
