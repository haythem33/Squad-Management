<?php

use Illuminate\Support\Facades\Schedule;

// Daily Database Backup at 01:03 (Safe from DST changes)
Schedule::command('backup:run --only-db')
    ->dailyAt('01:03')
    ->timezone('Europe/Brussels');

// Weekly Full Backup (Files + DB) on Sundays at 05:00
Schedule::command('backup:run')
    ->weeklyOn(0, '05:00')
    ->timezone('Europe/Brussels');
