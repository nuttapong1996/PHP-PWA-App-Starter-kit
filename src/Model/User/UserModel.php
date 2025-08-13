<?php
namespace App\Model\User;

use PDO;
use PDOException;

class UserModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_login");
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getProfile()
    {
        $auth_user = $_SERVER['jwt_payload'] ?? null;
        $usercode  = $auth_user['user_code'] ?? null;

        try {
            $stmt = $this->conn->prepare("SELECT user_code,username,name FROM tbl_login WHERE user_code = :usercode");
            $stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getProfileByCode($usercode)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_login WHERE user_code = :usercode");
            $stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getEmailByEmail($email)
    {
        try {
            $stmt = $this->conn->prepare("SELECT email FROM tbl_login WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }
}
