<?php namespace App\Endpoints;

use App\Lib\Logger;
use App\Lib\Request;
use App\Lib\Response;
use App\Endpoints\User\RegisterUserInfo;

/**
 * @codeCoverageIgnore
 */
final class User {
    private function __construct() {}

    public static function register() {
        $req = new Request();
        $register_data = $req->getJSON();
        
        $res = new Response();
        
        try {
            $user = RegisterUserInfo::create(
                $register_data->name,
                $register_data->email,
                $register_data->password
            );
        
            // Insert into database
            $logger = new Logger();
            $logger->info("registering your user: " . print_r($user, true));
        
            $res->status(200);
        } catch (InvalidArgumentException $e) {
            $res->status(400);
            $resdata = array(
                'message' => $e->getMessage(),
            );
        }
        
        $res->toJSON($resdata);
    }
}
