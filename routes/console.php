<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('messages:delete-expired')->everyMinute();
