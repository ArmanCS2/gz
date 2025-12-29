<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule short content generation twice daily
Schedule::command('groohbaz:generate-content')
    ->withoutOverlapping()
    ->onFailure(function () {
        \Log::error('Scheduled content generation failed');
    });
