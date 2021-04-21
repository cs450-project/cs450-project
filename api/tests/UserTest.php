<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use CS450\Model\User;
use CS450\Lib\Password;
use CS450\Lib\EmailAddress;

final class UserTest extends TestCase {
    private static $db;
    private static $container;

    public static function setUpBeforeClass(): void {
        self::$container = require __DIR__ . '/testdata/bootstrap.php';
        self::$db = self::$container->get(CS450\Service\DbService::class);
    }

    protected function setUp(): void {
        $conn = self::$db->getConnection();
        $result = $conn->query("SET FOREIGN_KEY_CHECKS = 0");
        $result = $conn->query("TRUNCATE TABLE tbl_fact_users");
        $result = $conn->query(sprintf(
            "INSERT INTO tbl_fact_users (name, email, password, department) VALUES ('%s', '%s', '%s', %d)",
            "Test User",
            "test@example.com",
            Password::fromString("TestPassword1"),
            1
        ));
        $this->assertTrue($conn->error === "", $conn->error);
    }

    protected function tearDown(): void
    {
        $conn = self::$db->getConnection();
        $result = $conn->query("SET FOREIGN_KEY_CHECKS = 0");
        $result = $conn->query("TRUNCATE TABLE tbl_fact_users");
        $this->assertTrue($result != false);
    }

    public function testCreatesFromBuilder(): void {
        $pwHash = password_hash("test.PASSword.secure", PASSWORD_DEFAULT);
        $user = (new User(self::$db))
            ->setId(1)
            ->setName("Test")
            ->setEmail("test@example.com")
            ->setRole("FACULTY")
            ->setPasswordHash($pwHash)
            ->setDepartment(1);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals("Test", $user->getName());
        $this->assertEquals(
            EmailAddress::fromString("test@example.com"),
            $user->getEmail()
        );
        $this->assertEquals(
            $pwHash,
            $user->getPasswordHash()
        );
        $this->assertEquals(1, $user->getDepartment());
    }

    public function testWritesOnSave(): void {
        $pwHash = password_hash("test.PASSword.secure", PASSWORD_DEFAULT);
        $user = (new User(self::$db))
            ->setName("Test")
            ->setEmail("testWritesOnSave@example.net")
            ->setRole("FACULTY")
            ->setPasswordHash($pwHash)
            ->setDepartment(1)
            ->save();

        $result = self::$db->getConnection()->query("SELECT * FROM tbl_fact_users WHERE email='testWritesOnSave@example.net'");
        $data = $result->fetch_assoc();

        $this->assertEquals($user->getName(), $data["name"]);
        $this->assertEquals($user->getEmail(), EmailAddress::fromString($data["email"]));
        $this->assertEquals($user->getRole(), $data["user_role"]);
        $this->assertEquals($user->getDepartment(), $data["department"]);
    }
}
