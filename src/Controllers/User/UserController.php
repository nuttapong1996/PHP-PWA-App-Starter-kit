<?php
namespace App\Controllers\User;

use App\Controllers\DBController;
use App\Model\User\UserModel;
use PDOException;

$root = str_replace('src\Controllers\User', '', __DIR__);
require_once $root . 'vendor\autoload.php';

class UserController extends DBController
{
    private $db;
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connection();
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getUserAll()
    {
        $this->result = null;

        try {
            $userModel    = new UserModel($this->db);
            $this->result = $userModel->getAll();
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getUserProfile()
    {
        $this->result = null;
        try {
            $userModel    = new UserModel($this->db);
            $this->result = $userModel->getProfile();
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function getUserProfileByCode($usercode)
    {
        $this->result = null;
        try {
            $userModel    = new UserModel($this->db);
            $this->result = $userModel->getProfileByCode($usercode);
        } catch (PDOException $e) {
            return false;
        }
        return $this->result;
    }

    public function getUserIP()
    {
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }

    public function getUserDeviceType()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $ua        = strtolower($userAgent);
        if (strpos($ua, 'iphone') !== false) {
            return 'iPhone';
        }

        if (strpos($ua, 'ipad') !== false) {
            return 'iPad';
        }

        if (strpos($ua, 'android') !== false) {
            return 'Android';
        }

        if (strpos($ua, 'windows') !== false) {
            return 'Windows PC';
        }

        if (strpos($ua, 'macintosh') !== false) {
            return 'Mac';
        }

        if (strpos($ua, 'linux') !== false) {
            return 'Linux PC';
        }

        return 'Unknown';
    }

}
