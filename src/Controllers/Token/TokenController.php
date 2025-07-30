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

    public function insertRefreshToken($usercode, $refresh_token, $expires)
    {
        $this->result = null;

        try {
            $TokenModel   = new TokenModel($this->db);
            $this->result = $TokenModel->insertRefreshToken($usercode, $refresh_token, $expires);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }
}
