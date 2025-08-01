<?php
namespace App\Controllers\Token;

use App\Controllers\DBController;
use App\Model\Token\TokenModel;
use PDOException;

$root = str_replace('src\Controllers\Token', '', __DIR__);
require_once $root . 'vendor\autoload.php';

class TokenController extends DBController
{
    private $db;
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connection();
    }

    public function getRefreshToken($usercode)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->getRefreshToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getRefreshTokenByID($usercode, $tokenid)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->getRefreshTokenByID($usercode, $tokenid);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getExpiresToken($usercode)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->getExpiresToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function getRevokeToken($usercode)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->getRevokeToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function insertRefreshToken($usercode, $token_id, $refresh_token, $device, $ip, $expires)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->insertRefreshToken($usercode, $token_id, $refresh_token, $device, $ip, $expires);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function updateToken($usercode, $token_id, $refresh_token, $device, $ip, $expires)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->updateToken($usercode, $token_id, $refresh_token, $device, $ip, $expires);
            return $this->result;
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function updateExpiredToken($usercode)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->updateExpiredToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function updateRevokeToken($usercode, $tokenid, $revoke_reason)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->updateRevokeToken($usercode, $tokenid, $revoke_reason);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function deleteToken($usercode)
    {
        $this->result = null;
        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->deleteToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }
}
