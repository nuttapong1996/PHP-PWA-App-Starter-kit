<?php
namespace App\Middleware;

use App\Controllers\DBController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;
use PDOException;

class PersonalMiddleware extends DBController
{
    private $db;
    private $usercode;
    private $password;
    private $root;
    private $secret_key;
    private $access_token_name;
    private $basepath;

    public function __construct()
    {
        $this->root = str_replace('src\Middleware', '', __DIR__);
        require_once $this->root . 'vendor\autoload.php';

        parent::__construct();
        $this->db = $this->connection();

        $this->basepath          = $_ENV['BASE_PATH'];
        $this->secret_key        = $_ENV['SECRET_KEY'];
        $this->access_token_name = $_ENV['APP_NAME'] . '_access_token';
    }

    public function handle($section, $callback)
    {

        if (! isset($_COOKIE[$this->access_token_name])) {
            header('Location: ' . $this->basepath . '/login');
            exit;
        }
        
        try {
            $decode         = JWT::decode($_COOKIE[$this->access_token_name], new Key($this->secret_key, 'HS256'));
            $this->usercode = $decode->data->user_code;
        } catch (PDOException $e) {
            header('Location: ' . $this->basepath);
            exit;
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['input_lock'])) {

                $stmt = $this->db->prepare('SELECT password FROM tbl_login WHERE user_code = :usercode');
                $stmt->BindParam(':usercode', $this->usercode, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (! $user) {
                    return false;
                }

                // if ($user || password_verify($this->password, $user['password'])) {
                if ($this->password === $user['password']) {
                    $callback();
                    return true;
                } else {
                    echo "รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่";
                    return;
                }

                // ถ้ายังไม่ submit password ก็ redirect ไปหน้า unlock
                header("Location: /unlock/" . $section);
                exit;
            }
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            return false;
        }
    }
}
