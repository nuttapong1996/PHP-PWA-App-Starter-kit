<?php
namespace App\Controllers\User;

use App\Controllers\DBController;
use App\Model\UserModel;
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
            $userModel = new UserModel($this->db);
            $this->result = $userModel->getProfile();
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }
}