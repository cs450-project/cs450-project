<?php

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/db.php');

use Ahc\Jwt\JWT;

function json_response($code = 200, $message = null)
{
    header_remove();
    http_response_code($code);
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    );
    header('Status: '.$status[$code]);
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'message' => $message
    ));
}

class InvalidDataError extends Error {
	public function __construct($message, $code = 0, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}

class Email {
	private $email;

	public static function validateAndCreate($email): ?self {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidDataError($email . " is not a valid email address.");
		}

		return new Self($email);
	}

	private function __construct(string $email) {
		$this->email = $email;
	}
}

class Password {
	private $hashed;

	public static function hashAndCreate($plaintext): self {
		return new Password(password_hash($plaintext, PASSWORD_DEFAULT));
	}

	private function __construct(string $hashed) {
		$this->hashed = $hashed;
	}
}

class RegisterUserInfo {
	public $name;
	public $email;
	public $password;

	public function create(string $name, string $email, string $password): ?self {
		$email = Email::validateAndCreate($email);
		$password = Password::hashAndCreate($password);

		return new Self($name, $email, $password);
	}

	private function __construct(string $name, Email $email, Password $password) {
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
	}
}

$json = file_get_contents('php://input');
$register_data = json_decode($json);

try {
	$user = RegisterUserInfo::create(
		$register_data->name,
		$register_data->email,
		$register_data->password
	);

	try {
		$db = DB::connect();
		$db->registerUser($user);
	} catch (DatabaseError $e) {
		echo json_response(500, $e->getMessage());
	}
} catch (InvalidDataError $e) {
	echo json_response(400, $e->getMessage());
}

?>
