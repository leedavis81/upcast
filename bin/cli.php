<?php

// Set the default timezone
date_default_timezone_set('Europe/London');


use Upcast\Meeting\ScheduleConfig;
use Upcast\Meeting\Schedule;

require '../autoloader.php';

$schedule = new Schedule(new ScheduleConfig());
$schedule->execute();