<?php
namespace App\Controllers\Auth;

use App\Controllers\DBController;
use App\Model\Auth\AuthModel;
use PDOException;

$root = str_replace('src\Controllers\Auth', '', __DIR__);
require_once $root . 'vendor\autoload.php';

class AuthController extends DBController
{
    private $db;
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connection();
    }

    public function login($username, $password)
    {
        $this->result = null;
        try {
            $AuthModel    = new AuthModel($this->db);
            $this->result = $AuthModel->login($username, $password);
            return $this->result;
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function register()
    {

    }
}
