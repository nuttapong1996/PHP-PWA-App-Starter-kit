<?php
namespace App\Middleware;

use PDOException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$root = str_replace('src\Middleware','',__DIR__);
require_once $root . 'vendor\autoload.php';

class JwtMiddleware
{
    private $secret_key;

    public function __construct($secret_key)
    {
        $this->secret_key = $secret_key;
    }

    public function handle($callback)
    {
        //ดึง token จาก cookie
        $access_token = $_COOKIE['myapp_access_token'] ?? '';

        if (! $access_token) {
            // กลับไป หน้า login
            header('Location: /login');
        }

        try {
            $decoded = JWT::decode($access_token, new Key($this->secret_key, 'HS256'));
            
        // แนบข้อมูล user ไปที่ global เพื่อใช้ใน controller ได้
        $_SERVER['jwt_payload'] = (array) $decoded->data;

        return $callback();

        } catch (PDOException $e) {
            http_response_code(401);
            echo json_encode([
                'code'    => 401,
                'status'  => 'error',
                'message' => 'Invalid or expired token',
                'error'   => $e->getMessage()]);
             header('Location: /login');
            exit;
        }
    }
}
