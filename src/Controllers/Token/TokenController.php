<?php
namespace App\Controllers\Token;

use App\Controllers\DBController;
use App\Model\Token\TokenModel;
use PDOException;
use PDORow;

$root = dirname(__DIR__, 3);
require_once $root . '/vendor/autoload.php';

class TokenController extends DBController
{
    private $db;
    private $result;
    private $TokenModel;

    public function __construct()
    {
        parent::__construct();
        $this->db         = $this->connection();
        $this->TokenModel = new TokenModel($this->db);
    }

    public function getRefreshToken($usercode)
    {
        $this->result = null;
        try {

            $this->result = $this->TokenModel->getRefreshToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getRefreshTokenByID($usercode, $tokenid)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->getRefreshTokenByID($usercode, $tokenid);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getRefreshTokenList($usercode){
        $this->result = null;
        try {
            $this->result = $this->TokenModel->getRefreshTokenList($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getExpiresToken($usercode)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->getExpiresToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function getRevokeToken($usercode)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->getRevokeToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function insertRefreshToken($usercode, $token_id, $refresh_token, $device, $ip, $expires)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->insertRefreshToken($usercode, $token_id, $refresh_token, $device, $ip, $expires);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function updateToken($usercode, $token_id, $refresh_token, $device, $ip, $expires)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->updateToken($usercode, $token_id, $refresh_token, $device, $ip, $expires);
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
            $this->result = $this->TokenModel->updateExpiredToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function updateRevokeToken($usercode, $tokenid, $revoke_reason)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->updateRevokeToken($usercode, $tokenid, $revoke_reason);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function deleteExpiredToken($usercode)
    {
        $this->result = null;
        try {
            $this->result = $this->TokenModel->deleteExpiredToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function deleteAllToken($usercode)
    {
        $this->result = null;

        try {
            $this->result = $this->TokenModel->deleteAllToken($usercode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }
}
