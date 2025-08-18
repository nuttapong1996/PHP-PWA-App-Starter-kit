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

    public function register($usercode, $name, $username, $password, $email, $idenCode)
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

    public function forgot($usercode, $idenCode)
    {
        try {
            $stmt = $this->conn->prepare('SELECT user_code FROM tbl_login WHERE user_code =:usercode AND iden_code =:idencode');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->BindParam(':idencode', $idenCode, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertResetToken($usercode, $resetToken, $expr)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE tbl_login SET reset_token =:resetToken , reset_expires = :expr WHERE user_code =:usercode');
            $stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->bindParam(':resetToken', $resetToken, PDO::PARAM_STR);
            $stmt->bindParam(':expr', $expr, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getResetToken($usercode , $resetToken)
    {
        try {
            $stmt = $this->conn->prepare('SELECT reset_token, reset_expires FROM tbl_login WHERE user_code = :usercode AND reset_token = :resetToken AND reset_expires > NOW();');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->BindParam(':resetToken', $resetToken, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function reset($usercode, $password)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE tbl_login SET password = :password , reset_date = NOW() ,reset_token = NULL , reset_expires = NULL WHERE user_code = :usercode');
            $stmt->bindParam(':usercode', $usercode);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }
}
