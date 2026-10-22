<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('loyalty:expire-points')->dailyAt('02:00');