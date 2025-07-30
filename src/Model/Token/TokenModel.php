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

    public function getRefreshToken($usercode)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM refresh_tokens WHERE user_code = :usercode AND revoked = 0 AND expires_at > NOW() ORDER BY id DESC LIMIT 1');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getExpiresToken($usercode)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM refresh_tokens WHERE user_code = :usercode  AND expires_at < NOW()');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getRevokeToken($usercode)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM refresh_tokens WHERE user_code = :usercode  AND revoked = 1 OR expires_at < NOW() - INTERVAL 7 DAY');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
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

    public function updateRevokeToken($usercode, $revoke_reason)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE refresh_tokens SET revoked = 1 , revoked_at = NOW(), revoked_reason = :token_remark WHERE user_code = :usercode AND revoked = 0 AND expires_at < NOW()');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->BindParam(':token_remark',$revoke_reason, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteRevokeRefreshToken()
    {

    }
}
