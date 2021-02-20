<?php declare(strict_types=1);
namespace App\Types;

final class Password {
    private const MIN_LENGTH = 4;
    private $hashed;

    public static function fromString($plaintext): self {
        self::validatePassword($plaintext);
        return new Password(password_hash($plaintext, PASSWORD_DEFAULT));
    }

    private function __construct(string $hashed) {
        $this->hashed = $hashed;
    }

    private static function validatePassword($password) {
        if (strlen($password) < self::MIN_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf(
                    "%d characters is too few characters",
                    strlen($password)
                )
            );
        }
    }
}
