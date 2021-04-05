<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use CS450\Model\User;
use CS450\Model\User\RegisterUserInfo;

final class UserTest extends TestCase {

    private static $container;

    public static function setUpBeforeClass(): void {
        self::$container = require __DIR__ . '/testdata/bootstrap.php';
    }

    public function testRegisterCreatesJwtWithGoodData(): void {
        $jwtService = self::$container->get(CS450\Service\JwtService::class);
        $registerInfo = RegisterUserInfo::create("test", "hi@example.com", "Abc12345");

        $user = self::$container->get('CS450\Model\User');
        $jwt = $user->register($registerInfo);

        $this->assertTrue(
            array_key_exists(
                'uid',
                $jwtService->decode($jwt, 
                    self::$container->get('jwt.key'), 
                    array('HS256')
                )
            ),
        );
    }
}
