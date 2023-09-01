<?php

return [
    // ...
    'fcm' => [
        'driver' => 'laravel-notification-channels/fcm',
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'service_account_file' => env('FIREBASE_CREDENTIALS'),
    ],
    // ...
];
