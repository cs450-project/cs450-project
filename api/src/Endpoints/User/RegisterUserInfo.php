<?php namespace App\Endpoints\User;

use App\Types\Password;
use App\Types\EmailAddress;

abstract class UserRole {
    const SUPERUSER = 1;
    const ADMINISTRATOR = 2;
    const FACULTY = 3;
}

/**
 * @codeCoverageIgnore
 */
final class RegisterUserInfo {
    public $name;
    public $email;
    public $password;
    public $created_at;
    public $role;

    public static function create(string $name, string $email, string $password, $role = UserRole::FACULTY): ?self {
        $email = EmailAddress::fromString($email);
        $password = Password::fromString($password);

        return new Self($name, $email, $password, $role);
    }

    private function __construct(string $name, EmailAddress $email, Password $password, $role) {
        date_default_timezone_set("America/New_York");

        $this->role = $role;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = date('d-m-Y h:i:s A');
    }
}
