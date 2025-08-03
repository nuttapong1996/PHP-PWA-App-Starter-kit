<?php
namespace App\Middleware;

use App\Controllers\DBController;
use PDO;

class PersonalMiddleware extends DBController
{
    private $db;
    private $usercode;
    private $password;

    public function __construct($usercode, $password)
    {
        parent::__construct();
        $this->db       = $this->connection();
        $this->usercode = $usercode;
        $this->password = $password;
    }

    public function handle($callback)
    {
        $stmt = $this->db->prepare('SELECT password FROM tbl_login WHERE user_code = :usercode');
        $stmt->BindParam(':usercode', $this->usercode, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // if (! $user || ! password_verify($this->password, $user['password'])) {
        if ($this->password == $user['password']) {
            return false; // หรือโยน exception แล้วแต่คุณจะจัดการ
        }
        
        return $callback();
    }

}
