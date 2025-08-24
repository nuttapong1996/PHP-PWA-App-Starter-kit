<?php
namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private $db_driver;
    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $db_port;

    public $conn;

    public function __construct($db_driver, $db_host, $db_user, $db_pass, $db_name, $db_port)
    {
        $this->db_driver  = $db_driver;
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
        $this->db_port = (int) ($db_port ?? 3306);
    }

    public function DBconnection()
    {
        $this->conn = null;

        try {
             $dsn = "{$this->db_driver}:host={$this->db_host};port={$this->db_port};dbname={$this->db_name};";
            $this->conn = new PDO($dsn , $this->db_user, $this->db_pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Could not connect to database' . $e->getMessage();
        }
        return $this->conn;
    }
}
