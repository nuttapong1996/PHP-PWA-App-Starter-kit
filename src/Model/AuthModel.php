<?php 
namespace App\Model;

use PDO;
use PDOException;

class AuthModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($username,$password)
    {
        try {
            $stmt = $this->conn->prepare( 'SELECT user_code,username ,name FROM tbl_login WHERE username  = :username AND password = :password');
            $stmt->BindParam(':username',$username , PDO::PARAM_STR);
            $stmt->BindParam(':password',$password , PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function register(){
        
    }

    public function logout(){

    }
    
    
}