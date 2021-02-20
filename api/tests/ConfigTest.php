<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use App\Lib\Config;

final class ConfigTest extends TestCase {
    public function testGetKeyExists(): void {
        $value = Config::get("COOLEST_DOG", "Nacho", __DIR__ . "/testdata/config.php");

        $this->assertEquals($value, "SNOOPY");
    }

    public function testGetsDefaultKeyWhenKeyNotSet(): void {
        $value = Config::get("2ND_COOLEST_DOG", "Nacho", __DIR__ . "/testdata/config.php");

        $this->assertEquals($value, "Nacho");
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetsDefaultKeyWhenConfigDoesNotExists(): void {
        $value = Config::get("COOLEST_DOG", "Nacho", __DIR__ . "/testdata/noconfig.php");

        $this->assertEquals($value, "Nacho");
    }
}
