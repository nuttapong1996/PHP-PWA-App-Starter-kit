<?php
namespace App\Controllers\Push;

use App\Controllers\DBController;
use PDOException;

class PushController extends DBController
{
    private $db;
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connection();
    }

    // Function get Push subscribtion.
    public function getSub($userCode, $endPoint)
    {
        $this->result = null;

        try {
            $PushController = new PushController($this->db);
            $this->result   = $PushController->getSub($userCode, $endPoint);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }
}
