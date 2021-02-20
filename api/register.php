<?php

require __DIR__ . '/vendor/autoload.php';

use App\Lib\Logger;
use App\Lib\Request;
use App\Lib\Response;
use App\Endpoints\User;
use App\Endpoints\User\RegisterUserInfo;

Logger::enableSystemLogs();

$req = new Request();
$register_data = $req->getJSON();

try {
    $userInfo = RegisterUserInfo::create(
        $register_data->name,
        $register_data->email,
        $register_data->password
    );

    echo Response::ok()
        ->toJSON(User::register($userInfo));
} catch (InvalidArgumentException $e) {
    echo Response::error()->toJSON((object) array(
        'message' => $e->getMessage(),
    ));
}
