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
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertSub($userCode, $userDevice,$userIp, $endPoint, $publicKey, $authKey)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO push_subscribers(user_code, device_name , ip_address , endpoint,p256dh,authKey) VALUES (:usercode,:device,:ip,:endpoint,:pub_key,:auth_key)');
            $stmt->BindParam(':usercode', $userCode, PDO::PARAM_STR);
            $stmt->BindParam(':device', $userDevice, PDO::PARAM_STR);
            $stmt->BindParam(':ip', $userIp, PDO::PARAM_STR);
            $stmt->BindParam(':endpoint', $endPoint, PDO::PARAM_STR);
            $stmt->BindParam(':pub_key', $publicKey, PDO::PARAM_STR);
            $stmt->BindParam(':auth_key', $authKey, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteSub($userCode, $endPoint)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM push_subscribers WHERE user_code =:usercode AND endpoint =:endpoint');
            $stmt->BindParam(':usercode', $userCode, PDO::PARAM_STR);
            $stmt->BindParam(':endpoint', $endPoint, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }
}
