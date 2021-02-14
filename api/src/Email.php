<?php declare(strict_types=1);

final class Email {
	private $email;

	public static function validateAndCreate(string $email): ?self {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException(
				sprintf(
					"%s is not a valid email address.",
					$email
				)
			);
		}

		return new Self($email);
	}

	private function __construct(string $email) {
		$this->email = $email;
	}

	public function __toString(): string {
		return $this->email;
	}
}
?>
