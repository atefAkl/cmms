<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Monitoring Settings
    |--------------------------------------------------------------------------
    |
    | This file defines settings for temperature and humidity monitoring.
    |
    */

    'temperature' => [
        // Cooldown period between readings in minutes
        'cooldown_minutes' => 60,

        // Allow users to select the reading time manually
        'allow_user_time_selection' => false,
        
        // Tolerance for alerts if temperature exceeds profile limits
        'alert_tolerance' => 1.0,
    ],

    'humidity' => [
        'cooldown_minutes' => 120,
        'allow_user_time_selection' => false,
    ],
];
