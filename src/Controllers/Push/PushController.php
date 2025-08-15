<?php
namespace App\Controllers\Push;

use App\Controllers\DBController;
use App\Model\Push\PushModel;
use PDOException;

$root = str_replace('src\Controllers\Push', '', __DIR__);
require_once $root . 'vendor\autoload.php';

class PushController extends DBController
{
    private $db;
    private $result;
    private $PushModel;

    public function __construct()
    {
        parent::__construct();
        $this->db        = $this->connection();
        $this->PushModel = new PushModel($this->db);
    }

    // Function get Push subscription from Database.
    public function getSub($userCode, $endPoint)
    {
        $this->result = null;

        try {
            $this->result = $this->PushModel->getSub($userCode, $endPoint);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    // Function insert or create subscription in Database.
    public function insertSub($userCode, $userDevice, $userIp, $endPoint, $publicKey, $authKey)
    {
        $this->result = null;

        try {
            $this->result = $this->PushModel->insertSub($userCode, $userDevice, $userIp, $endPoint, $publicKey, $authKey);
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    // Function delete subscription from Database.
    public function deleteSub($userCode, $endPoint)
    {
        $this->result = null;

        try {
            $this->result = $this->PushModel->deleteSub($userCode, $endPoint);
        } catch (PDOException $e) {
            $this->result = false;
        }
    }
}
