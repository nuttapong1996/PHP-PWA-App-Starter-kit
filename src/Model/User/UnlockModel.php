<?php
namespace App\Model\User;

use PDO;
use PDOException;

class UnlockModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function validateUnlock($password)
    {
        $auth_user = $_SERVER['jwt_payload'] ?? null;
        $usercode  = $auth_user['user_code'] ?? null;
        try {
            $stmt = $this->conn->prepare('SELECT * FROM tbl_login WHERE user_code = :usercode');
            $stmt->BindParam(':usercode', $usercode, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            
            if($password === $user['password']){
                return true;
            }else{
                return false;
            }
            
        } catch (PDOException $e) {
            return false;
        }
    }
}