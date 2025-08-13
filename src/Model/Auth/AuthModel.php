<?php
namespace App\Model\Auth;

use PDO;
use PDOException;

class AuthModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($username, $password)
    {
        try {
            $stmt = $this->conn->prepare('SELECT user_code,username ,name FROM tbl_login WHERE username  = :username AND password = :password');
            $stmt->BindParam(':username', $username, PDO::PARAM_STR);
            $stmt->BindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function register($usercode, $name, $username, $password, $email)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO tbl_login (user_code, name, username, password, email) VALUES (:user_code, :name, :username, :password, :email)');
            $stmt->bindParam(':user_code', $usercode);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
