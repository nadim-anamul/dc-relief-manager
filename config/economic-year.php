<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Economic Year Auto Update Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls the automatic updating of the current
    | economic year based on the current date.
    |
    */

    'auto_update' => env('ECONOMIC_YEAR_AUTO_UPDATE', true),
    
    /*
    |--------------------------------------------------------------------------
    | Update Interval
    |--------------------------------------------------------------------------
    |
    | The interval in seconds between automatic checks for economic year updates.
    | Default is 3600 seconds (1 hour).
    |
    */
    'update_interval' => env('ECONOMIC_YEAR_UPDATE_INTERVAL', 3600),
    
    /*
    |--------------------------------------------------------------------------
    | Fallback Scheduler
    |--------------------------------------------------------------------------
    |
    | Enable the backup scheduler that runs daily as a safety net.
    | This ensures the economic year is updated even if the middleware
    | doesn't run for some reason.
    |
    */
    'fallback_scheduler' => env('ECONOMIC_YEAR_FALLBACK_SCHEDULER', true),
    
    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable logging of economic year changes for audit purposes.
    |
    */
    'log_changes' => env('ECONOMIC_YEAR_LOG_CHANGES', true),
];
