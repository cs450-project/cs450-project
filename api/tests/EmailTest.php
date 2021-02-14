<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase {
	public function testCanBeCreatedFromValidEmailAddres(): void {
		$this->assertInstanceOf(
			Email::class,
			Email::validateAndCreate("user+dingus2@woopwoop.net")
		);
	}

	public function testCannotBeCreatedFromInvalidEmailAddress(): void {
		$this->expectException(InvalidArgumentException::class);

		Email::validateAndCreate("this_is_invalid@xxx");
	}

	public function testCanBeUsedAsString(): void {
		$this->assertEquals(
			"bigdaddyflex@aol.com",
			Email::validateAndCreate("bigdaddyflex@aol.com")
		);
	}
}
?>
