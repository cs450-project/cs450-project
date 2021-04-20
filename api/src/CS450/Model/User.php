<?php

namespace CS450\Model;

use CS450\Lib\Password;
use CS450\Lib\EmailAddress;


final class UserBuilder {
    public $id;
    public $name;
    public $email;
    public $role;
    public $password;
    public $department;

    function __construct() {}

    function build() {
        return new User($this);
    }

    function id($id) {
        $this->id = $id;
        return $this;
    }

    function name($name) {
        $this->name = $name;
        return $this;
    }

    function email($email) {
        $this->email = $email;
        return $this;
    }

    function role($role) {
        $this->role = $role;
        return $this;
    }

    function password($password) {
        $this->password = $password;
        return $this;
    }

    function department($department) {
        $this->department = $department;
        return $this;
    }
}

final class User {
    private $uid;
    private $name;
    private $email;
    private $passwordHash;
    private $role;
    private $department;

    public static function builder(): UserBuilder {
        return new UserBuilder();
    }

    public function __construct(UserBuilder $builder) {
        $this->uid = $builder->id;
        $this->email = EmailAddress::fromString($builder->email);
        $this->passwordHash = $builder->password;
        $this->name = $builder->name;
        $this->role = $builder->role;
        $this->department = $builder->department;
    }

    public function getUid(): int {
        return $this->uid;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): EmailAddress {
        return $this->email;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function getRole() {
        return $this->role;
    }

    public function getDepartment() {
        return $this->department;
    }
}
