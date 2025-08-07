<?php
namespace App\Controllers\User;

use App\Controllers\DBController;
use App\Model\User\UnlockModel;
use PDOException;

$root = str_replace('src\Controllers\User', '', __DIR__);
require_once $root . 'vendor\autoload.php';

class UnlockController extends DBController
{
    private $db;
    private $result;
    // private $basepath;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connection();
    }
    public function Unlocked ($section){
        $this->result = $section;
        return; $this->result;
    }


     public function handle($section, $callback) {
        // เช็กว่า unlock form ถูก submit และถูกต้องรึยัง
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['input_lock'])) {
            if ($this->unlockSection($_POST['input_lock'] ,$section)) {
                // ปลดล็อกสำเร็จ
                call_user_func($callback);
            } else {
                echo 'Invalid password';
            }
        } else {
            // ยังไม่ปลดล็อก
            header('Location: unlock/' . $section);
            exit;
        }
    }


    public function unlockSection($password)
    {
        $this->result = null;
        
        try {
            $UnlockModel = new UnlockModel($this->db);
            $this->result = $UnlockModel->validateUnlock($password);
        } catch (PDOException $e) {
           $this->result = false;
        }

        return $this->result;
    }
}
