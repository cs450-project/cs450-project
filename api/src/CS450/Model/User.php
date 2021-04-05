<?php 

namespace CS450\Model;

use CS450\Lib\Password;
use CS450\Lib\EmailAddress;
use CS450\Model\User\RegisterUserInfo;

final class User {
    /**
     * 
     * @Inject("jwt.key")
     */
    private $jwtKey;

    /**
     * 
     * @Inject
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * 
     * @Inject
     * @var CS450\Service\JwtService
     */
    private $jwt;

    private function makeJwt(EmailAddress $email, Password $password): string {
        $payload = array(
            'uid' => 0
        );

        return $this->jwt->encode($payload, $this->jwtKey);
    }

    public function register(RegisterUserInfo $userInfo): string {
        return $this->makeJwt($userInfo->email, $userInfo->password);
    }
}
