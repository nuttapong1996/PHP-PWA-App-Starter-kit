<?php
namespace App\Model\Push;

use PDO;
use PDOException;

class PushModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getSub($userCode, $endPoint)
    {
        try {
            $stmt = $this->conn->prepare('SELECT endpoint , p256dh , authKey FROM push_subscribers WHERE user_code = :usercode AND endpoint = :endpoint');
            $stmt->BindParam(':usercode', $userCode, PDO::PARAM_STR);
            $stmt->BindParam(':endpoint', $endPoint, PDO::PARAM_STR);
            $stmt->excute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }
}
