<?php
namespace App\Controllers;

use App\Middleware\PersonalMiddleware;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;

class UnlockController
{
    private $root;
    private $secret_key;
    private $access_token_name;
    private  $basepath;

    public function __construct()
    {
        $this->root = str_replace('src\Controllers', '', __DIR__);
        require_once $this->root . 'vendor\autoload.php';

        $dotenv = Dotenv::createImmutable($this->root);
        $dotenv->load();

        $this->secret_key        = $_ENV['SECRET_KEY'];
        $this->access_token_name = $_ENV['APP_NAME'] . '_access_token';
        $this->basepath = $_ENV['BASE_PATH'];
    }

    public function showForm($section)
    {
        $section = htmlspecialchars($section); 
        //  header('Location: ' .$this->basepath.'/unlock'.'/'. $section);
        include $this->root . 'view/unlock.php';
    }

    public function unlockSection($section)
    {

        if (! isset($_COOKIE[$this->access_token_name])) {
            http_response_code(401);
            exit('Unauthorized');
        }

        try {
            $decode   = JWT::decode($_COOKIE[$this->access_token_name], new Key($this->secret_key, 'HS256'));
            $usercode = $decode->data->user_code;
            $pass     = $_POST['input_lock'] ?? '';

            $PersonalMiddleware = new PersonalMiddleware($usercode, $pass);

            $PersonalMiddleware->handle(function () use ($section) {
                echo "Unlocked !".$section;
                // header('Location: ' .$this->basepath.'/'. $section);

            });
            echo "รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่";
            
        } catch (PDOException $e) {
            http_response_code(401);
            echo "Token ผิดพลาดหรือหมดอายุ";
        }
    }
}
