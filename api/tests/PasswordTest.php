<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use App\Types\Password;

final class PasswordTest extends TestCase {
    public function testModifiesGivenPassword(): void {
        $this->assertNotEquals(
            "hot-dog-daddy-99",
            Password::fromString("hot-dog-daddy-99")
        );
    }

    public function testRejectsEmptyPassword(): void {
        $this->expectException(InvalidArgumentException::class);

        Password::fromString("");
    }
}
