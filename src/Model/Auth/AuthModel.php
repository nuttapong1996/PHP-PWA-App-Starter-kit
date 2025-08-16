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

    public function login($username)
    {
        try {
            $stmt = $this->conn->prepare('SELECT user_code,username ,name ,password FROM tbl_login WHERE username  = :username');
            $stmt->BindParam(':username', $username, PDO::PARAM_STR);
            // $stmt->BindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function register($usercode, $name, $username, $password, $email ,$idenCode)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO tbl_login (user_code, name, username, password, email ,iden_code) VALUES (:user_code, :name, :username, :password, :email ,:idenCode)');
            $stmt->bindParam(':user_code', $usercode);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':idenCode', $idenCode);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // public function forgot
}
