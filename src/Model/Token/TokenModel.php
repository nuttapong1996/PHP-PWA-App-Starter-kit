<?php
namespace App\Model\Token;

use PDO;
use PDOException;

class TokenModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getRefreshToken($usercode, $refresh_token)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM refresh_tokens WHERE user_code = :usercode AND token = :token LIMIT 1');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->BindParam(':token', $refresh_token, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertRefreshToken($usercode, $refresh_token, $expires)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO refresh_tokens (user_code, token, expires_at) VALUES (:usercode, :token, :expires)');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->BindParam(':token', $refresh_token, PDO::PARAM_STR);
            $stmt->BindParam(':expires', $expires, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteRefreshToken()
    {
        
    }

    public function deleteExpiresRefreshToken()
    {

    }
}
