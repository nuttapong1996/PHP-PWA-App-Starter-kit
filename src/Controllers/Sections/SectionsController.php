<?php
namespace App\Controllers\Sections;

use App\Controllers\DBController;
use App\Model\Sections\SectionsModel;
use PDOException;

$root = dirname(__DIR__, 3);
require_once $root . '/vendor/autoload.php';

class SectionsController extends DBController
{
    private $db;
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connection();
    }

    public function validate($password)
    {
        $this->result = null;

        try {
            $UnlockModel  = new SectionsModel($this->db);
            $this->result = $UnlockModel->validate($password);
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }
}
