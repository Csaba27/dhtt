<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('registration:status')->hourly();
