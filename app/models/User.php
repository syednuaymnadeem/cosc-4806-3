<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    private $db;
    public function __construct() { $this->db = db_connect(); }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

    public function create_user($username, $password) {
        $db = db_connect();

        if ($this->username_exists($username)) {
            return "Username already exists.";
        }

        // Simplified password rule
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashed_password
        ]);

        return true;
    }

    public function authenticate($username, $password) {
        /*
         * if username and password good then
         * $this->auth = true;
         */
		$username = strtolower($username);
		$db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
		
		if (password_verify($password, $rows['password'])) {
			$_SESSION['auth'] = 1;
			$_SESSION['username'] = ucwords($username);
			unset($_SESSION['failedAuth']);
			header('Location: /home');
			die;
		} else {
			if(isset($_SESSION['failedAuth'])) {
				$_SESSION['failedAuth'] ++; //increment
			} else {
				$_SESSION['failedAuth'] = 1;
			}
			header('Location: /login');
			die;
		}
    }

}
