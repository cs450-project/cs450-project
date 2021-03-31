<?php namespace App\Endpoints;

use Firebase\JWT\JWT;

use App\Lib\Logger;
use App\Lib\Config;
use App\Types\Password;
use App\Types\EmailAddress;
use App\Endpoints\User\RegisterUserInfo;

final class User {
    private function __construct() {}

    private static function makeJwt(EmailAddress $email, Password $password): string {
        $payload = array();
        // usually you'd query the db and check if set or whatever
        // but this is how you'd send a jwt back
        if (isset($email) && isset($password)) {
            $payload['uid'] = 0;
        } else {
            $payload['error'] = 'This is not fully implemented! But you have to AT LEAST give a user/password';
        }

        $key = Config::get('KEY');
        return JWT::encode($payload, $key);
    }

    public static function register(RegisterUserInfo $userInfo): string {

        // Insert into database
        $logger = new Logger();
        $logger->info("registering your user: " . print_r($userInfo, true));

        return Self::makeJwt($userInfo->email, $userInfo->password);
    }
}
