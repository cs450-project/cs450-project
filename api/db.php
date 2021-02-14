<?php
class DatabaseError extends Error {
	public function __construct($message, $code = 0, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}

class DB {
	private $db_conn;

	public static function connect(): ?self {
		include 'config.php';

		$db_conn = new mysqli($db_servername, $db_username, $db_password, $db_database);

		if ($db_conn->connect_error) {
			throw new DatabaseError("Could not connect to database: " . mysqli_connect_error());
		}

		return new Self($db_conn);
	}

	private function __construct($db_conn) {
		$this->db_conn = $db_conn;
	}

	function registerUser(RegisterUserInfo $user) {
		$sql = "INSERT INTO users (name, email, password, created) VALUES (?, ?, ?, ?)";
		$stmt = $this->attemptDbOperation(function() {
			return $this->db_conn->prepare($sql);
		});
		
		$this->attemptDbOperation(function() {
			$stmt->bind_param(
				"ssss",
				$user->name,
				$user->email,
				$user->password,
				date("Y-m-d H:i:s")
			);
		});

		$this->attemptDbOperation(function() {
			$stmt->execute();
		});
	}

	private function attemptDbOperation($operation) {
		$result = operation();

		if(!result) {
			throw new DatabaseError($this->db_conn->error);
		}

		return result;
	}
}
?>
