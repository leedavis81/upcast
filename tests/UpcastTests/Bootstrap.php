<?php

$loader = require __DIR__ . '/../../vendor/autoload.php';

if (! isset($loader)) {
    throw new Exception('Unable to load autoload.php. Try running `php composer.phar install`');
}

define('APPLICATION_ENV', 'testing');