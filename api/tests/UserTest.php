<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use CS450\Model\User;
use CS450\Lib\EmailAddress;

final class UserTest extends TestCase {

    public function testCreatesFromBuilder(): void {
        $pwHash = password_hash("test.PASSword.secure", PASSWORD_DEFAULT);
        $user = User::builder()
            ->id(1)
            ->name("Test")
            ->email("test@example.net")
            ->role("TEST")
            ->password($pwHash)
            ->department(1)
            ->build();

        $this->assertEquals(1, $user->getUid());
        $this->assertEquals("Test", $user->getName());
        $this->assertEquals(
            EmailAddress::fromString("test@example.net"),
            $user->getEmail()
        );
        $this->assertEquals(
            $pwHash,
            $user->getPasswordHash()
        );
        $this->assertEquals(1, $user->getDepartment());
    }
}
