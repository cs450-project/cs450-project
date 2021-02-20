<?php namespace App\Endpoints;

use App\Lib\Logger;
use App\Endpoints\User\RegisterUserInfo;

/**
 * @codeCoverageIgnore
 */
final class User {
    private function __construct() {}

    public static function register(RegisterUserInfo $userInfo): bool {

        // Insert into database
        $logger = new Logger();
        $logger->info("registering your user: " . print_r($userInfo, true));

        return true;
    }
}
