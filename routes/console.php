<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Economic Year Auto-Update Scheduler
// This runs daily as a backup to ensure the current economic year is always up-to-date
Schedule::call(function () {
    if (config('economic-year.fallback_scheduler', true)) {
        \App\Models\EconomicYear::updateCurrentYear();
    }
})->daily()->at('00:05')->name('update-current-economic-year');
