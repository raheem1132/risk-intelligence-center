<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('alerts:dispatch')->everyFifteenMinutes()->withoutOverlapping();
Schedule::command('alerts:dispatch --weekly')->sundays()->at('08:00')->timezone('Asia/Jakarta')->withoutOverlapping();
