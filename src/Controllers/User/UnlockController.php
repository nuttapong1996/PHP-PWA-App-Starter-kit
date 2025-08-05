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

    // public function showForm($section)
    // {
    //     $section = htmlspecialchars($section); 
    //     //  header('Location: ' .$this->basepath.'/unlock'.'/'. $section);
    //     include $this->root . 'view/unlock.php';
    // }

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
