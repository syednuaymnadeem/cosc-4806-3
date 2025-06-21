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


    private function username_exists($username) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :u");
        $stmt->execute([':u' => strtolower($username)]);
        return $stmt->fetch() !== false;
    }



    private function logAttempt(string $username,string $status): void
    {
        $sql = "INSERT INTO login_log (username,status) VALUES (:u,:s)";
        $this->db->prepare($sql)->execute([':u'=>$username,':s'=>$status]);
    }


    private function failedAttemptsLastMinute(string $username): int
    {
        $sql="SELECT COUNT(*) FROM login_log
              WHERE username=:u AND status='bad'
                AND ts > DATE_SUB(NOW(), INTERVAL 60 SECOND)";
        $st=$this->db->prepare($sql);
        $st->execute([':u'=>$username]);
        return (int)$st->fetchColumn();
    }

    private function isLockedOut(string $username): bool  
    {
        return $this->failedAttemptsLastMinute($username) >= 3;
    }


    public function authenticate($username, $password) {


        if ($this->isLockedOut($username)) {
            $_SESSION['error'] = "Locked out for 60 seconds after 3 failed logins.";
            header('Location: /login');
            die;
        }

        $username = strtolower($username);
        $db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $rows['password'])) {
        
            $this->logAttempt($username,'good');

            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            header('Location: /home');
            die;
        } else {
         
            $this->logAttempt($username,'bad');

            if(isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth'] ++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            header('Location: /login');
            die;
        }
    }


    public function get_all_users() {
        $stmt = $this->db->prepare("SELECT username FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
