<?php
namespace App\Controllers\Auth;

use App\Controllers\DBController;
use App\Model\Auth\AuthModel;
use PDOException;

$root = dirname(__DIR__, 3);
require_once $root . '/vendor/autoload.php';

class AuthController extends DBController
{
    private $db;
    private $result;
    private $AuthModel;

    public function __construct()
    {
        parent::__construct();
        $this->db        = $this->connection();
        $this->AuthModel = new AuthModel($this->db);
    }

    public function login($username)
    {
        $this->result = null;
        try {
            $this->result = $this->AuthModel->login($username);
            return $this->result;
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function register($usercode, $name, $username, $password, $email, $idenCode)
    {
        $this->result = null;
        try {
            $this->result = $this->AuthModel->register($usercode, $name, $username, $password, $email, $idenCode);
            return $this->result;
        } catch (PDOException $e) {
            $this->result = false;
        }
    }

    public function forgot($usercode, $idenCode)
    {
        $this->result = null;
        try {
            $this->result = $this->AuthModel->forgot($usercode, $idenCode);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function insertResetToken($usercode, $resetToken, $expr)
    {
        $this->result = null;
        try {
            $this->result = $this->AuthModel->insertResetToken($usercode, $resetToken, $expr);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getResetToken($usercode , $resetToken)
    {
        $this->result = null;
        try {
            $this->result = $this->AuthModel->getResetToken($usercode , $resetToken);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function reset($usercode, $password)
    {
        $this->result = null;
        try {
            $this->result = $this->AuthModel->reset($usercode, $password);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }
}
