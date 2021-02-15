<?php

require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Endpoints\User;

App::enableLogs();
User::register();